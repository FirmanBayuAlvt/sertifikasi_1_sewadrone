<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // pastikan ada user dan kolom is_admin di tabel users
        if (! auth()->check() || ! (auth()->user()->is_admin ?? false)) {
            abort(403, 'Akses ditolak: Anda bukan admin.');
        }

        return $next($request);
    }
}
