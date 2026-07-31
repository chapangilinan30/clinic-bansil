<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\AdminSwitchLog; // Added import for AdminSwitchLog
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', '7days');

        if ($range === 'month') {
            $start = Carbon::now()->subMonth()->startOfDay();
        } else {
            $start = Carbon::now()->subDays(7)->startOfDay();
        }

        $end = Carbon::now()->endOfDay();

        $accountsCreated = User::whereBetween('created_at', [$start, $end])->count();
        $activeAccounts = User::count();
        $serviceUpdates = Service::whereBetween('updated_at', [$start, $end])->count();

        // Fetch recent admin mode switches within the selected timeframe
        $adminSwitches = AdminSwitchLog::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        return view('reports.index', [
            'accountsCreated' => $accountsCreated,
            'activeAccounts'  => $activeAccounts,
            'serviceUpdates'  => $serviceUpdates,
            'adminSwitches'   => $adminSwitches, // Passed to Blade
            'range'           => $range,

            // Chart Data
            'chartLabels' => [
                'Accounts Created',
                'Total Users',
                'Service Updates'
            ],

            'chartValues' => [
                $accountsCreated,
                $activeAccounts,
                $serviceUpdates
            ]
        ]);
    }
}