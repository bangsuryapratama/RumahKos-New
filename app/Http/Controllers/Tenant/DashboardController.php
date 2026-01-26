<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        $resident = $user->resident; // Data kos aktif
        
        return view('tenant.dashboard', compact('user', 'resident'));
    }

    public function profile()
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        return view('tenant.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'identity_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'occupation' => 'nullable|string|max:100',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
        ]);

        // Update profile
        $user->profile->update([
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'identity_number' => $validated['identity_number'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function payments()
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        $resident = $user->resident;
        
        if (!$resident) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'Anda belum terdaftar sebagai penghuni kos.');
        }
        
        $payments = $resident->payments()->latest()->get();
        
        return view('tenant.payments', compact('user', 'resident', 'payments'));
    }
}