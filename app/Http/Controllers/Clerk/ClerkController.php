<?php

namespace App\Http\Controllers\Clerk;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Patient;
use App\Models\Service;
use App\Models\DoctorUnavailability;
use Illuminate\Http\Request;
use App\Notifications\AppointmentStatusNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentCancelledMail;

class ClerkController extends Controller
{
    // -------------------------------
    // Dashboard / Queue
    // -------------------------------
    public function index(Request $request)
    {
        $selectedDate = $request->query('schedule_date', Carbon::today('Asia/Manila')->toDateString());
        $doctorId = $request->query('doctor_id');

        // Queue (appointments for selected date, including walk-ins and no-shows)
        $queueQuery = Appointment::with(['doctor', 'patient', 'user'])
            ->whereDate('appointment_date', $selectedDate)
            ->where(function($q) {
                $q->whereIn('status', ['pending', 'booked', 'checked-in', 'called', 'in-progress', 'in-session', 'no-show'])
                  ->orWhereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('is_walk_in', true);
            });

        if ($doctorId) {
            $queueQuery->where('doctor_id', $doctorId);
        }

        $queue = $queueQuery
            ->orderBy('appointment_time', 'asc')
            ->orderBy('queue_number', 'asc')
            ->get();

        // --- DYNAMIC NO-SHOW CALCULATION ---
        $now = Carbon::now('Asia/Manila');

        $queue->transform(function ($appointment) use ($now) {
            if (in_array(strtolower($appointment->status), ['pending', 'booked'])) {
                
                // 1. Extract only the date part (YYYY-MM-DD)
                $dateOnly = Carbon::parse($appointment->appointment_date)->toDateString();
                
                // 2. Extract only the time part
                $timeOnly = Carbon::parse($appointment->appointment_time)->format('H:i:s');
                
                // 3. Parse clean combined DateTime
                $appointmentDateTime = Carbon::parse("{$dateOnly} {$timeOnly}", 'Asia/Manila');

                // Allow one minute after the scheduled time before marking a no-show.
                $noShowAt = $appointmentDateTime->copy()->addMinute();

                if ($now->greaterThanOrEqualTo($noShowAt)) {
                    $appointment->status = 'no-show';
                    
                    // Persist the status update in the database
                    $appointment->save();

                    // --- ROBUST USER RESOLUTION FOR NOTIFICATIONS ---
                    $targetUser = null;

                    if ($appointment->relationLoaded('user') && $appointment->user) {
                        $targetUser = $appointment->user;
                    } elseif ($appointment->user_id) {
                        $targetUser = \App\Models\User::find($appointment->user_id);
                    } elseif ($appointment->patient && $appointment->patient->user_id) {
                        $targetUser = \App\Models\User::find($appointment->patient->user_id);
                    }

                    // Send the database notification using the resolved user model
                    if ($targetUser) {
                        $targetUser->notify(
                            new \App\Notifications\AppointmentStatusNotification('Your appointment has been marked as No-Show due to missed check-in time.')
                        );
                    }
                }
            }
            return $appointment;
        });

        $checkInDueAppointments = $queue->filter(function ($appointment) use ($now) {
            if (!in_array(strtolower($appointment->status ?: 'pending'), ['pending', 'booked'], true)) {
                return false;
            }

            $date = Carbon::parse($appointment->appointment_date)->toDateString();
            $time = Carbon::parse($appointment->appointment_time)->format('H:i:s');
            $appointmentDateTime = Carbon::parse("{$date} {$time}", 'Asia/Manila');

            return $now->greaterThanOrEqualTo($appointmentDateTime->copy()->subMinutes(20))
                && $now->lessThan($appointmentDateTime->copy()->addMinute());
        })->values();

        $upcoming = $queue->first() ? collect([$queue->first()]) : collect();

        $history = Appointment::with(['doctor', 'patient'])
            ->whereDate('appointment_date', '<', $selectedDate)
            ->orderBy('appointment_date', 'desc')
            ->get();

        $doctors = User::where('role', 'doctor')
            ->with(['unavailabilities' => function ($q) use ($selectedDate) {
                $q->where('date', $selectedDate);
            }])
            ->with(['appointments' => function ($q) use ($selectedDate) {
                $q->whereDate('appointment_date', $selectedDate);
            }])
            ->get();

        $doctors_data = $doctors->map(function ($doc) use ($selectedDate) {
            $isUnavailable = $doc->unavailabilities->where('date', $selectedDate)->count() > 0;
            $appointmentsCount = $doc->appointments->count();

            return [
                'id' => $doc->id,
                'name' => $doc->name,
                'specialty' => $doc->specialization ?? 'General Medicine',
                'status' => $isUnavailable ? 'Unavailable' : 'Available',
                'appointments_count' => $appointmentsCount,
                'appointments' => $doc->appointments->map(function ($appt) {
                    return [
                        'patient_name' => $appt->patient_name,
                        'status' => $appt->status,
                        'time' => $appt->appointment_time,
                    ];
                }),
            ];
        });

        $totalToday = $queue->count();
        $pending = $queue->whereIn('status', ['pending', 'booked'])->count();
        $checkedIn = $queue->where('status', 'checked-in')->count();
        $called = $queue->where('status', 'called')->count();
        $walkIns = $queue->where('is_walk_in', true)->count();
        $noShows = $queue->where('status', 'no-show')->count();

        $existingPatients = User::where('role', 'patient')->get();
        $services = Service::where('status', 'on')
            ->orderBy('name')
            ->get();

        return view('clerk.dashboard', compact(
            'queue', 'upcoming', 'history', 'doctors_data', 'selectedDate',
            'totalToday', 'pending', 'checkedIn', 'called', 'walkIns', 'checkInDueAppointments',
            'noShows', 'existingPatients', 'services'
        ));
    }

