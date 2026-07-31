<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check kung naka-login ang user
        if (!auth()->check()) {
            return redirect()->route('login'); // Mas magandang redirect sa login kaysa abort
        }

        $user = auth()->user();

        // 2. ADMIN SUPERPOWER CHECK:
        // Kung ang user ay may is_admin = true, pasok agad!
        if ($user->is_admin) {
            return $next($request);
        }

        // 3. ROLE CHECK:
        // Kung hindi admin, titingnan natin kung match yung role nila sa required roles
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}