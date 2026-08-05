<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return redirect()->route('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'role_id'  => User::ROLE_PENGHUNI,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'phone'   => $request->phone,
        ]);

        Auth::guard('web')->login($user);
        Auth::guard('tenant')->login($user);

        return redirect()->intended(route('tenant.dashboard'))
            ->with('success', 'Akun berhasil dibuat. Selamat datang di Cemara Residence!');
    }
}
