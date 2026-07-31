<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctorId = auth()->id();
        $today = now()->toDateString();

        // Total appointments scheduled for today
        $totalAppointmentsToday = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today)
            ->count();

        // Patients in queue (pending)
        $patientsInQueue = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->count();

        // Completed today (by status updated today)
        $completedToday = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->whereDate('updated_at', $today)
            ->count();

        // Ongoing consultations (in_progress)
        $ongoingConsultations = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'in_progress')
            ->count();

        // Appointments table for today
       $today = now()->format('Y-m-d');

       $todaysAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();

        return view('doctor.dashboard', compact(
            'totalAppointmentsToday',
            'patientsInQueue',
            'completedToday',
            'ongoingConsultations',
            'todaysAppointments'
        ));
    }
}