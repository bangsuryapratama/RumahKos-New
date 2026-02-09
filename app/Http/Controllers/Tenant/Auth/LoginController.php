<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('tenant.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // PENTING: Gunakan guard 'tenant'
        if (Auth::guard('tenant')->attempt($request->only('email', 'password'), $request->filled('remember'))) {

            $request->session()->regenerate();
            $user = Auth::guard('tenant')->user();

            // Cek role penghuni
            if ($user->isPenghuni()) {
                return redirect()->intended(route('tenant.dashboard'));
            }

            // Jika bukan penghuni, logout
            Auth::guard('tenant')->logout();
            throw ValidationException::withMessages([
                'email' => 'Akun ini bukan untuk penghuni.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
