<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Appointment;

class PrescriptionController extends Controller
{
    // Step 1: Select Patient (with search)
    public function create(Request $request)
    {
        $search = $request->search;

        $patients = Patient::where('doctor_id', auth()->id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->orderBy('first_name')
            ->get();

        return view('doctor.prescriptions.create', compact('patients', 'search'));
    }

    // Step 2: Create Prescription for Specific Patient
    public function createForPatient(Request $request, $id)
    {
        $patient = Patient::where('doctor_id', auth()->id())
            ->findOrFail($id);

        $medicines = Medicine::orderBy('name')->get();

        $appointment = null;
        if ($request->has('appointment')) {
            $appointment = Appointment::find($request->appointment);
        }

        return view('doctor.prescriptions.create_patient', compact(
            'patient',
            'medicines',
            'appointment'
        ));
    }

    // Store Prescription (Sidebar)
    public function store(Request $request)
    {
        $request->validate([
            'patient_id'            => 'required|exists:patients,id',
            'appointment_id'        => 'nullable|exists:appointments,id',
            'diagnosis'             => 'required|string|max:1000',
            'next_appointment_date' => 'nullable|date',
            'medicine_id'           => 'required|array|min:1',
            'medicine_id.*'         => 'required|exists:medicines,id',
            'dosage.*'              => 'nullable|string|max:255',
            'frequency.*'           => 'nullable|string|max:255',
            'duration.*'            => 'nullable|string|max:255',
            'instructions.*'        => 'nullable|string|max:500',
        ]);

        $patient = Patient::where('doctor_id', auth()->id())
            ->findOrFail($request->patient_id);

        return $this->savePrescription($request, $patient, $request->appointment_id);
    }

    // Store Prescription from Dashboard
    public function storeDashboard(Request $request)
    {
        $request->validate([
            'appointment_id'        => 'required|exists:appointments,id',
            'diagnosis'             => 'required|string|max:1000',
            'next_appointment_date' => 'nullable|date',
            'medicine_id'           => 'required|array|min:1',
            'medicine_id.*'         => 'required|exists:medicines,id',
            'dosage.*'              => 'nullable|string|max:255',
            'frequency.*'           => 'nullable|string|max:255',
            'duration.*'            => 'nullable|string|max:255',
            'instructions.*'        => 'nullable|string|max:500',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);

        // 🔹 Lookup patient by booking name first
        $nameParts = explode(' ', $appointment->patient_name);
        $first = $nameParts[0] ?? 'Unknown';
        $last = $nameParts[1] ?? '';

        $patient = Patient::where('doctor_id', auth()->id())
            ->where('first_name', $first)
            ->where('last_name', $last)
            ->first();

        // 🔹 If patient not exist, create new record using booking info
        if (!$patient) {
            $patient = Patient::create([
                'doctor_id'       => auth()->id(),
                'user_id'         => $appointment->user_id, // optional link sa parent
                'first_name'      => $first,
                'last_name'       => $last,
                'birth_date'      => now()->subYears(5), // default age kung wala info
                'gender'          => 'N', // default o leave blank
                'contact_number'  => $appointment->patient_phone ?? '',
                'address'         => '',
            ]);
        }

        // 🔹 Link patient_id sa appointment kung wala pa
        if (!$appointment->patient_id) {
            $appointment->patient_id = $patient->id;
            $appointment->save();
        }

        return $this->savePrescription($request, $patient, $appointment->id);
    }

    // Shared method to save prescription + items
    protected function savePrescription(Request $request, Patient $patient, $appointmentId = null)
    {
        DB::beginTransaction();

        try {
            $prescription = Prescription::create([
                'patient_id'            => $patient->id,
                'doctor_id'             => auth()->id(),
                'diagnosis'             => $request->diagnosis,
                'next_appointment_date' => $request->next_appointment_date,
                'appointment_id'        => $appointmentId,
            ]);

            foreach ($request->medicine_id as $index => $medicineId) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id'     => $medicineId,
                    'dosage'          => $request->dosage[$index] ?? null,
                    'frequency'       => $request->frequency[$index] ?? null,
                    'duration'        => $request->duration[$index] ?? null,
                    'instructions'    => $request->instructions[$index] ?? null,
                ]);
            }

            // Auto mark appointment completed
            if ($appointmentId) {
                $appointment = Appointment::find($appointmentId);
                if ($appointment) {
                    $appointment->status = 'completed';
                    $appointment->save();
                }
            }

            DB::commit();

            return redirect()
                ->route('doctor.prescriptions.print', $prescription->id)
                ->with('success', 'Prescription saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to save prescription. ' . $e->getMessage())
                ->withInput();
        }
    }

    // Show prescription
    public function show(Prescription $prescription)
    {
        return $this->print($prescription);
    }

    // Printable view
    public function print(Prescription $prescription)
    {
        if ($prescription->doctor_id !== auth()->id()) {
            abort(403);
        }

        $prescription->load([
            'patient',
            'items.medicine'
        ]);

        return view('doctor.prescriptions.print', compact('prescription'));
    }
}