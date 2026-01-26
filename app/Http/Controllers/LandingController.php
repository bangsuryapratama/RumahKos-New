<?php
namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Property;
use App\Models\Facility;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Get all rooms with relations
        $rooms = Room::with(['property', 'facilities'])
            ->orderBy('floor')
            ->orderBy('name')
            ->get();
        
        // Statistics
        $availableRooms = $rooms->where('status', 'available')->count();
        $totalRooms = $rooms->count();
        $occupiedRooms = $rooms->where('status', 'occupied')->count();
        
        // Get minimum price from available rooms only
        $availableRoomsCollection = $rooms->where('status', 'available');
        $minPrice = $availableRoomsCollection->min('price') ?? 0;
        $maxPrice = $availableRoomsCollection->max('price') ?? 0;
        
        // Get all unique facilities from all rooms
        $allFacilities = Facility::whereHas('rooms')->orderBy('name')->get();
        $FacilityAll = Facility::all();
        
        // Get properties
        $properties = Property::withCount(['rooms' => function($query) {
            $query->where('status', 'available');
        }])->get();


        //Location
        $property = Property::first();
        if ($property && !empty($property->address)) {
            $parts = array_map('trim', explode(',', $property->address));
            $propertyLocation = end($parts) ?: $parts[0];
        } else {
            $propertyLocation = 'Bandung';
        }

        //Kebutuhan Footer
        // 1. Kontak 
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);
        // 2. Address
        $address = Property::select('address')->first();
        view()->share('address', $address);
        // 3. Maps Embed
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

    public function roomDetail(Room $room)
    {
        // Load relasi yang dibutuhkan
        $room->load(['property', 'facilities', 'reviews.user']);

        // Hitung rating
        $reviews = $room->reviews;
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

         //Kebutuhan Footer
        // 1. Kontak 
        $contact = Property::select('phone', 'whatsapp')->first();
        view()->share('contact', $contact);
        // 2. Address
        $address = Property::select('address')->first();
        view()->share('address', $address);
        // 3. Maps Embed
        $mapsEmbed = Property::select('maps_embed')->first();
        view()->share('mapsEmbed', $mapsEmbed);


        // Distribusi rating (persentase untuk setiap bintang)
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $reviews->where('rating', $i)->count();
            $ratingDistribution[$i] = $totalReviews > 0 
                ? round(($count / $totalReviews) * 100, 1) 
                : 0;
        }

        // Ambil kamar lainnya dari property yang sama
        $similarRooms = Room::where('property_id', $room->property_id)
            ->where('id', '!=', $room->id)
            ->where('status', 'available')
            ->with(['facilities'])
            ->take(3)
            ->get();

        return view('landing.room-detail', compact(
            'room',
            'reviews',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'similarRooms',
            'contact',
            'address',
            'mapsEmbed'

        ));
    }
}