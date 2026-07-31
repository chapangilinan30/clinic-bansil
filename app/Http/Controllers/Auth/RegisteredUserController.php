<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient; // IMPORTANT: Added this line so the controller knows what a Patient is
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'mi'        => ['nullable', 'string', 'max:2'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'birthdate' => ['required', 'date'],
            'sex'       => ['required', 'string'],
            'contact'   => ['required', 'string', 'max:20'],
            'address'   => ['required', 'string', 'max:500'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Create the User (Only fields that actually exist in the 'users' table)
$user = User::create([
    'name'     => $request->name, // Laravel's default 'users' table uses 'name'
    'email'    => $request->email,
    'password' => Hash::make($request->password),
    'role'     => 'patient', 
    'status'   => 'active',
]);

// 2. Create the Patient (This is where the 'mi' and 'last_name' go!)
Patient::create([
    'user_id'        => $user->id,
    'first_name'     => $request->name,
    'last_name'      => $request->last_name, // Make sure this is 'last_name' in DB
    'mi'             => $request->mi,
    'birth_date'     => $request->birthdate,
    'gender'         => $request->sex,
    'contact_number' => $request->contact,
    'address'        => $request->address,
]);

       // ... (Keep the User and Patient creation logic as it is)

        event(new Registered($user));

        // 1. Remove or comment out the auto-login line:
        // Auth::login($user);

        // 2. Redirect to the login page with a success message
        return redirect()->route('login')->with('status', 'Registration successful! Please log in.');
    }
}