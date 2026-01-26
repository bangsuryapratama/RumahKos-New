<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Room $room)
    {
        // Validasi input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Cek apakah user sudah login (tenant atau admin)
        $user = Auth::guard('tenant')->user() ?? Auth::user();

        if (!$user) {
            return redirect()->route('tenant.login')
                ->with('error', 'Silakan login terlebih dahulu untuk memberikan ulasan.');
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