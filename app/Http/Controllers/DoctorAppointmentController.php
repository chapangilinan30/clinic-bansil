<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorAppointmentController extends Controller
{
    /**
     * Show consultation page (NOW with medicines)
     */
    public function show(Appointment $appointment)
    {
        // Security: only assigned doctor
        if ((int) $appointment->doctor_id !== auth()->id()) {
            abort(403, 'You are not authorized to view this consultation.');
        }

        // ✅ IMPORTANT: load medicines for prescription form
        $medicines = Medicine::all();

        return view('doctor.appointments.show', compact('appointment', 'medicines'));
    }

    /**
     * (OPTIONAL / BACKUP)
     * Old consultation update (not used in new flow but kept safe)
     */
    public function update(Request $request, Appointment $appointment)
    {
        if ((int) $appointment->doctor_id !== auth()->id()) {
            abort(403, 'You are not authorized to update this consultation.');
        }

        $request->validate([
            'diagnosis' => 'required|string|max:1000',
        ]);

        // Update appointment
        $appointment->update([
            'diagnosis' => $request->diagnosis,
            'status' => 'completed',
        ]);

        // =========================
        // CREATE / UPDATE PATIENT
        // =========================
        $names = explode(' ', $appointment->patient_name, 2);
        $firstName = $names[0] ?? 'Unknown';
        $lastName = $names[1] ?? '';

        $birthDate = $appointment->birth_date
            ? Carbon::parse($appointment->birth_date)->format('Y-m-d')
            : now()->toDateString();

        $gender = $appointment->gender ?? 'unknown';
        $contactNumber = $appointment->patient_phone ?? null;
        $address = $appointment->address ?? '';
        $medicalHistory = $appointment->notes ?? '';

        Patient::updateOrCreate(
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'doctor_id' => $appointment->doctor_id,
            ],
            [
                'birth_date' => $birthDate,
                'gender' => $gender,
                'contact_number' => $contactNumber,
                'address' => $address,
                'medical_history' => $medicalHistory,
            ]
        );

        return redirect()
            ->route('doctor.dashboard')
            ->with('success', 'Consultation completed successfully.');
    }

    /**
     * Dashboard appointments (today)
     */
    public function index()
    {
        $appointments = Appointment::where('doctor_id', auth()->id())
            ->whereDate('appointment_date', today())
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('doctor.dashboard', [
            'todaysAppointments' => $appointments,
            'totalAppointmentsToday' => $appointments->count(),
            'patientsInQueue' => $appointments->whereIn('status', ['pending', 'checked-in', 'in_progress'])->count(),
            'completedToday' => $appointments->where('status', 'completed')->count(),
            'ongoingConsultations' => $appointments->where('status', 'in_progress')->count(),
        ]);
    }
}