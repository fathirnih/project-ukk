<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is a member (using session) - show access denied
        if (Session::has('anggota_id')) {
            Session::flush();
            return response()->view('admin.access-denied');
        }

        // Check if admin is authenticated
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        // Not authenticated at all - redirect to admin login
        return redirect()->route('admin.login');
    }
}
