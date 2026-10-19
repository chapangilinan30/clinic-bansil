<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    /**
     * Display a list of currently locked-out patient accounts.
     */
    public function index()
    {
        // Fetch users who are currently locked out from booking
        $blockedUsers = User::where('is_locked_from_booking', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.appeals.index', compact('blockedUsers'));
    }
}