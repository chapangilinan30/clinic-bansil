<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
use App\Models\DoctorUnavailability;
use App\Notifications\AppointmentStatusNotification;

class DoctorScheduleController extends Controller
{
    /* ===============================
        SHOW DOCTOR SCHEDULES
    =============================== */
    public function index()
    {
        $schedules = DoctorSchedule::byDoctor(auth()->id())
            ->orderBy('day')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedules.index', compact('schedules'));
    }

    /* ====================================================================
        UPDATED: CANCEL PANEL SELECTION METHOD (INTERACTIVE CALENDAR VIEW)
    ==================================================================== */
    public function cancelPanel(Request $request)
    {
        $doctorId = auth()->id();
        
        // 1. Kuhanin ang kasalukuyang buwan at taon para sa Calendar setup
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        
        $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $blankDaysBefore = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)

        // 2. Kuhanin ang mga active schedule profiles para markahan ang calendar cells
        $doctorSchedules = DoctorSchedule::where('doctor_id', $doctorId)->get();

        // 3. Saluhin ang pinindot na petsa mula sa interactive calendar interface
        $selectedDate = $request->input('selected_date');
        $affectedAppointments = null; // Ginawang null sa simula para sa clean layout handling
        $chosenSchedule = null;

        if ($selectedDate) {
            $carbonDate = \Carbon\Carbon::parse($selectedDate);
            $dayName = $carbonDate->format('l'); // Halimbawa: 'Monday'

            // Hanapin kung may operational schedule slot si doc sa petsa o araw na ito
            $chosenSchedule = DoctorSchedule::where('doctor_id', $doctorId)
                ->where(function($query) use ($selectedDate, $dayName) {
                    $query->whereDate('date', $selectedDate)
                          ->orWhere('day', $dayName);
                })
                ->first();

            // FIX: Kuhanin ang mga active patient appointments gamit ang multi-status arrays
            $affectedAppointments = \App\Models\Appointment::where('doctor_id', $doctorId)
                ->whereIn('status', ['waiting', 'Waiting', 'pending', 'Pending', 'approved', 'Approved', 'confirmed', 'Confirmed'])
                ->whereDate('appointment_date', $selectedDate)
                ->get();
        }

