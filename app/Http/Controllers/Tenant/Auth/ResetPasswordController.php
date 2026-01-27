<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request, $token)
    {
        return view('tenant.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek token di database
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetData) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        // Cek apakah token sudah kadaluarsa (1 jam)
        if (now()->diffInHours($resetData->created_at) > 1) {
            return back()->with('error', 'Token reset password sudah kadaluarsa. Silakan request ulang.');
        }

        // Cari user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token dari database
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Redirect ke login dengan pesan sukses
        return redirect()->route('tenant.login')
            ->with('status', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}