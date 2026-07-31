<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* ===================================================
       INDEX PAGE
    =================================================== */
    public function index()
    {
        $users = User::latest()->paginate(100);

        return view('admin.users.index', compact('users'));
    }

    /* ===================================================
       STORE USER
    =================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            
            // Auto-verify lahat ng ililikha sa Admin side (Doctors, Clerks, Admins, Staff)
            'email_verified_at' => now(),

            'specialization' => $request->role === 'doctor'
                ? $request->specialization
                : null
        ]);

        return back()->with('success', 'User added successfully');
    }

    /* ===================================================
       EDIT USER (Modal Support JSON Response)
    =================================================== */
    public function edit(User $user)
    {
        return response()->json($user);
    }

    /* ===================================================
       UPDATE USER
    =================================================== */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required'
        ]);

        // Siguraduhin ding manatiling verified ang admin/doctor/clerk/staff kung sakaling ma-edit
        $isStaffOrDoctor = in_array($request->role, ['admin', 'doctor', 'clerk', 'staff']);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            
            // Dito idinagdag ang logic para sa checkbox
            'is_admin' => $request->has('is_admin') ? 1 : 0,

            // Kunin ang lumang email_verified_at O i-set na verified kung staff/doctor account sila
            'email_verified_at' => $isStaffOrDoctor 
                ? ($user->email_verified_at ?? now()) 
                : $user->email_verified_at,

            'specialization' => $request->role === 'doctor'
                ? $request->specialization
                : null
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User Updated Successfully');
    }

    /* ===================================================
       DELETE USER
    =================================================== */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User Deleted Successfully');
    }
}