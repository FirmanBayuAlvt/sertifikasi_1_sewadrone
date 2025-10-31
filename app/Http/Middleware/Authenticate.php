<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * Redirect to login if not authenticated (web guard).
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (! Auth::check()) {
            // Untuk permintaan AJAX/JSON kita return 401, untuk request normal redirect ke login
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'));
        }

        return $next($request);
    }
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
