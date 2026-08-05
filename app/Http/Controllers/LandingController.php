<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\Facility;
use App\Models\Review;
use App\Models\Resident;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['property', 'facilities', 'reviews'])
            ->orderBy('floor')
            ->orderByRaw("
                CAST(
                    REGEXP_REPLACE(name, '[^0-9]', '')
                AS UNSIGNED
            )
            ")
            ->get();

        $availableRooms = $rooms->where('status', 'available')->count();
        $totalRooms = $rooms->count();
        $occupiedRooms = $rooms->where('status', 'occupied')->count();

        $availableRoomsCollection = $rooms->where('status', 'available');
        $minPrice = $availableRoomsCollection->min('price') ?? 0;
        $maxPrice = $availableRoomsCollection->max('price') ?? 0;

        $allFacilities = Facility::whereHas('rooms')->orderBy('name')->get();
        $FacilityAll = $allFacilities;
        $properties = Property::withCount(['rooms' => function($query) {
            $query->where('status', 'available');
        }])->get();

        $property = Property::first();
        if ($property && !empty($property->address)) {
            $parts = array_map('trim', explode(',', $property->address));
            $propertyLocation = end($parts) ?: $parts[0];
        } else {
            $propertyLocation = 'Bandung';
        }

        return view('landing.index', compact(
            'rooms',
            'availableRooms',
            'totalRooms',
            'occupiedRooms',
            'minPrice',
            'maxPrice',
            'allFacilities',
            'FacilityAll',
            'properties',
            'propertyLocation',
        ));
    }

    public function roomDetail(Request $request, Room $room)
    {
        // Load relationships
        $room->load(['property', 'facilities', 'reviews.user']);

        // Get all reviews for rating calculation
        $allReviews = $room->reviews;

        // Query for filtered reviews
        $reviewsQuery = Review::where('room_id', $room->id)->with('user');

        // Filter by rating
        $filterRating = $request->get('filter_rating', 'all');
        if ($filterRating !== 'all' && in_array($filterRating, ['1', '2', '3', '4', '5'])) {
            $reviewsQuery->where('rating', $filterRating);
        }

        // Sort reviews
        $sortBy = $request->get('sort_by', 'newest');
        switch($sortBy) {
            case 'oldest':
                $reviewsQuery->orderBy('created_at', 'asc');
                break;
            case 'highest':
                $reviewsQuery->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'lowest':
                $reviewsQuery->orderBy('rating', 'asc')->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $reviewsQuery->orderBy('created_at', 'desc');
                break;
        }

        $filteredReviews = $reviewsQuery->get();
        $reviews = $allReviews;
        $averageRating = round($allReviews->avg('rating') ?? 4.9, 1);
        $totalReviews = $allReviews->count();

        // Rating distribution percentage
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $allReviews->where('rating', $i)->count();
            $ratingDistribution[$i] = $totalReviews > 0
                ? round(($count / $totalReviews) * 100, 1)
                : 0;
        }

        // Category ratings
        $categoryRatings = [
            'cleanliness' => 4.9,
            'facilities' => 4.8,
            'service' => 4.9,
            'location' => 5.0,
            'price' => 4.8
        ];

        if ($totalReviews > 0) {
            $categoryTotals = ['cleanliness' => 0, 'facilities' => 0, 'service' => 0, 'location' => 0, 'price' => 0];
            $categoryCounts = ['cleanliness' => 0, 'facilities' => 0, 'service' => 0, 'location' => 0, 'price' => 0];

            foreach ($allReviews as $review) {
                if ($review->category_ratings) {
                    $ratings = is_string($review->category_ratings)
                        ? json_decode($review->category_ratings, true)
                        : $review->category_ratings;

                    if (is_array($ratings)) {
                        foreach ($ratings as $category => $rating) {
                            if (isset($categoryTotals[$category]) && $rating > 0) {
                                $categoryTotals[$category] += $rating;
                                $categoryCounts[$category]++;
                            }
                        }
                    }
                }
            }

            foreach ($categoryRatings as $category => $value) {
                if ($categoryCounts[$category] > 0) {
                    $categoryRatings[$category] = round($categoryTotals[$category] / $categoryCounts[$category], 1);
                }
            }
        }

        // Check review permission for logged in user
        $canReview = false;
        $reviewMessage = '';
        $user = Auth::user() ?? Auth::guard('tenant')->user();

        if ($user) {
            $hasResident = Resident::where('user_id', $user->id)
                ->where('room_id', $room->id)
                ->whereIn('status', ['active', 'completed', 'moved_out'])
                ->exists();

            $hasReviewed = Review::where('room_id', $room->id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$hasResident) {
                $reviewMessage = 'Hanya tamu yang pernah/sedang menyewa kamar ini yang dapat memberikan ulasan terverifikasi.';
            } elseif ($hasReviewed) {
                $reviewMessage = 'Anda sudah memberikan ulasan untuk kamar ini.';
            } else {
                $canReview = true;
            }
        } else {
            $reviewMessage = 'Silakan masuk ke akun Anda terlebih dahulu untuk memberikan ulasan.';
        }

        // Similar rooms
        $similarRooms = Room::where('id', '!=', $room->id)
            ->where('status', 'available')
            ->with(['facilities'])
            ->take(3)
            ->get();

        return view('landing.room-detail', compact(
            'room',
            'reviews',
            'filteredReviews',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'categoryRatings',
            'similarRooms',
            'canReview',
            'reviewMessage',
        ));
    }

    public function storeReview(Request $request, $roomId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'category_ratings' => 'nullable|array',
            'category_ratings.cleanliness' => 'nullable|numeric|min:0|max:5',
            'category_ratings.facilities' => 'nullable|numeric|min:0|max:5',
            'category_ratings.service' => 'nullable|numeric|min:0|max:5',
            'category_ratings.location' => 'nullable|numeric|min:0|max:5',
            'category_ratings.price' => 'nullable|numeric|min:0|max:5',
        ]);

        $user = Auth::user() ?? Auth::guard('tenant')->user();

        if (!$user) {
            return redirect()->back()->with('error', 'Anda harus masuk terlebih dahulu.');
        }

        $room = Room::findOrFail($roomId);

        $hasResident = Resident::where('user_id', $user->id)
            ->where('room_id', $roomId)
            ->whereIn('status', ['active', 'completed', 'moved_out'])
            ->exists();

        if (!$hasResident && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Hanya penyewa yang dapat memberikan ulasan.');
        }

        $existingReview = Review::where('room_id', $roomId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk kamar ini.');
        }

        $categoryRatings = [];
        if (isset($validated['category_ratings'])) {
            foreach ($validated['category_ratings'] as $category => $rating) {
                if ($rating > 0) {
                    $categoryRatings[$category] = (float) $rating;
                }
            }
        }

        Review::create([
            'room_id' => $roomId,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'category_ratings' => !empty($categoryRatings) ? json_encode($categoryRatings) : null,
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan bintang Anda berhasil dipublikasikan.');
    }
}
