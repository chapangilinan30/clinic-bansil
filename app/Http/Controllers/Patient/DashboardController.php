<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show patient dashboard with appointments + queue system
     */
    /**
     * Show patient dashboard with appointments + queue system
     */
    public function index()
    {
        $userId = Auth::id();
        $user = Auth::user();

        // 1. Get the patient's active (upcoming or ongoing) appointment
        $activeAppointment = Appointment::with('doctor')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed', 'checked-in', 'called', 'in_progress'])
            ->latest()
            ->first();

        $hasActiveAppointment = $activeAppointment ? true : false;

        // ============================
        // UPCOMING APPOINTMENTS
        // ============================
        $upcoming = Appointment::with('doctor')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'checked-in', 'called', 'in_progress'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        // ============================
        // HISTORY
        // ============================
        $history = Appointment::where('user_id', $userId)
            ->whereIn('status', ['completed', 'cancelled', 'no-show'])
            ->with(['doctor', 'prescription.items.medicine'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        // ============================
        // QUEUE SYSTEM
        // ============================
        $queueData = null;
        $queue = collect(); // Initialize empty collection by default

        if ($activeAppointment) {
            // Fetch all queue items for the same doctor and date so the view can properly iterate/filter them
            $queue = Appointment::with(['doctor', 'user'])
                ->where('doctor_id', $activeAppointment->doctor_id)
                ->where('appointment_date', $activeAppointment->appointment_date)
                ->orderBy('queue_number', 'asc')
                ->get();

            $nowServing = $queue->first(fn($i) => in_array(strtolower($i->status), ['in_progress', 'called', 'serving']));

            $aheadCount = $queue->filter(function($i) use ($activeAppointment) {
                return $i->queue_number < ($activeAppointment->queue_number ?? 0) 
                    && in_array(strtolower($i->status), ['pending', 'checked-in']);
            })->count();

            $queueData = [
                'now_serving_number' => $nowServing->queue_number ?? $activeAppointment->queue_number ?? '--',
                'now_serving_name'   => $nowServing->user->name ?? $nowServing->patient_name ?? '--',
                'your_ticket'        => $activeAppointment->queue_number ?? '--',
                'ahead'              => $aheadCount,
                'status'             => $activeAppointment->status,
            ];
        } else {
            $queueData = [
                'now_serving_number' => '--',
                'now_serving_name'   => '--',
                'your_ticket'        => '--',
                'ahead'              => 0,
                'status'             => 'No Active Queue',
            ];
        }

        return view('patient.dashboard', compact(
            'activeAppointment',
            'hasActiveAppointment',
            'upcoming',
            'history',
            'queueData',
            'queue' // <--- Pass the $queue collection to the view
        ));
    }

    /**
     * Show independent patient history view
     */
    public function history()
    {
        $history = Appointment::where('user_id', auth()->id())
            ->whereIn('status', ['completed', 'cancelled', 'no-show'])
            ->with(['doctor', 'prescription.items.medicine']) 
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patient.history', compact('history'));
    }

    /**
     * Show independent patient appointments view
     */
    public function appointments()
    {
        $appointments = Appointment::with('doctor')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'checked-in', 'called', 'in_progress'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('patient.appointments', compact('appointments'));
    }

    /**
     * Handles patient-side individual appointment cancellation & strike verification
     */
    public function cancelAppointment(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:500'
        ]);

        $userId = auth()->id();
        $user = auth()->user();

        $appointment = Appointment::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (in_array($appointment->status, ['completed', 'cancelled', 'no-show'])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->cancel_reason
        ]);

        $strikeQuery = Appointment::where('user_id', $userId)
            ->whereIn('status', ['cancelled', 'no-show']);

        if ($user->reactivation_status === 'approved') {
            $strikeQuery->where('updated_at', '>', $user->updated_at);
        }

        $strikeCount = $strikeQuery->count();

        if ($strikeCount >= 3) {
            User::where('id', $userId)->update([
                'is_locked_from_booking' => true,
                'reactivation_status' => 'none'
            ]);

            return back()->with('error', 'Appointment cancelled. You have reached 3 strikes (cancelled/missed sessions). Your account booking access has been suspended.');
        }

        return back()->with('success', 'Appointment cancelled successfully. Current Strike Count: ' . $strikeCount . '/3.');
    }

    /**
     * Process a locked-out patient's appeal statement submission
     */
    public function submitReactivation(Request $request)
    {
        $request->validate([
            'reactivation_reason' => 'required|string|max:1000',
        ], [
            'reactivation_reason.required' => 'Please provide an explanatory reason for your booking reactivation request.',
        ]);

        $userId = auth()->id();

        User::where('id', $userId)->update([
            'is_locked_from_booking' => true,
            'reactivation_reason'    => $request->reactivation_reason,
            'reactivation_status'    => 'pending',
        ]);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Your account reactivation request has been submitted successfully and is currently under administrative review.');
    }
}