        return view('doctor.schedules.cancel-management', compact(
            'daysInMonth', 'blankDaysBefore', 'month', 'year', 'startOfMonth',
            'doctorSchedules', 'selectedDate', 'affectedAppointments', 'chosenSchedule'
        ));
    }

    /* ====================================================================
        UPDATED: SHOW CANCELLATION SCREEN (ROW REDIRECT TO CALENDAR VIEW)
    ==================================================================== */
    public function showCancel($id)
    {
        $doctorId = auth()->id();
        $chosenSchedule = DoctorSchedule::where('doctor_id', $doctorId)->findOrFail($id);

        // Tukuyin ang target calendar date profile base sa uri ng schedule row block
        $targetDate = $chosenSchedule->date ?? date('Y-m-d', strtotime("next " . $chosenSchedule->day));

        // Mag-redirect nang may kasamang selected date params patungo sa interactive calendar engine natin
        return redirect()->route('doctor.schedules.cancel-panel', [
            'selected_date' => $targetDate,
            'month'         => date('m', strtotime($targetDate)),
            'year'          => date('Y', strtotime($targetDate))
        ]);
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    /* ===============================
        STORE SCHEDULE (FIXED + SAFE)
    =============================== */
    public function store(Request $request)
    {
        $doctorId = auth()->id();

        /* =====================================================
            MODE 1: WEEKLY BATCH SCHEDULE
        ===================================================== */
        if ($request->has('days')) {

            $request->validate([
                'type' => 'required|in:weekly',
                'days' => 'required|array',
            ]);

            /* ==========================================
                🔥 SMART REPLACE (PREVENT DUPLICATES)
            ========================================== */
            DoctorSchedule::where('doctor_id', $doctorId)
                ->whereNull('date')
                ->delete();

            foreach ($request->days as $day => $data) {

                // skip inactive
                if (!isset($data['active'])) {
                    continue;
                }

                // strict validation
                if (
                    empty($data['start_time']) ||
                    empty($data['end_time'])
                ) {
                    continue;
                }

                // time validation
                if ($data['start_time'] >= $data['end_time']) {
                    return back()->withErrors([
                        'time_error' => "Invalid time range for $day"
                    ]);
                }

                DoctorSchedule::create([
                    'doctor_id'     => $doctorId,
                    'day'           => $day,
                    'date'          => null,
                    'start_time'    => $data['start_time'],
                    'end_time'      => $data['end_time'],
                    'slot_duration' => $data['slot_duration'] ?? 30,
                ]);
            }

            return redirect()
                ->route('doctor.schedules.index')
                ->with('success', 'Weekly schedule updated successfully.');
        }

        /* =====================================================
            MODE 2: SINGLE DATE
        ==================================================== */
        $request->validate([
            'type'       => 'required|in:date',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after:start_time',
        ]);

        $blocked = DoctorUnavailability::where('doctor_id', $doctorId)
            ->where('date', $request->date)
            ->exists();

        if ($blocked) {
            return back()->withErrors([
                'unavailable' => 'Doctor is unavailable on this date.'
            ]);
        }

        $conflict = DoctorSchedule::byDoctor($doctorId)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors([
                'conflict' => 'This schedule overlaps with an existing one.'
            ]);
        }

        DoctorSchedule::create([
            'doctor_id'  => $doctorId,
            'day'        => null,
            'date'       => $request->date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Schedule added successfully.');
    }

    /* =====================================================
        DESTROY / CANCEL TIMELINE SLOT (FIXED)
    ===================================================== */
public function destroy(Request $request, $id) // Added Request $request
{
    $doctorUser = auth()->user();
    $schedule = DoctorSchedule::where('doctor_id', $doctorUser->id)->findOrFail($id);

    // 1. Validate the reason for the batch cancellation
    $request->validate(['reason' => 'required|string|max:1000']);

    // 2. Find appointments
    $appointments = \App\Models\Appointment::where('doctor_id', $doctorUser->id)
        ->whereIn('status', ['waiting', 'Waiting', 'pending', 'Pending', 'approved', 'Approved', 'confirmed', 'Confirmed'])
        ->where(function($query) use ($schedule) {
            if ($schedule->date) {
                $query->whereDate('appointment_date', $schedule->date);
            } else {
                $query->whereRaw("DATE_FORMAT(appointment_date, '%W') = ?", [$schedule->day]);
            }
        })
        ->get();

    // 3. Update with the dynamic reason
    foreach ($appointments as $appointment) {
        $appointment->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->reason, // ⚡ USE THE DYNAMIC REASON
        ]);

        $msg = 'Your appointment on ' . date('M d, Y', strtotime($appointment->appointment_date)) 
             . ' has been cancelled by Dr. ' . $doctorUser->name . '. Reason: ' . $request->reason;

        $notifiableUser = $appointment->user ?? $appointment->patient;
        if ($notifiableUser) {
            $notifiableUser->notify(new AppointmentStatusNotification($msg));
        }
    }

    $schedule->delete();

    return redirect()->route('doctor.schedules.index')
        ->with('success', 'Schedule slot and associated appointments were cancelled successfully.');
}

    /* ===============================
        FUTURE: SLOT GENERATOR
    =============================== */
    public function getAvailableSlots($doctorId, $date)
    {
        return [];
    }

    /* =====================================================
        CANCEL SELECTED CHECKBOX PATIENTS ONLY
    ===================================================== */
  public function cancelSelected(Request $request)
{
    $doctorUser = auth()->user();

    // 1. Validate both the IDs and the reason
    $request->validate([
        'appointment_ids' => 'required|array',
        'appointment_ids.*' => 'exists:appointments,id',
        'reason' => 'required|string|max:1000',
    ], [
        'appointment_ids.required' => 'Please select at least one patient.',
        'reason.required' => 'A cancellation reason is required for the patients to be notified.',
    ]);

    // 2. Process the update
    $appointments = \App\Models\Appointment::where('doctor_id', $doctorUser->id)
        ->whereIn('id', $request->appointment_ids)
        ->get();

    foreach ($appointments as $appointment) {
        // Update record
        $appointment->update([
            'status' => 'cancelled',
            'cancel_reason' => $request->reason, 
        ]);

        // Send notification
        $msg = 'Your appointment on ' . date('M d, Y', strtotime($appointment->appointment_date)) 
             . ' has been cancelled by Dr. ' . $doctorUser->name . '. Reason: ' . $request->reason;

        $notifiableUser = $appointment->user ?? $appointment->patient;
        if ($notifiableUser) {
            $notifiableUser->notify(new \App\Notifications\AppointmentStatusNotification($msg));
        }
    }

    return back()->with('success', count($appointments) . ' selected patient appointments were successfully cancelled.');
}
}