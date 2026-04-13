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

        if (Auth::guard('tenant')->attempt($request->only('email', 'password'), $request->filled('remember'))) {

            $request->session()->regenerate();
            $user = Auth::guard('tenant')->user();

            if ($user->isPenghuni()) {
                // Cek apakah ada resident yang suspended
                $isSuspended = $user->residents()
                    ->where('status', 'suspended')
                    ->exists();

                if ($isSuspended) {
                    // Tetap login tapi redirect ke halaman suspended
                    return redirect()->route('tenant.suspended');
                }

                return redirect()->intended(route('tenant.dashboard'));
            }

            Auth::guard('tenant')->logout();
            throw ValidationException::withMessages([
                'email' => 'Akun ini bukan untuk penghuni.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function suspended()
    {
        $user = Auth::guard('tenant')->user();

        // Kalau tidak ada yang suspended, kembalikan ke dashboard
        if (!$user || !$user->residents()->where('status', 'suspended')->exists()) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.auth.suspended');
    }

    public function logout(Request $request)
    {
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}