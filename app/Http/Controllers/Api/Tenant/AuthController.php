<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register tenant baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'phone.required' => 'Nomor telepon harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Dapatkan role 'penghuni'
            $penghuniRole = Role::firstOrCreate(['name' => 'penghuni']);

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

            // Load relasi
            $user->load(['profile', 'role']);

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->profile->phone ?? null,
                        'avatar' => $user->avatar,
                        'role' => $user->role->name ?? null,
                        'created_at' => $user->created_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login tenant
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Cari user berdasarkan email
            $user = User::with(['profile', 'role'])->where('email', $request->email)->first();

            // Validasi email dan password
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 401);
            }

            // Cek apakah user adalah penghuni
            if (!$user->isPenghuni()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun ini bukan akun penghuni. Silakan gunakan aplikasi admin.'
                ], 403);
            }

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->profile->phone ?? null,
                        'avatar' => $user->avatar,
                        'role' => $user->role->name ?? null,
                        'created_at' => $user->created_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Social Login (Google / Facebook)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:google,facebook',
            'provider_id' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'avatar' => 'nullable|string',
        ], [
            'provider.required' => 'Provider harus diisi',
            'provider.in' => 'Provider harus google atau facebook',
            'provider_id.required' => 'Provider ID harus diisi',
            'email.required' => 'Email harus diisi',
            'name.required' => 'Nama harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Cari user berdasarkan email atau provider_id
            $user = User::where('email', $request->email)
                ->orWhere(function($query) use ($request) {
                    $query->where('provider', $request->provider)
                          ->where('provider_id', $request->provider_id);
                })
                ->first();

            $isNewUser = false;

            // Jika user belum ada, buat baru
            if (!$user) {
                $penghuniRole = Role::firstOrCreate(['name' => 'penghuni']);

                $user = User::create([
                    'role_id' => $penghuniRole->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'provider' => $request->provider,
                    'provider_id' => $request->provider_id,
                    'avatar' => $request->avatar,
                    'password' => Hash::make(Str::random(24)),
                ]);

                // Buat profile
                UserProfile::create([
                    'user_id' => $user->id,
                ]);

                $isNewUser = true;
            } else {
                // Update provider info jika login dengan provider baru
                if (!$user->provider || $user->provider !== $request->provider) {
                    $user->update([
                        'provider' => $request->provider,
                        'provider_id' => $request->provider_id,
                        'avatar' => $request->avatar ?? $user->avatar,
                    ]);
                }
            }

            // Cek apakah user adalah penghuni
            if (!$user->isPenghuni()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun ini bukan akun penghuni.'
                ], 403);
            }

            // Load relasi
            $user->load(['profile', 'role']);

            // Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->profile->phone ?? null,
                        'avatar' => $user->avatar,
                        'provider' => $user->provider,
                        'role' => $user->role->name ?? null,
                        'created_at' => $user->created_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'is_new_user' => $isNewUser,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login dengan ' . $request->provider,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout tenant (revoke token)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revoke current token
            $request->user('sanctum')->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get profile user yang sedang login
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user('sanctum');
            $user->load(['profile', 'role']);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->profile->phone ?? null,
                        'avatar' => $user->avatar,
                        'provider' => $user->provider,
                        'role' => $user->role->name ?? null,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update profile user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user('sanctum');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Update phone di profile
            if ($request->filled('phone')) {
                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['phone' => $request->phone]
                );
            }

            $user->load(['profile', 'role']);

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diupdate',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->profile->phone ?? null,
                        'avatar' => $user->avatar,
                        'role' => $user->role->name ?? null,
                        'updated_at' => $user->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refresh token
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        try {
            $user = $request->user('sanctum');
            
            // Revoke current token
            $request->user('sanctum')->currentAccessToken()->delete();
            
            // Generate new token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token berhasil di-refresh',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat refresh token',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}