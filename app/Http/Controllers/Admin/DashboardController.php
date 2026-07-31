<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AdminSwitchLog; // Added import for logging
use Illuminate\Support\Facades\Auth; // Added import for authentication
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Record the Admin Switch event
        if (Auth::check()) {
            AdminSwitchLog::create([
                'user_id' => Auth::id(),
                'role'    => Auth::user()->role ?? 'Clerk',
            ]);
        }

        // Statistic cards
        $totalUsers = User::count(); 
        $totalDoctors = User::where('role', 'doctor')->count();
        
        // Count any administrative users (admin, clerk, or staff) as team members
        $totalStaff = User::whereIn('role', ['admin', 'staff', 'clerk'])->count();

        // Recent Activity (latest 5 appointments)
        $activities = Appointment::latest()->take(5)->get();

        // Fetch booking counts grouped by date for the last 7 days
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $rawChartData = Appointment::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total', 'date'); // Creates key-value array ['2026-07-16' => 5]

        // Build continuous 7-day labels & values (filling missing dates with 0)
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $dateString = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = $dateString;
            $data[] = $rawChartData->get($dateString, 0); // Use count or default 0
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalDoctors',
            'totalStaff',
            'activities',   
            'labels',       
            'data'          
        ));
    }
}