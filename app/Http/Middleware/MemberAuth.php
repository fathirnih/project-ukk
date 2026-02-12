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
        // Check if admin is logged in - show access denied
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->view('anggota.access-denied');
        }

        // Check if member is logged in (session)
        if (Session::has('anggota_id')) {
            return $next($request);
        }

        // Not authenticated at all - can proceed to login page
        return $next($request);
    }
}
