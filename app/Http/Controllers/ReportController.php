<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range','7days');

        if($range == 'month'){
            $start = Carbon::now()->subMonth();
        }else{
            $start = Carbon::now()->subDays(7);
        }

        $end = Carbon::now();

        $accountsCreated = User::whereBetween('created_at',[$start,$end])->count();

        $activeAccounts = User::where('status','active')->count();

        $serviceUpdates = Service::whereBetween('updated_at',[$start,$end])->count();

        return view('reports.index',compact(
            'accountsCreated',
            'activeAccounts',
            'serviceUpdates',
            'range'
        ));
    }
}