    // -------------------------------
    // UPDATE APPOINTMENT STATUS
    // -------------------------------
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $appointment = Appointment::findOrFail($id);
        $requestedStatus = strtolower(str_replace('_', '-', $request->status));

        $progressStatuses = ['called', 'in-progress', 'in_progress', 'in-session', 'waiting', 'pending', 'scheduled', 'booked'];
        if (in_array($requestedStatus, $progressStatuses, true)) {
            $appointmentTime = Carbon::parse($appointment->appointment_time)->format('H:i:s');
            $hasUnfinishedEarlierAppointment = Appointment::where('doctor_id', $appointment->doctor_id)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->where(function ($query) use ($appointment, $appointmentTime) {
                    $query->whereTime('appointment_time', '<', $appointmentTime)
                        ->orWhere(function ($sameTime) use ($appointment, $appointmentTime) {
                            $sameTime->whereTime('appointment_time', '=', $appointmentTime)
                                ->where('queue_number', '<', $appointment->queue_number);
                        });
                })
                ->get(['status'])
                ->contains(function ($earlierAppointment) {
                    $status = strtolower(str_replace('_', '-', $earlierAppointment->status ?: 'pending'));

                    return !in_array($status, ['completed', 'cancelled', 'no-show', 'noshow'], true);
                });

            if ($hasUnfinishedEarlierAppointment) {
                return redirect()->back()->with('error', 'This appointment is waiting for an earlier appointment time to be completed, cancelled, or marked as no-show.');
            }
        }

        if ($requestedStatus === 'called') {
            $nextEligibleAppointment = Appointment::where('doctor_id', $appointment->doctor_id)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->where(function ($query) {
                    $query->whereIn('status', ['pending', 'booked', 'checked-in'])
                        ->orWhereNull('status')
                        ->orWhere('status', '');
                })
                ->orderBy('appointment_time', 'asc')
                ->orderBy('queue_number', 'asc')
                ->first();

            if ($nextEligibleAppointment && $nextEligibleAppointment->id !== $appointment->id) {
                return redirect()->back()->with('error', 'Only the next patient in queue can start.');
            }
        }

        $appointment->status = $request->status;
        $appointment->save();

        // Send notification to the user if applicable
        $targetUser = $appointment->user 
            ?? ($appointment->patient ? $appointment->patient->user : null);

        if ($targetUser) {
            $targetUser->notify(
                new AppointmentStatusNotification("Your appointment status has been updated to: {$request->status}")
            );
        }

        return redirect()->back()->with('success', 'Appointment status updated successfully.');
    }

    // -------------------------------
    // CANCELLATION METHODS
    // -------------------------------

    public function cancelView(Request $request)
    {
        $selectedDate = $request->query('date', Carbon::today('Asia/Manila')->toDateString());
        
        $appointments = Appointment::whereDate('appointment_date', $selectedDate)
            ->whereIn('status', ['pending', 'booked'])
            ->get();

        return view('clerk.cancel-appointments', compact('appointments', 'selectedDate'));
    }

    public function cancelAppointments(Request $request)
    {
        $request->validate([
            'appointment_ids' => 'required|array',
            'appointment_ids.*' => 'exists:appointments,id',
            'reason' => 'required|string|max:255',
        ]);

        Appointment::whereIn('id', $request->appointment_ids)
            ->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->reason,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Selected appointments have been cancelled successfully.');
    }
}