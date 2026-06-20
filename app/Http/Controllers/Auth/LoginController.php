<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        // If already logged in, redirect to respective dashboard
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user()->isSuperadmin()
                ? redirect()->intended('/superadmin/dashboard')
                : redirect()->intended('/admin/dashboard');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->intended('/murid/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = $credentials['login'];
        $password = $credentials['password'];
        $remember = $request->has('remember');

        // Check if input is email (Admin / Superadmin)
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $admin = Admin::where('email', $loginInput)->first();

            if (!$admin) {
                return back()->withErrors([
                    'login' => 'Username atau password salah. Silakan coba lagi.',
                ])->withInput($request->only('login', 'remember'));
            }

            if (!$admin->is_active) {
                return back()->withErrors([
                    'login' => 'Akun Anda tidak aktif. Hubungi pengurus.',
                ])->withInput($request->only('login', 'remember'));
            }

            if (Auth::guard('admin')->attempt(['email' => $loginInput, 'password' => $password], $remember)) {
                $request->session()->regenerate();

                return $admin->isSuperadmin()
                    ? redirect()->intended('/superadmin/dashboard')
                    : redirect()->intended('/admin/dashboard');
            }
        } else {
            // Student (Murid) uses username
            $user = User::where('username', $loginInput)->first();

            if (!$user) {
                return back()->withErrors([
                    'login' => 'Username atau password salah. Silakan coba lagi.',
                ])->withInput($request->only('login', 'remember'));
            }

            if (!$user->is_active) {
                return back()->withErrors([
                    'login' => 'Akun Anda tidak aktif. Hubungi pengurus.',
                ])->withInput($request->only('login', 'remember'));
            }

            if (Auth::guard('web')->attempt(['username' => $loginInput, 'password' => $password], $remember)) {
                $request->session()->regenerate();

                return redirect()->intended('/murid/dashboard');
            }
        }

        return back()->withErrors([
            'login' => 'Username atau password salah. Silakan coba lagi.',
        ])->withInput($request->only('login', 'remember'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah keluar.');
    }
}
