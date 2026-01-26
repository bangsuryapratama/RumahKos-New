<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class FacilityRoomController extends Controller
{
    /**
     * Display a listing of room facilities assignments
     */
    public function index()
    {
        $rooms = Room::with(['property:id,name', 'facilities:id,name,icon'])
            ->withCount('facilities')
            ->orderBy('name', 'asc')
            ->paginate(15);
        
        return view('admin.facility_rooms.index', compact('rooms'));
    }

    /**
     * Show the form for assigning facilities to a room
     */
    public function create()
    {
        $rooms = Room::with('property:id,name')
            ->orderBy('name', 'asc')
            ->get();
        
        $facilities = Facility::orderBy('name', 'asc')->get();
        
        return view('admin.facility_rooms.create', compact('rooms', 'facilities'));
    }

    /**
     * Store facility assignments for a room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'facilities' => 'required|array|min:1',
            'facilities.*' => 'exists:facilities,id',
        ], [
            'room_id.required' => 'Kamar wajib dipilih.',
            'room_id.exists' => 'Kamar tidak ditemukan.',
            'facilities.required' => 'Minimal pilih 1 fasilitas.',
            'facilities.min' => 'Minimal pilih 1 fasilitas.',
            'facilities.*.exists' => 'Fasilitas tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            $room = Room::findOrFail($validated['room_id']);
            
            // Sync facilities (will remove old and add new)
            $room->facilities()->sync($validated['facilities']);

            DB::commit();

            return redirect()
                ->route('admin.facility_rooms.index')
                ->with('success', 'Fasilitas berhasil di-assign ke kamar ' . $room->name . '.');
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing room facilities
     */
    public function edit($facility_room)  
    {
        // Find room by ID
        $room = Room::with(['facilities', 'property'])->findOrFail($facility_room);
        
        $facilities = Facility::orderBy('name', 'asc')->get();
        
        return view('admin.facility_rooms.edit', compact('room', 'facilities'));
    }

    /**
     * Update facility assignments for a room
     */
    public function update(Request $request, $facility_room)  
    {
        $validated = $request->validate([
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ], [
            'facilities.*.exists' => 'Fasilitas tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            // Find room by ID
            $room = Room::findOrFail($facility_room);

            // Count before update for message
            $oldCount = $room->facilities()->count();

            // Sync facilities (empty array will remove all)
            $room->facilities()->sync($validated['facilities'] ?? []);

            $newCount = count($validated['facilities'] ?? []);

            DB::commit();

            $message = 'Fasilitas kamar ' . $room->name . ' berhasil diperbarui.';
            
            if ($newCount > $oldCount) {
                $message .= ' Ditambahkan ' . ($newCount - $oldCount) . ' fasilitas baru.';
            } elseif ($newCount < $oldCount) {
                $message .= ' Dihapus ' . ($oldCount - $newCount) . ' fasilitas.';
            }

            return redirect()
                ->route('admin.facility_rooms.index')
                ->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Remove all facilities from a room
     */
    public function destroy($facility_room)
    {
        try {
            DB::beginTransaction();

            // Find room by ID
            $room = Room::with('facilities')->findOrFail($facility_room);

            $facilityCount = $room->facilities()->count();
            
            if ($facilityCount === 0) {
                return redirect()
                    ->route('admin.facility_rooms.index')
                    ->with('error', 'Kamar ' . $room->name . ' tidak memiliki fasilitas.');
            }

            $room->facilities()->detach();

            DB::commit();

            return redirect()
                ->route('admin.facility_rooms.index')
                ->with('success', 'Berhasil menghapus ' . $facilityCount . ' fasilitas dari kamar ' . $room->name . '.');
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('admin.facility_rooms.index')
                ->with('error', 'Gagal menghapus fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Show details of a specific room's facilities
     */
    public function show($facility_room) 
    {
        $room = Room::with(['property', 'facilities'])->findOrFail($facility_room);
        
        return view('admin.facility_rooms.show', compact('room'));
    }
}