<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomApiController extends Controller
{
    /**
     * GET /api/rooms
     */
    public function index(Request $request)
    {
        $query = Room::with(['property', 'facilities']);

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $rooms = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List kamar',
            'data' => $rooms
        ]);
    }

    /**
     * GET /api/rooms/{room}
     */
    public function show(Room $room)
    {
        $room->load([
            'property',
            'facilities',
            'reviews.user'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail kamar',
            'data' => $room
        ]);
    }

    /**
     * GET /api/rooms/available/list
     */
    public function available()
    {
        $rooms = Room::with('property')
            ->where('status', 'available')
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Kamar tersedia',
            'data' => $rooms
        ]);
    }
}
