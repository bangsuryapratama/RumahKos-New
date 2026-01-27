<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form forgot password
     */
    public function showLinkRequestForm()
    {
        return view('tenant.auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Cek apakah email ada dan merupakan penghuni
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.'
            ]);
        }

        if (!$user->isPenghuni()) {
            return back()->withErrors([
                'email' => 'Email ini bukan akun penghuni. Gunakan halaman reset password admin.'
            ]);
        }

        // Generate token
        $token = Str::random(64);

        // Simpan ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Kirim email
        try {
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

            return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }
    }
}