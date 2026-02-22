<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->require_password_change) {
            // Allow access only to the profile/change password routes and logout
            $allowedRoutes = ['password.change', 'password.change.update', 'logout'];
            if (!$request->routeIs($allowedRoutes)) {
                return redirect()->route('password.change')
                    ->with('warning', 'Please update your password before continuing.');
            }
        }

        return $next($request);
    }
}
