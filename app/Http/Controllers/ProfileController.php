<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
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
        $user = $request->user();

        // Update user basic info
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Update or create profile
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

        // Handle document deletions
        $profile = $user->profile;
        if ($profile) {
            $deleteFlags = [
                'delete_ktp' => 'ktp_photo',
                'delete_passport' => 'passport_photo',
                'delete_sim' => 'sim_photo',
                'delete_other' => 'other_document',
            ];

            foreach ($deleteFlags as $flag => $field) {
                if ($request->$flag) {
                    $profile->deleteOldDocument($field);
                    $profileData[$field] = null;
                }
            }

            // Handle document uploads
            $documents = ['ktp_photo', 'passport_photo', 'sim_photo', 'other_document'];
            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    // Delete old file
                    $profile->deleteOldDocument($doc);
                    // Store new file
                    $profileData[$doc] = $request->file( doc)->store('documents/profiles', 'public');
                }
            }

            $profile->update($profileData);
        } else {
            // Create new profile
            $documents = ['ktp_photo', 'passport_photo', 'sim_photo', 'other_document'];
            foreach ($documents as $doc) {
                if ($request->hasFile($doc)) {
                    $profileData[$doc] = $request->file($doc)->store('documents/profiles', 'public');
                }
            }

            $profileData['user_id'] = $user->id;
            \App\Models\UserProfile::create($profileData);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
            $documents = ['ktp_photo', 'passport_photo', 'sim_photo', 'other_document'];
            foreach ($documents as $doc) {
                $user->profile->deleteOldDocument($doc);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
