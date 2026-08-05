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
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            Auth::guard('tenant')->login($user, $request->boolean('remember'));

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isPenghuni()) {
                $isSuspended = $user->residents()
                    ->where('status', 'suspended')
                    ->exists();

                if ($isSuspended) {
                    return redirect()->route('tenant.suspended');
                }

                return redirect()->intended(route('tenant.dashboard'));
            }

            return redirect()->intended(route('landing'));
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    public function suspended()
    {
        $user = Auth::user() ?? Auth::guard('tenant')->user();

        if (!$user || !$user->residents()->where('status', 'suspended')->exists()) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.auth.suspended');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}