<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Review;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Room $room)
    {
        // Validasi input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000|min:10',
        ], [
            'rating.required' => 'Rating harus dipilih',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'comment.required' => 'Ulasan tidak boleh kosong',
            'comment.min' => 'Ulasan minimal 10 karakter',
            'comment.max' => 'Ulasan maksimal 1000 karakter',
        ]);

        // HANYA CEK GUARD TENANT
        if (!Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.login')
                ->with('error', 'Silakan login terlebih dahulu untuk memberikan ulasan.');
        }

        $user = Auth::guard('tenant')->user();

        // Validasi: Cek apakah user pernah/sedang ngontrak kamar ini
        $hasActiveOrPastResident = Resident::where('user_id', $user->id)
            ->where('room_id', $room->id)
            ->whereIn('status', ['active', 'completed', 'moved_out'])
            ->exists();

        if (!$hasActiveOrPastResident) {
            return redirect()->back()
                ->with('error', 'Anda hanya bisa memberikan ulasan jika sedang atau pernah menyewa kamar ini.');
        }

        // Cek apakah user sudah pernah review kamar ini
        $existingReview = Review::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Anda sudah pernah memberikan ulasan untuk kamar ini.');
        }

        // Buat review baru
        Review::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()
            ->with('success', 'Terima kasih! Ulasan Anda berhasil ditambahkan.');
    }
}
