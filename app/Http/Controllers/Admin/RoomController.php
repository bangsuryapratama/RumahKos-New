<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
   public function index(Request $request)
{
    $query = Room::with('property');

    // Search
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // =============================
    // DEFAULT SORT: A-Z NUMERIC
    // =============================
    $query->orderByRaw("
        CAST(SUBSTRING_INDEX(name, ' ', -1) AS UNSIGNED) ASC
    ");

    // =============================
    // CUSTOM SORT
    // =============================
    if ($request->filled('sort')) {

        // reset default order
        $query->getQuery()->orders = null;

        switch ($request->sort) {
            case 'name_desc':
                $query->orderByRaw("
                    CAST(SUBSTRING_INDEX(name, ' ', -1) AS UNSIGNED) DESC
                ");
                break;

            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            default:
                $query->latest();
        }
    }

    $rooms = $query->paginate(10);

    // Statistik
    $allRooms = Room::all();
    $totalRooms = $allRooms->count();
    $availableCount = $allRooms->where('status', 'available')->count();
    $occupiedCount = $allRooms->where('status', 'occupied')->count();
    $totalRevenue = $allRooms->where('status', 'occupied')->sum('price');

    return view('admin.rooms.index', compact(
        'rooms',
        'totalRooms',
        'availableCount',
        'occupiedCount',
        'totalRevenue'
    ));
}


    public function create()
    {
        $properties = Property::all();
        return view('admin.rooms.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required',
            'name' => 'required',
            'floor' => 'required',
            'size' => 'required',
            'status' => 'required',
            'price' => 'required',
            'billing_cycle' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        Room::create($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit(Room $room)
    {
        $properties = Property::all();
        return view('admin.rooms.edit', compact('room', 'properties'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'property_id' => 'required',
            'name' => 'required',
            'floor' => 'required',
            'size' => 'required',
            'status' => 'required',
            'price' => 'required',
            'billing_cycle' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {
            if ($room->image) Storage::disk('public')->delete($room->image);
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil diupdate');
    }

    public function show($id)
    {
        // Get room with relationships
        $room = Room::with(['property', 'facilities'])->findOrFail($id);

        // Get reviews with pagination - MUST use paginate() not get()
        $reviews = Review::where('room_id', $room->id)
            ->with('user')
            ->latest()
            ->paginate(10); // This creates paginator instance

        // Calculate average rating
        $averageRating = Review::where('room_id', $room->id)->avg('rating') ?? 0;
        $totalReviews = Review::where('room_id', $room->id)->count();

        // Get category averages
        $categoryAverages = $this->getCategoryAverages($room->id);

        // Get similar rooms
        $similarRooms = Room::where('property_id', $room->property_id)
            ->where('id', '!=', $room->id)
            ->where('status', 'available')
            ->limit(3)
            ->get();

        return view('landing.room-detail', compact(
            'room',
            'reviews',
            'averageRating',
            'totalReviews',
            'categoryAverages',
            'similarRooms'
        ));
    }

    /**
     * Calculate average scores per category
     */
    private function getCategoryAverages($roomId)
    {
        $reviews = Review::where('room_id', $roomId)
            ->whereNotNull('category_ratings')
            ->get();

        if ($reviews->isEmpty()) {
            return [];
        }

        $categories = ReviewAutoScoring::getCategories();
        $averages = [];

        foreach ($categories as $key => $category) {
            $scores = [];

            foreach ($reviews as $review) {
                $ratings = $review->category_ratings;
                if (is_array($ratings) && isset($ratings[$key]) && $ratings[$key] > 0) {
                    $scores[] = $ratings[$key];
                }
            }

            if (!empty($scores)) {
                $averages[$key] = [
                    'name' => $category['name'],
                    'score' => round(array_sum($scores) / count($scores), 1),
                    'icon' => $category['icon'],
                    'color' => $category['color']
                ];
            }
        }

        return $averages;
    }

    public function destroy(Room $room)
    {
        if ($room->image) Storage::disk('public')->delete($room->image);
        $room->delete();

        return back()->with('success', 'Kamar berhasil dihapus');
    }
}
