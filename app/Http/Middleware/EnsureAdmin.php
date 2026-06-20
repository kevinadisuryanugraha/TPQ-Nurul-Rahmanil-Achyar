<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/login');
        }

        $admin = Auth::guard('admin')->user();
        if ($admin->role !== 'admin' && $admin->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Anda bukan Admin.');
        }

        return $next($request);
    }
}
