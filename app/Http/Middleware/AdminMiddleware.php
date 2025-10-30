<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check() || ! (auth()->user()->is_admin ?? false)) {
            // 403 — jangan redirect ke login
            abort(403, 'Akses ditolak: Anda bukan admin.');
        }

        return $next($request);
    }
}
