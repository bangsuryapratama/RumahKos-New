<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        // Coba login dengan guard tenant
        if (Auth::guard('tenant')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            
            // Cek apakah user yang login adalah penghuni
            /** @var User $user */
            $user = Auth::guard('tenant')->user();
            
            if (!$user->isPenghuni()) {
                Auth::guard('tenant')->logout();
                
                throw ValidationException::withMessages([
                    'email' => 'Akun ini bukan akun penghuni. Silakan gunakan halaman login admin.',
                ]);
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('tenant.dashboard'));
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