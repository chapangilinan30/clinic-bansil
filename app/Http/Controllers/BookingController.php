<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use App\Models\DoctorUnavailability;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AppointmentBooked;

class BookingController extends Controller
{
    /* ===============================
        STEP 1 – SELECT DEPARTMENT
    =============================== */
    public function stepDepartment()
    {
        $user = Auth::user();

        if ($user && $this->hasActiveAppointment($user)) {
            return redirect()
                ->route('patient.dashboard')
                ->with('error', 'You already have an active appointment. Please wait until it is completed before booking again.');
        }

        $departments = Service::where('status', 'on')->get();
        return view('patient.booking.step1', compact('departments'));
    }

    /* ===============================
        STEP 2 – SELECT DOCTOR
    =============================== */
    public function stepDoctor(Request $request)
    {
        if ($request->department_id) {
            session(['booking.department_id' => $request->department_id]);
        }

        $departmentId = session('booking.department_id');

        if (!$departmentId) {
            return redirect()->route('patient.booking.step1');
        }

        $service = Service::findOrFail($departmentId);

        // Prevent selecting disabled services
        if ($service->status !== 'on') {
            return redirect()
                ->route('patient.booking.step1')
                ->with('error', 'This service is currently unavailable.');
        }

        $doctors = User::where('role', 'doctor')
            ->whereRaw('LOWER(specialization) = ?', [strtolower($service->name)])
            ->get();
        return view('patient.booking.step2', compact('doctors'));
    }

    /* ===============================
        STEP 3 – SELECT DATE & TIME
    =============================== */
    public function stepSchedule(Request $request)
    {
        $doctorId = $request->input('doctor_id', session('booking.doctor_id'));

        if ($doctorId) {
            session(['booking.doctor_id' => $doctorId]);
        }

        if (!$doctorId) {
            return redirect()->route('patient.booking.step2');
        }

        return view('patient.booking.step3', compact('doctorId'));
    }

    /* ===============================
        STEP 4 – PATIENT INFORMATION
    =============================== */
    public function stepPatientInfo(Request $request)
    {
        // Save selected schedule
        if ($request->filled('date') && $request->filled('time')) {
            session([
                'booking.date' => $request->date,
                'booking.time' => $request->time,
            ]);
        }

        $booking = session('booking', []);

        // Make sure previous steps are complete
        if (
            empty($booking['doctor_id']) ||
            empty($booking['department_id']) ||
            empty($booking['date']) ||
            empty($booking['time'])
        ) {
            return redirect()->route('patient.booking.step3');
        }

        // Load doctor information
        $selectedDoctor = User::find($booking['doctor_id']);

        return view('patient.booking.step4', [
            'selectedDoctor'     => $selectedDoctor,
            'selectedDepartment' => Service::find($booking['department_id'])->name,
            'selected_date'      => $booking['date'],
            'selected_time'      => $booking['time'],
        ]);
    }

    /* ===============================
        STEP 5 – CONFIRMATION PAGE
    =============================== */
    public function confirm(Request $request)
    {
        // Save info to session (No payment_method)
        session([
            'booking.patient_name'  => $request->patient_name,
            'booking.patient_email' => $request->patient_email,
            'booking.patient_phone' => $request->patient_phone,
            'booking.notes'         => $request->notes,
        ]);

        $booking = session('booking');
        $selectedDoctor = User::findOrFail($booking['doctor_id']);

        return view('patient.booking.confirm', [
            'selectedDoctor' => $selectedDoctor,
            'selectedDepartment' => Service::find($booking['department_id'])->name,
            'selected_date' => $booking['date'],
            'selected_time' => $booking['time'],
            'patient_name'  => $booking['patient_name'],
            'patient_email' => $booking['patient_email'],
            'patient_phone' => $booking['patient_phone'],
            'notes'         => $booking['notes'],
        ]);
    }

