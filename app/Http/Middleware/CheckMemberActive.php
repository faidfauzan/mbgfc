<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMemberActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Cek jika user punya relasi member dan statusnya Nonaktif
        if ($user && $user->member && ($user->member->status === 'Nonaktif' || !$user->member->status_aktif)) {
            return redirect()->route('member.disabled');
        }

        return $next($request);
    }
}
