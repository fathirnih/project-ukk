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
        // Member session cannot access admin area.
        if (Session::has('anggota_id')) {
            return response()->view('admin.access-denied');
        }

        // Admin session can access admin area.
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        // Not authenticated as admin.
        return redirect()->route('admin.login');
    }
}
