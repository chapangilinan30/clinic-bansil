<?php

namespace App\Http\Controllers\Clerk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DoctorSchedule;
use App\Models\DoctorUnavailability; 

class DoctorScheduleController extends Controller
{
    public function index()
{
    $doctors = User::where('role', 'doctor')->get();

    return view('clerk.schedules.index', compact('doctors'));
}

    public function edit(User $doctor)
{
    $unavailabilities = DoctorUnavailability::where(
        'doctor_id',
        $doctor->id
    )->orderBy('date')->get();

    return view(
        'clerk.schedules.edit',
        compact('doctor', 'unavailabilities')
    );
}

    public function store(Request $request)
{
    $doctorId = $request->doctor_id;

    // Remove previous weekly schedule
    DoctorSchedule::where('doctor_id', $doctorId)
        ->whereNull('date')
        ->delete();

    if ($request->has('days')) {

        foreach ($request->days as $day => $schedule) {

            DoctorSchedule::create([
                'doctor_id' => $doctorId,
                'day' => $day,
                'date' => null,
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
            ]);

        }

    }

    return redirect()
        ->route('clerk.doctor-schedules.index')
        ->with('success', 'Schedule saved successfully.');
}
public function storeUnavailability(Request $request)
{
    DoctorUnavailability::create([
        'doctor_id' => $request->doctor_id,
        'date' => $request->date,
    ]);

    return back()->with('success', 'Unavailable date added.');
}

public function destroyUnavailability($id)
{
    DoctorUnavailability::findOrFail($id)->delete();

    return back()->with('success', 'Unavailable date removed.');
}
}