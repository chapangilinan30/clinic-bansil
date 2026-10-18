<?php

namespace App\Http\Middleware;

use App\Services\BookingStrikeService;
use Closure;
use Illuminate\Http\Request;

class RefreshExpiredBookingLockout
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->is_locked_from_booking) {
            app(BookingStrikeService::class)->unlockIfExpired($user);
        }

        return $next($request);
    }
}