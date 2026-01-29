<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('profile');

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Update user basic info (only name)
            $user->fill($request->only(['name']));

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Prepare profile data
            $profileData = [
                'phone' => $request->phone,
                'address' => $request->address,
                'identity_number' => $request->identity_number,
                'date_of_birth' => $request->date_of_birth,
                'occupation' => $request->occupation,
                'emergency_contact' => $request->emergency_contact,
                'emergency_contact_name' => $request->emergency_contact_name,
                'gender' => $request->gender,
            ];

            // Get or create profile
            $profile = $user->profile;

            if (!$profile) {
                // Create new profile
                $profileData['user_id'] = $user->id;
                $profile = \App\Models\UserProfile::create($profileData);
            }

            // Handle document deletions FIRST
            $deleteFlags = [
                'delete_ktp' => 'ktp_photo',
                'delete_passport' => 'passport_photo',
                'delete_sim' => 'sim_photo',
            ];

            foreach ($deleteFlags as $flag => $field) {
                if ($request->has($flag) && $request->$flag == '1') {
                    if ($profile->$field) {
                        // Delete old file from storage
                        Storage::disk('public')->delete($profile->$field);
                        $profileData[$field] = null;

                        Log::info("Deleted document: $field for user {$user->id}");
                    }
                }
            }

            // Handle document uploads
            $documents = [
                'ktp_photo',
                'passport_photo',
                'sim_photo',
            ];

            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    $file = $request->file($doc);

                    // Validate file is valid
                    if ($file->isValid()) {
                        // Delete old file if exists (and not already deleted)
                        $deleteFlag = 'delete_' . str_replace('_photo', '', $doc);
                        if ($profile->$doc && !($request->has($deleteFlag) && $request->$deleteFlag == '1')) {
                            Storage::disk('public')->delete($profile->$doc);
                            Log::info("Replaced old document: $doc for user {$user->id}");
                        }

                        // Store new file with unique name
                        $path = $file->store('documents/profiles', 'public');
                        $profileData[$doc] = $path;

                        Log::info("Uploaded new document: $doc -> $path for user {$user->id}");
                    } else {
                        Log::error("Invalid file upload for: $doc, user {$user->id}");
                    }
                } else {
                    // Keep existing file if not uploading new one and not deleting
                    unset($profileData[$doc]);
                }
            }

            // Update profile with new data
            $profile->update($profileData);

            // Debug: Check what was saved
            $profile->refresh();
            Log::info("Profile updated for user {$user->id}", [
                'ktp_photo' => $profile->ktp_photo,
                'sim_photo' => $profile->sim_photo,
                'passport_photo' => $profile->passport_photo,
            ]);

            return Redirect::back()->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return Redirect::back()
                ->with('error', 'Terjadi kesalahan saat menyimpan profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile documents before deleting user
        if ($user->profile) {
            $documents = ['ktp_photo', 'passport_photo', 'sim_photo'];
            foreach ($documents as $doc) {
                if ($user->profile->$doc) {
                    Storage::disk('public')->delete($user->profile->$doc);
                }
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
