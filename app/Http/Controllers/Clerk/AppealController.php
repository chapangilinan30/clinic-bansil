<?php

namespace App\Http\Controllers\Clerk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AppealController extends Controller
{
    /**
     * Display a dashboard panel list of all ongoing pending user booking appeals
     */
    public function index()
    {
        // Gather all suspended user rows tagged explicitly with a pending review statement
        $appeals = User::where('is_locked_from_booking', true)
            ->where('reactivation_status', 'pending')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('clerk.appeals.index', compact('appeals'));
    }

    /**
     * Action handler processing approval: reset strikes and fully restore booking permissions
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_locked_from_booking' => false,
            'reactivation_status' => 'approved',
            'reactivation_reason' => null, // Optional clear out cleanups
        ]);

        // IMPORTANT OPTIONAL LOGIC TASK: 
        // If your strike system relies strictly on counting records in the appointments table, 
        // you might want to soft-delete or change the status of their old cancelled/no-show records here 
        // so their next cancel doesn't instantly snap back into a 4-strike lockdown loop.

        return redirect()->route('clerk.appeals.index')
            ->with('success', "Booking permissions successfully restored for user: {$user->name}.");
    }

    /**
     * Action handler processing denial: leaves the account suspended indefinitely
     */
    public function deny($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'reactivation_status' => 'denied'
        ]);

        return redirect()->route('clerk.appeals.index')
            ->with('error', "Reactivation appeal for user: {$user->name} has been formally rejected.");
    }
}