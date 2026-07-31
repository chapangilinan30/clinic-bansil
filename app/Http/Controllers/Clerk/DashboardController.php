<?php

namespace App\Http\Controllers\Clerk;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\DoctorUnavailability;
use Illuminate\Http\Request;
use App\Notifications\AppointmentStatusNotification;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index(Request $request)
{
    $doctorId = $request->query('doctor_id');
    $selectedDate = $request->query('schedule_date', Carbon::today('Asia/Manila')->toDateString());

    // 1. Fetch Queue (appointments for selected date, including walk-ins)
    $queueQuery = Appointment::with(['doctor', 'user'])
        ->whereDate('appointment_date', $selectedDate)
        ->where(function($q) {
            $q->whereIn('status', ['pending', 'booked', 'checked-in', 'called', 'in-progress', 'in-session'])
              ->orWhereNull('status')
              ->orWhere('status', '')
              ->orWhere('is_walk_in', true);
        });

    if ($doctorId) {
        $queueQuery->where('doctor_id', $doctorId);
    }

    $queue = $queueQuery->orderBy('queue_number', 'asc')->get();
    $upcoming = $queue->first() ? collect([$queue->first()]) : collect();

    // 2. Sidebar doctors
    $doctors = User::whereIn('role', ['doctor', 'midwife', 'Pediatrician'])
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

    // 3. Appointment history (past appointments)
    $history = Appointment::with(['doctor', 'user'])
        ->whereDate('appointment_date', '<', $selectedDate)
        ->orderBy('appointment_date', 'desc')
        ->get();

    // 4. Stats
    $totalToday = $queue->count();
    $pending = $queue->whereIn('status', ['pending', 'booked'])->count();
    $checkedIn = $queue->where('status', 'checked-in')->count();
    $called = $queue->where('status', 'called')->count();

    return view('clerk.dashboard', [
        'queue' => $queue,
        'upcoming' => $upcoming,
        'history' => $history,
        'doctors_data' => $doctors_data,
        'selectedDate' => $selectedDate,
        'totalToday' => $totalToday,
        'pending' => $pending,
        'checkedIn' => $checkedIn,
        'called' => $called,
    ]);
}

    public function toggleAvailability(Request $request, $id)
    {
        $date = $request->input('date', date('Y-m-d'));
        $exists = DoctorUnavailability::where('doctor_id', $id)->where('date', $date)->first();

        if ($exists) {
            $exists->delete();
            $msg = "Doctor is now marked as Available.";
        } else {
            DoctorUnavailability::create(['doctor_id' => $id, 'date' => $date]);
            $msg = "Doctor is now marked as Unavailable.";
        }
        return redirect()->back()->with('success', $msg);
    }
public function complete($id)
{
    $appointment = Appointment::findOrFail($id);
    $appointment->update(['status' => 'completed']);

    // Notify patient if needed
    if ($appointment->patient) {
        $appointment->patient->notify(new AppointmentStatusNotification($appointment));
    }

    return redirect()->back()->with('success', 'Appointment marked as completed.');
}
 public function updateStatus(Request $request, $id)
{
    $status = $request->input('status');
    $appointment = Appointment::findOrFail($id);
    $appointment->update(['status' => $status]);

    // Use the correct relationship
    if ($appointment->patient) {
        $appointment->patient->notify(new AppointmentStatusNotification($appointment));
    }

    return redirect()->back()->with('success', 'Patient status updated to ' . ucwords(str_replace('-', ' ', $status)));
}

    /**
     * Updated Walk-in logic for Regular/New Patient and Reason for Visit
     */
    public function walkin(Request $request) 
{
    $request->validate([
        'patient_name' => 'required|string|max:255',
        'patient_email' => 'nullable|email|max:255',
        'patient_phone' => 'nullable|string|max:20',
        'doctor_id' => 'required|exists:users,id',
        'reason_for_visit' => 'nullable|string|max:255',
    ]);

    $nowInManila = Carbon::now('Asia/Manila');
    $today = $nowInManila->toDateString();

    $doctor = User::findOrFail($request->doctor_id);

    // Calculate next queue number for today & this doctor
    $nextQueueNumber = Appointment::whereDate('appointment_date', $today)
        ->where('doctor_id', $doctor->id)
        ->count() + 1;

    // Create appointment
    Appointment::create([
        'user_id'          => null,                       // optional if walk-in is not registered as user
        'patient_name'     => $request->patient_name,     // ✅ stays patient_name
        'patient_email'    => $request->patient_email,
        'patient_phone'    => $request->patient_phone,
        'doctor_id'        => $doctor->id,
        'doctor_name'      => $doctor->name,
        'department'       => $doctor->department ?? 'General',
        'status'           => 'pending',
        'is_walk_in'       => true,
        'queue_number'     => $nextQueueNumber,
        'appointment_date' => $today,
        'appointment_time' => $nowInManila->format('H:i'),
        'purpose'          => $request->reason_for_visit,
    ]);

    return redirect()->route('clerk.dashboard')
                     ->with('success', 'Walk-in registered! Patient is #' . $nextQueueNumber);
}
}