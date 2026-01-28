<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
     public function redirect($provider)
    {

    if (Auth::guard('tenant')->check()) {
        Auth::guard('tenant')->logout();
    }

    return Socialite::driver($provider)->redirect();
    }

   public function callback($provider)
  {
    try {
        $socialUser = Socialite::driver($provider)
            ->user();

        $user = User::where('email', $socialUser->email)
            ->orWhere(function ($query) use ($provider, $socialUser) {
                $query->where('provider', $provider)
                      ->where('provider_id', $socialUser->id);
            })
            ->first();

        if ($user) {
            if (!$user->provider) {
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->id,
                    'avatar' => $socialUser->avatar,
                ]);
            }
        } else {
            $penghuniRole = Role::firstOrCreate(['name' => 'penghuni']);

            $user = User::create([
                'role_id' => $penghuniRole->id,
                'name' => $socialUser->name,
                'email' => $socialUser->email,
                'provider' => $provider,
                'provider_id' => $socialUser->id,
                'avatar' => $socialUser->avatar,
                'password' => Hash::make(Str::random(24)),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
            ]);
        }

        if (!$user->isPenghuni()) {
            return redirect()->route('tenant.login')
                ->with('error', 'Akun ini bukan akun penghuni.');
        }

        Auth::guard('tenant')->login($user, true);

        return redirect()->route('tenant.dashboard');

    } catch (\Exception $e) {
        return redirect()->route('tenant.login')
            ->with('error', 'Login gagal. Silakan coba lagi.');
    }
}

}
