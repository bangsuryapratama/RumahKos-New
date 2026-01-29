<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\Resident;
use App\Services\ReviewAutoScoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review with half-star support
     */
    public function store(Request $request, Room $room)
    {
        try {
            $validated = $request->validate([
                'rating'  => 'required|numeric|min:0.5|max:5',
                'comment' => 'required|string|min:10|max:1000',
            ], [
                'rating.required'  => 'Rating harus dipilih',
                'rating.numeric'   => 'Rating tidak valid',
                'rating.min'       => 'Rating minimal 0.5 bintang',
                'rating.max'       => 'Rating maksimal 5 bintang',
                'comment.required' => 'Ulasan tidak boleh kosong',
                'comment.min'      => 'Ulasan minimal 10 karakter',
                'comment.max'      => 'Ulasan maksimal 1000 karakter',
            ]);

            if (!Auth::guard('tenant')->check()) {
                return back()->with('error', 'Silakan login terlebih dahulu untuk memberikan ulasan.');
            }

            $user = Auth::guard('tenant')->user();

            // Check if user has rented this room
            $hasResident = Resident::where('user_id', $user->id)
                ->where('room_id', $room->id)
                ->whereIn('status', ['active', 'completed', 'moved_out'])
                ->exists();

            if (!$hasResident) {
                return back()->with('error', 'Anda hanya bisa memberikan ulasan jika pernah menyewa kamar ini.');
            }

            // Check duplicate review
            $existingReview = Review::where('room_id', $room->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($existingReview) {
                return back()->with('error', 'Anda sudah pernah memberikan ulasan untuk kamar ini.');
            }

            // Auto-generate category scores
            $categoryScores = ReviewAutoScoring::generate(
                (float) $validated['rating'],
                $validated['comment']
            );

            // Create review
            Review::create([
                'room_id'          => $room->id,
                'user_id'          => $user->id,
                'rating'           => $validated['rating'],
                'comment'          => $validated['comment'],
                'category_ratings' => $categoryScores,
            ]);

            return back()->with('success', 'Terima kasih! Ulasan Anda berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit(Review $review)
    {
        // Check if user owns this review
        if (!Auth::guard('tenant')->check() || Auth::guard('tenant')->id() !== $review->user_id) {
            return back()->with('error', 'Anda tidak bisa mengedit ulasan ini.');
        }

        return back()->with('editReview', $review);
    }

    /**
     * Update existing review
     */
    public function update(Request $request, Review $review)
    {
        try {
            // Check ownership
            if (!Auth::guard('tenant')->check() || Auth::guard('tenant')->id() !== $review->user_id) {
                return back()->with('error', 'Anda tidak bisa mengedit ulasan ini.');
            }

            $validated = $request->validate([
                'rating'  => 'required|numeric|min:0.5|max:5',
                'comment' => 'required|string|min:10|max:1000',
            ], [
                'rating.required'  => 'Rating harus dipilih',
                'comment.required' => 'Ulasan tidak boleh kosong',
                'comment.min'      => 'Ulasan minimal 10 karakter',
                'comment.max'      => 'Ulasan maksimal 1000 karakter',
            ]);

            // Re-generate category scores with new comment
            $categoryScores = ReviewAutoScoring::generate(
                (float) $validated['rating'],
                $validated['comment']
            );

            // Update review
            $review->update([
                'rating'           => $validated['rating'],
                'comment'          => $validated['comment'],
                'category_ratings' => $categoryScores,
            ]);

            return back()->with('success', 'Ulasan Anda berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete review
     */
    public function destroy(Review $review)
    {
        try {
            // Check ownership
            if (!Auth::guard('tenant')->check() || Auth::guard('tenant')->id() !== $review->user_id) {
                return back()->with('error', 'Anda tidak bisa menghapus ulasan ini.');
            }

            $review->delete();

            return back()->with('success', 'Ulasan Anda berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Reply to review
     */
    public function reply(Request $request, Review $review)
    {
        try {
            // Check if user is admin
            if (!Auth::check()) {
                return back()->with('error', 'Silakan login sebagai admin.');
            }

            $validated = $request->validate([
                'reply' => 'required|string|min:10|max:500',
            ], [
                'reply.required' => 'Balasan tidak boleh kosong',
                'reply.min'      => 'Balasan minimal 10 karakter',
                'reply.max'      => 'Balasan maksimal 500 karakter',
            ]);

            // Create or update reply
            ReviewReply::updateOrCreate(
                ['review_id' => $review->id],
                [
                    'user_id' => Auth::id(),
                    'reply'   => $validated['reply'],
                ]
            );

            return back()->with('success', 'Balasan berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Delete reply
     */
    public function deleteReply(ReviewReply $reply)
    {
        try {
            // Check if user is admin
            if (!Auth::check()) {
                return back()->with('error', 'Unauthorized');
            }

            $reply->delete();

            return back()->with('success', 'Balasan berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
