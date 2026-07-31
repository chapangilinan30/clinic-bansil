<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; 
use Illuminate\Http\Request;

class AppealController extends Controller
{
    /**
     * Display the workspace grid for handling submitted appeals.
     */
    public function index()
    {
        // Fetch users who are locked out from booking and have a pending reactivation status
        $appeals = User::where('is_locked_from_booking', true)
            ->where('reactivation_status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.appeals.index', compact('appeals'));
    }

    /**
     * Approve the specific user appeal and lift account suspension.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        // Direct database update to bypass any potential $fillable restrictions in the User model
        User::where('id', $id)->update([
            'is_locked_from_booking' => false,
            'reactivation_status'    => 'approved',
            'last_reactivated_at'    => now() // <--- This resets their strike timeline to right now
        ]);

        return redirect()->route('admin.appeals.index')
            ->with('success', "Account booking privileges for {$user->name} have been reactivated successfully.");
    }

    /**
     * Reject the specific user appeal request.
     */
    public function deny($id)
    {
        $user = User::findOrFail($id);
        
        // Direct database update to bypass any potential $fillable restrictions in the User model
        User::where('id', $id)->update([
            'is_locked_from_booking' => true,
            'reactivation_status'    => 'denied'
        ]);

        return redirect()->route('admin.appeals.index')
            ->with('error', "Reactivation appeal for {$user->name} was officially denied.");
    }
}