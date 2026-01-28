<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\Facility;
use App\Models\Review;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        // Kode existing tetap sama...
        $rooms = Room::with(['property', 'facilities', 'reviews'])
            ->orderBy('floor')
            ->orderBy('name')
            ->get();

        $availableRooms = $rooms->where('status', 'available')->count();
        $totalRooms = $rooms->count();
        $occupiedRooms = $rooms->where('status', 'occupied')->count();

        $availableRoomsCollection = $rooms->where('status', 'available');
        $minPrice = $availableRoomsCollection->min('price') ?? 0;
        $maxPrice = $availableRoomsCollection->max('price') ?? 0;

        $allFacilities = Facility::whereHas('rooms')->orderBy('name')->get();
        $FacilityAll = Facility::all();

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

        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);

        $address = Property::select('address')->first();
        view()->share('address', $address);

        $mapsEmbed = Property::select('maps_embed')->first();
        view()->share('mapsEmbed', $mapsEmbed);

        return view('landing.index', compact(
            'rooms',
            'availableRooms',
            'totalRooms',
            'occupiedRooms',
            'minPrice',
            'maxPrice',
            'allFacilities',
            'properties',
            'FacilityAll',
            'propertyLocation'
        ));
    }

    public function roomDetail(Request $request, Room $room)
    {
        // Load relasi yang dibutuhkan
        $room->load(['property', 'facilities', 'reviews.user']);

        // ========== FILTER & SORT REVIEWS (PHP-BASED) ==========

        // Get all reviews (unfiltered) untuk perhitungan rating
        $allReviews = $room->reviews;

        // Query untuk filtered reviews
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

        // Get filtered reviews
        $filteredReviews = $reviewsQuery->get();

        // ========== HITUNG RATING (dari ALL reviews, bukan filtered) ==========

        $reviews = $allReviews; // Untuk backward compatibility
        $averageRating = round($allReviews->avg('rating') ?? 0, 1);
        $totalReviews = $allReviews->count();

        // Distribusi rating (persentase untuk setiap bintang)
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $allReviews->where('rating', $i)->count();
            $ratingDistribution[$i] = $totalReviews > 0
                ? round(($count / $totalReviews) * 100, 1)
                : 0;
        }

        // ========== CATEGORY RATINGS (BARU!) ==========

        $categoryRatings = [
            'cleanliness' => 0,
            'facilities' => 0,
            'service' => 0,
            'location' => 0,
            'price' => 0
        ];

        if ($totalReviews > 0) {
            $categoryTotals = [
                'cleanliness' => 0,
                'facilities' => 0,
                'service' => 0,
                'location' => 0,
                'price' => 0
            ];

            $categoryCounts = [
                'cleanliness' => 0,
                'facilities' => 0,
                'service' => 0,
                'location' => 0,
                'price' => 0
            ];

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

            // Calculate average for each category
            foreach ($categoryRatings as $category => $value) {
                if ($categoryCounts[$category] > 0) {
                    $categoryRatings[$category] = round($categoryTotals[$category] / $categoryCounts[$category], 1);
                }
            }
        }

        // ========== CEK REVIEW PERMISSION (EXISTING LOGIC) ==========

        $canReview = false;
        $reviewMessage = '';

        // Hanya cek guard tenant karena ini fitur untuk tenant/penghuni
        if (Auth::guard('tenant')->check()) {
            $user = Auth::guard('tenant')->user();

            // Cek apakah pernah/sedang ngontrak kamar ini
            $hasResident = Resident::where('user_id', $user->id)
                ->where('room_id', $room->id)
                ->whereIn('status', ['active', 'completed', 'moved_out'])
                ->exists();

            // Cek apakah sudah pernah review
            $hasReviewed = Review::where('room_id', $room->id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$hasResident) {
                $reviewMessage = 'Anda harus menyewa kamar ini terlebih dahulu untuk memberikan ulasan.';
            } elseif ($hasReviewed) {
                $reviewMessage = 'Anda sudah memberikan ulasan untuk kamar ini.';
            } else {
                $canReview = true;
            }
        } else {
            $reviewMessage = 'Silakan login terlebih dahulu untuk memberikan ulasan.';
        }

        // Ambil kamar lainnya dari property yang sama
        $similarRooms = Room::where('property_id', $room->property_id)
            ->where('id', '!=', $room->id)
            ->where('status', 'available')
            ->with(['facilities'])
            ->take(3)
            ->get();

        // Kebutuhan Footer
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);

        $address = Property::select('address')->first();
        view()->share('address', $address);

        $mapsEmbed = Property::select('maps_embed')->first();
        view()->share('mapsEmbed', $mapsEmbed);

        return view('landing.room-detail', compact(
            'room',
            'reviews',
            'filteredReviews',      // BARU: untuk ditampilkan di view
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'categoryRatings',      // BARU: rating per kategori
            'similarRooms',
            'canReview',
            'reviewMessage',
            'contact',
            'address',
            'mapsEmbed'
        ));
    }

    // ========== STORE REVIEW (BARU!) ==========

    public function storeReview(Request $request, $roomId)
    {
        // Validate request
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

        // Check authentication
        if (!Auth::guard('tenant')->check()) {
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu');
        }

        $user = Auth::guard('tenant')->user();
        $room = Room::findOrFail($roomId);

        // Check if user has resident record (pernah/sedang ngontrak)
        $hasResident = Resident::where('user_id', $user->id)
            ->where('room_id', $roomId)
            ->whereIn('status', ['active', 'completed', 'moved_out'])
            ->exists();

        if (!$hasResident) {
            return redirect()->back()->with('error', 'Anda harus menyewa kamar ini terlebih dahulu');
        }

        // Check if already reviewed
        $existingReview = Review::where('room_id', $roomId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk kamar ini');
        }

        // Filter out zero values from category ratings
        $categoryRatings = [];
        if (isset($validated['category_ratings'])) {
            foreach ($validated['category_ratings'] as $category => $rating) {
                if ($rating > 0) {
                    $categoryRatings[$category] = (float) $rating;
                }
            }
        }

        // Create review
        Review::create([
            'room_id' => $roomId,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'category_ratings' => !empty($categoryRatings) ? json_encode($categoryRatings) : null,
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan Anda berhasil ditambahkan');
    }
}
