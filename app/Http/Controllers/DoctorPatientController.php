<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class DoctorPatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::where('doctor_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->get();

        return view('doctor.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('doctor.patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'birth_date'     => 'required|date',
            'gender'         => 'required|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'medical_history'=> 'nullable|string',
        ]);

        Patient::create([
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'birth_date'      => $request->birth_date,
            'gender'          => $request->gender,
            'contact_number'  => $request->contact_number,
            'address'         => $request->address,
            'medical_history' => $request->medical_history,
            'doctor_id'       => auth()->id(),
        ]);

        return redirect()->route('doctor.patients.index')
            ->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient)
    {
        $this->authorizePatient($patient);

        $patient->load([
            'prescriptions' => function ($query) {
                $query->where('doctor_id', auth()->id())->latest();
            },
            'prescriptions.items.medicine'
        ]);

        return view('doctor.patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        abort(403);
    }

    public function update(Request $request, Patient $patient)
    {
        abort(403);
    }

    public function destroy(Patient $patient)
    {
        abort(403);
    }

    private function authorizePatient(Patient $patient)
    {
        if ($patient->doctor_id !== auth()->id()) {
            abort(403);
        }
    }
}