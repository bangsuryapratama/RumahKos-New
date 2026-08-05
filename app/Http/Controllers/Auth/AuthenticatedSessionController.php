<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the luxury hotel smart login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request with smart role routing.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        // Also log in to tenant guard if applicable for seamless compatibility
        Auth::guard('tenant')->login($user, $request->boolean('remember'));

        // 1. Admin Role -> Admin CMS Dashboard
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2. Tenant / Penghuni Role
        if ($user->isPenghuni()) {
            $isSuspended = $user->residents()
                ->where('status', 'suspended')
                ->exists();

            if ($isSuspended) {
                return redirect()->route('tenant.suspended');
            }

            return redirect()->intended(route('tenant.dashboard'));
        }

        // 3. Fallback for general guests
        return redirect()->intended(route('landing'));
    }

    /**
     * Destroy an authenticated session across all guards.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('tenant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }
}