    /* ===============================
        FINAL – SAVE APPOINTMENT (UPDATED)
    =============================== */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user && $this->hasActiveAppointment($user)) {
            return redirect()
                ->route('patient.dashboard')
                ->with('error', 'You already have an active appointment. Please wait until it is completed before booking again.');
        }

        $booking = session('booking');

        if (!$booking) {
            $booking = [
                'doctor_id' => $request->doctor_id,
                'department' => $request->department,
                'date' => $request->selected_date,
                'time' => $request->selected_time,
                'patient_name' => $request->patient_name,
                'patient_email' => $request->patient_email,
                'patient_phone' => $request->patient_phone,
                'notes' => $request->notes,
            ];
        }

        $doctor = User::findOrFail($booking['doctor_id']);
        $patient = Patient::where('user_id', $user->id)->first();

        if (! $patient) {
            return redirect()
                ->route('patient.dashboard')
                ->with('error', 'Your patient profile is missing. Please contact the clinic before booking an appointment.');
        }

        $queueNumber = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $booking['date'])
            ->max('queue_number');

        $queueNumber = $queueNumber ? $queueNumber + 1 : 1;

        Appointment::create([
            'user_id'          => $user->id,
            'patient_id'       => $patient->id,
            'doctor_id'        => $doctor->id,
            'doctor_name'      => $doctor->name,
            'department'       => $doctor->specialization,
            'appointment_date' => $booking['date'],
            'appointment_time' => $booking['time'],
            'queue_number'     => $queueNumber,
            'patient_name'     => $booking['patient_name'],
            'patient_email'    => $booking['patient_email'] ?? '',
            'patient_phone'    => $booking['patient_phone'],
            'purpose'          => 'Consultation',
            'notes'            => $booking['notes'] ?? '',
            'status'           => 'pending',
            'is_walk_in'       => false,
        ]);

        session()->forget('booking');

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'Appointment booked successfully!');
    }

    private function hasActiveAppointment(User $user): bool
    {
        return Appointment::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'booked', 'confirmed', 'checked-in', 'called', 'in_progress', 'in-progress', 'in-session'])
            ->exists();
    }

    /* ===============================
        AJAX – GET DOCTORS
    =============================== */
    public function getDoctors($departmentId)
    {
        $service = Service::findOrFail($departmentId);

        return User::where('role', 'doctor')
            ->whereRaw('LOWER(specialization) = ?', [strtolower($service->name)])
            ->get();
    }

    /* ===============================
        AJAX – GET AVAILABLE DATES
    =============================== */
    public function getDates($doctorId)
    {
        $today = Carbon::today();
        $dates = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays($i);
            $dayName = $date->format('l');

            $hasSchedule = DoctorSchedule::where('doctor_id', $doctorId)
                ->where(function ($q) use ($date, $dayName) {
                    $q->where('date', $date->toDateString())
                      ->orWhere(function ($q2) use ($dayName) {
                          $q2->whereNull('date')->where('day', $dayName);
                      });
                })
                ->exists();

            $isUnavailable = DoctorUnavailability::where('doctor_id', $doctorId)
                ->where('date', $date->toDateString())
                ->exists();

            if ($hasSchedule && !$isUnavailable) {
                $dates[] = $date->toDateString();
            }
        }

        return response()->json($dates);
    }

    /* ===============================
        AJAX – GET TIMES
    =============================== */
    /* ===============================
    AJAX – GET TIMES
=============================== */
/* ===============================
    AJAX – GET TIMES
=============================== */
/* ===============================
    AJAX – GET TIMES
=============================== */
/* ===============================
    AJAX – GET TIMES
=============================== */
public function getTimes($doctorId, $date)
{
    if (
        DoctorUnavailability::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->exists()
    ) {
        return response()->json([]);
    }

    $dayName = Carbon::parse($date)->format('l');

    $schedule = DoctorSchedule::where('doctor_id', $doctorId)
        ->where('date', $date)
        ->first()
        ?? DoctorSchedule::where('doctor_id', $doctorId)
            ->whereNull('date')
            ->where('day', $dayName)
            ->first();

    if (!$schedule) {
        return response()->json([]);
    }

    // Force explicit timezone comparison (Asia/Manila)
    $timezone = 'Asia/Manila';
    $now = Carbon::now($timezone);
    $isToday = $now->toDateString() === $date;

    $start = Carbon::parse($date . ' ' . $schedule->start_time, $timezone);
    $end   = Carbon::parse($date . ' ' . $schedule->end_time, $timezone);

    $times = [];

    while ($start < $end) {

        // Skip any slot that is less than or equal to current time today
        if ($isToday && $start->lessThanOrEqualTo($now)) {
            $start->addMinutes($schedule->slot_duration ?? 30);
            continue;
        }

        $time = $start->format('H:i');

        // Check if there is an active (non-cancelled) appointment for this slot
        $exists = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if (!$exists) {
            $times[] = $time;
        }

        $start->addMinutes($schedule->slot_duration ?? 30);
    }

    return response()->json($times);
}
}