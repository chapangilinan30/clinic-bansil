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

        $queue = $queueQuery->orderBy('queue_number', 'asc')->get();

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

                // Check-in window opens 20 minutes before the appointment time
                $checkInStart = $appointmentDateTime->copy()->subMinutes(20);
                
                // The absolute deadline to check-in is the exact appointment time (e.g., 5:30 PM)
                $appointmentTime = $appointmentDateTime;

                // If current time has passed the appointment time and they haven't checked in:
                if ($now->greaterThan($appointmentTime)) {
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
                            new \App\Notifications\AppointmentStatusNotification($appointment, 'Your appointment has been marked as No-Show due to missed check-in time.')
                        );
                    }
                }
            }
            return $appointment;
        });

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
            'totalToday', 'pending', 'checkedIn', 'called', 'walkIns',
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
        $appointment->status = $request->status;
        $appointment->save();

        // Send notification to the user if applicable
        $targetUser = $appointment->user 
            ?? ($appointment->patient ? $appointment->patient->user : null);

        if ($targetUser) {
            $targetUser->notify(
                new AppointmentStatusNotification($appointment, "Your appointment status has been updated to: {$request->status}")
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