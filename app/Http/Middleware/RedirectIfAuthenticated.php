<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * If user already logged in, redirect to home (or admin dashboard if admin).
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            // jika ingin direct ke admin dashboard untuk admin:
            $user = Auth::user();
            if ($user && ($user->is_admin ?? false)) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }

        return $next($request);
    }
}
