<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();

        // Resident aktif/inactive untuk info kos & stats
        $resident = $user->residents()
            ->with([
                'room.property',
                'payments' => function($query) {
                    $query->orderBy('billing_month', 'asc');
                }
            ])
            ->whereIn('status', ['active', 'inactive'])
            ->latest()
            ->first();

        // Semua residents + payments untuk riwayat keuangan
        $allResidents = $user->residents()
            ->with([
                'room',
                'payments' => function($query) {
                    $query->orderBy('billing_month', 'desc');
                }
            ])
            ->latest()
            ->get();

        return view('tenant.dashboard', compact('user', 'resident', 'allResidents'));
    }

    public function profile()
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();
        return view('tenant.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        try {
            /** @var User $user */
            $user = Auth::guard('tenant')->user();

            $validated = $request->validate([
                'name'                   => 'required|string|max:255',
                'phone'                  => 'nullable|number|max:20',
                'address'                => 'nullable|string',
                'identity_number'        => 'nullable|string|max:20',
                'date_of_birth'          => 'nullable|date',
                'occupation'             => 'nullable|string|max:100',
                'emergency_contact'      => 'nullable|string|max:20',
                'emergency_contact_name' => 'nullable|string|max:100',
                'gender'                 => 'nullable|in:male,female',
                'ktp_photo'              => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
                'sim_photo'              => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
                'passport_photo'         => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
                'delete_ktp'             => 'nullable|boolean',
                'delete_sim'             => 'nullable|boolean',
                'delete_passport'        => 'nullable|boolean',
            ]);

            $user->update(['name' => $validated['name']]);

            $profileData = [
                'phone'                  => $validated['phone'] ?? null,
                'address'                => $validated['address'] ?? null,
                'identity_number'        => $validated['identity_number'] ?? null,
                'date_of_birth'          => $validated['date_of_birth'] ?? null,
                'occupation'             => $validated['occupation'] ?? null,
                'emergency_contact'      => $validated['emergency_contact'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'gender'                 => $validated['gender'] ?? null,
            ];

            $profile = $user->profile;

            if (!$profile) {
                $profileData['user_id'] = $user->id;
                $profile = $user->profile()->create($profileData);
            }

            $deleteFlags = [
                'delete_ktp'      => 'ktp_photo',
                'delete_sim'      => 'sim_photo',
                'delete_passport' => 'passport_photo',
            ];

            foreach ($deleteFlags as $flag => $field) {
                if ($request->has($flag) && $request->$flag == '1') {
                    if ($profile->$field) {
                        Storage::disk('public')->delete($profile->$field);
                        $profileData[$field] = null;
                        Log::info("Deleted document: $field for user {$user->id}");
                    }
                }
            }

            $documents = ['ktp_photo', 'sim_photo', 'passport_photo'];

            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    $file = $request->file($doc);
                    if ($file->isValid()) {
                        $deleteFlag = 'delete_' . str_replace('_photo', '', $doc);
                        if ($profile->$doc && !($request->has($deleteFlag) && $request->$deleteFlag == '1')) {
                            Storage::disk('public')->delete($profile->$doc);
                        }
                        $path = $file->store('documents/profiles', 'public');
                        $profileData[$doc] = $path;
                        Log::info("Uploaded: $doc -> $path for user {$user->id}");
                    }
                } else {
                    unset($profileData[$doc]);
                }
            }

            $profile->update($profileData);

            return back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function payments()
    {
        /** @var User $user */
        $user = Auth::guard('tenant')->user();

        $resident = $user->residents()
            ->with([
                'room.property',
                'payments' => function($query) {
                    $query->orderBy('billing_month', 'desc');
                }
            ])
            ->whereIn('status', ['active', 'inactive'])
            ->latest()
            ->first();

        if (!$resident) {
            return redirect()->route('tenant.dashboard')
                ->with('error', 'Anda belum terdaftar sebagai penghuni kos.');
        }

        $payments = $resident->payments;

        return view('tenant.payments', compact('user', 'resident', 'payments'));
    }
}