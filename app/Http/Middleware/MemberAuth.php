<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MemberAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Admin session cannot access member area.
        if (Auth::guard('web')->check()) {
            return response()->view('anggota.access-denied');
        }

        // Member session can access member area.
        if (Session::has('anggota_id')) {
            return $next($request);
        }

        // Not authenticated as member.
        return redirect()->route('login');
    }
}
