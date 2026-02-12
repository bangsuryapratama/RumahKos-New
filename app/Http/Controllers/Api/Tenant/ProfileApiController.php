<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileApiController extends Controller
{
    /**
     * Get user profile
     */
    public function getProfile()
    {
        try {
            $user = Auth::user();
            $user->load('profile');

            $profileData = null;
            $completionPercent = 0;

            if ($user->profile) {
                $fields = ['phone', 'identity_number', 'address', 'date_of_birth', 'gender', 'occupation', 'emergency_contact', 'ktp_photo'];
                $filled = 0;
                foreach ($fields as $field) {
                    if ($user->profile->$field) $filled++;
                }
                $completionPercent = round(($filled / count($fields)) * 100);

                $profileData = [
                    'phone' => $user->profile->phone,
                    'identity_number' => $user->profile->identity_number,
                    'address' => $user->profile->address,
                    'date_of_birth' => $user->profile->date_of_birth?->format('Y-m-d'),
                    'gender' => $user->profile->gender,
                    'occupation' => $user->profile->occupation,
                    'emergency_contact' => $user->profile->emergency_contact,
                    'emergency_contact_name' => $user->profile->emergency_contact_name,
                    'ktp_photo' => $user->profile->ktp_photo
                        ? asset('storage/' . $user->profile->ktp_photo)
                        : null,
                    'sim_photo' => $user->profile->sim_photo
                        ? asset('storage/' . $user->profile->sim_photo)
                        : null,
                    'passport_photo' => $user->profile->passport_photo
                        ? asset('storage/' . $user->profile->passport_photo)
                        : null,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diambil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                        'role' => $user->role->name,
                        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    ],
                    'profile' => $profileData,
                    'completion_percent' => $completionPercent,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'identity_number' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date',
                'occupation' => 'nullable|string|max:100',
                'emergency_contact' => 'nullable|string|max:20',
                'emergency_contact_name' => 'nullable|string|max:100',
                'gender' => 'nullable|in:male,female',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update user name if provided
            if ($request->has('name')) {
                $user->update(['name' => $request->name]);
            }

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
                $profileData['user_id'] = $user->id;
                $profile = $user->profile()->create($profileData);
            } else {
                $profile->update($profileData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'profile' => [
                        'phone' => $profile->phone,
                        'identity_number' => $profile->identity_number,
                        'address' => $profile->address,
                        'date_of_birth' => $profile->date_of_birth?->format('Y-m-d'),
                        'gender' => $profile->gender,
                        'occupation' => $profile->occupation,
                        'emergency_contact' => $profile->emergency_contact,
                        'emergency_contact_name' => $profile->emergency_contact_name,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload document (KTP, SIM, Passport)
     */
    public function uploadDocument(Request $request)
    {
        try {
            $user = Auth::user();

            // Validation
            $validator = Validator::make($request->all(), [
                'document_type' => 'required|in:ktp_photo,sim_photo,passport_photo',
                'document' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $profile = $user->profile;
            if (!$profile) {
                $profile = $user->profile()->create(['user_id' => $user->id]);
            }

            $documentType = $request->document_type;
            $file = $request->file('document');

            // Delete old file if exists
            if ($profile->$documentType) {
                Storage::disk('public')->delete($profile->$documentType);
            }

            // Store new file
            $path = $file->store('documents/profiles', 'public');
            $profile->update([$documentType => $path]);

            Log::info("Document uploaded: $documentType for user {$user->id}");

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload',
                'data' => [
                    'document_type' => $documentType,
                    'url' => asset('storage/' . $path),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Document upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload dokumen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'document_type' => 'required|in:ktp_photo,sim_photo,passport_photo',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $profile = $user->profile;
            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile tidak ditemukan'
                ], 404);
            }

            $documentType = $request->document_type;

            if ($profile->$documentType) {
                Storage::disk('public')->delete($profile->$documentType);
                $profile->update([$documentType => null]);

                Log::info("Document deleted: $documentType for user {$user->id}");

                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil dihapus',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
