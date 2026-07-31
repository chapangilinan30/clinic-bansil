<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorUnavailability;

class DoctorUnavailabilityController extends Controller
{
    /* ===============================
       SHOW UNAVAILABILITY LIST
    =============================== */
    public function index()
    {
        $unavailabilities = DoctorUnavailability::where('doctor_id', auth()->id())
            ->orderBy('date')
            ->get();

        return view('doctor.unavailability.index', compact('unavailabilities'));
    }

    /* ===============================
       STORE UNAVAILABILITY
    =============================== */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:doctor_unavailabilities,date,NULL,id,doctor_id,' . auth()->id(),
        ]);

        DoctorUnavailability::create([
            'doctor_id' => auth()->id(),
            'date'      => $request->date,
        ]);

        return back()->with('success', 'Unavailability added.');
    }

    /* ===============================
       DELETE UNAVAILABILITY
    =============================== */
    public function destroy($id)
    {
        DoctorUnavailability::where('id', $id)
            ->where('doctor_id', auth()->id())
            ->delete();

        return back()->with('success', 'Unavailability removed.');
    }
}