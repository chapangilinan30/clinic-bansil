<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrackActivity
{
    public function handle($request, Closure $next)
    {
        $today = Carbon::today();

        $activity = Activity::firstOrCreate(
            ['activity_date' => $today],
            ['user_count' => 0]
        );

        $activity->increment('user_count');

        return $next($request);
    }
}