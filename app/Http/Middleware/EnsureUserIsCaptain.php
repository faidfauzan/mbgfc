<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCaptain
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    
    public function handle(Request $request, Closure $next): Response
{
    if (!$request->user() || !in_array($request->user()->role, ['captain', 'admin'])) {
        abort(403, 'Akses ditolak. Halaman ini khusus untuk Captain.');
    }

    return $next($request);
}
}
