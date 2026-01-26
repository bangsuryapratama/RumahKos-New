<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('tenant.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Dapatkan role 'penghuni'
        $penghuniRole = Role::where('name', 'penghuni')->first();
        
        if (!$penghuniRole) {
            // Jika role belum ada, buat dulu
            $penghuniRole = Role::create(['name' => 'penghuni']);
        }

        // Buat user baru dengan role penghuni
        $user = User::create([
            'role_id' => $penghuniRole->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Buat profile untuk user
        UserProfile::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
        ]);

        // Login otomatis setelah register
        Auth::guard('tenant')->login($user);

        return redirect()->route('tenant.dashboard');
    }
}


