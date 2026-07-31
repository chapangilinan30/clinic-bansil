<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;

class ServiceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Service List Page
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->search) {
            $query->where('name','like','%'.$request->search.'%');
        }

        $services = $query->latest()->get();

        // ⭐ Doctor list normalization (IMPORTANT)
        $doctors = User::where('role','doctor')
            ->whereNotNull('specialization')
            ->get()
            ->map(function($doctor){
                $doctor->specialization = strtolower(trim($doctor->specialization));
                return $doctor;
            });

        return view('admin.services.index', compact('services','doctors'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Service
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:on,off'
        ]);

        Service::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()
            ->route('admin.services.index')  // ✅ Fixed route name
            ->with('success','Service added successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Update Service
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:on,off'
        ]);

        $service = Service::findOrFail($id);

        $service->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()
            ->route('admin.services.index')  // ✅ Fixed route name
            ->with('success','Service updated successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Service
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        Service::findOrFail($id)->delete();

        return redirect()
            ->route('admin.services.index')  // ✅ Fixed route name
            ->with('success','Service deleted successfully!');
    }
}