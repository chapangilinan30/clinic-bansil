<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
     public function index()
    {
        $medicines = Medicine::orderBy('category')
            ->orderBy('name')
            ->get();

        return view('doctor.medicines.index', compact('medicines'));
    }

    // Store new medicine
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name',
            'category' => 'required|in:Cough,Cold and Flu,Fever,Pain Relief,Allergy,Antibiotics,Digestive Health,Other',
        ]);

        Medicine::create([
            'name' => $request->name,
            'category' => $request->category,
        ]);

        return back()->with('success', 'Medicine added successfully.');
    }

    // Delete medicine
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return back()->with('success', 'Medicine removed successfully.');
    }
}