<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next)
    {
        // Cek jika user login dan statusnya BUKAN active
        if (Auth::check() && Auth::user()->status !== 'active') {
            return redirect()->route('matchdays.pending');
        }

        return $next($request);
    }
}
