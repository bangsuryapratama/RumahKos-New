<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class FacilityRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['property:id,name', 'facilities:id,name,icon'])
            ->withCount('facilities')
            ->orderByRaw("CAST(SUBSTRING_INDEX(name, ' ', -1) AS UNSIGNED) ASC")
            ->paginate(15);

        return view('admin.facility_rooms.index', compact('rooms'));
    }

    public function create()
    {
        $rooms      = Room::with('property:id,name')->orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();

        return view('admin.facility_rooms.create', compact('rooms', 'facilities'));
    }

    /**
     * Store — supports single room OR bulk (multiple rooms, same facilities)
     */
    public function store(Request $request)
    {
        // Bulk mode: room_ids[] — assign same facilities to many rooms
        // Single mode: room_id  — assign facilities to one room
        $isBulk = $request->has('room_ids') && is_array($request->room_ids);

        $rules = [
            'facilities'   => 'required|array|min:1',
            'facilities.*' => 'exists:facilities,id',
        ];

        if ($isBulk) {
            $rules['room_ids']   = 'required|array|min:1';
            $rules['room_ids.*'] = 'exists:rooms,id';
        } else {
            $rules['room_id'] = 'required|exists:rooms,id';
        }

        $messages = [
            'room_id.required'   => 'Kamar wajib dipilih.',
            'room_ids.required'  => 'Pilih minimal 1 kamar.',
            'facilities.required'=> 'Minimal pilih 1 fasilitas.',
            'facilities.min'     => 'Minimal pilih 1 fasilitas.',
        ];

        $validated = $request->validate($rules, $messages);

        try {
            [$rooms, $roomIds, $facilityIds] = DB::transaction(function () use ($validated, $isBulk, $request) {
                $roomIds     = $isBulk ? $validated['room_ids'] : [$validated['room_id']];
                $facilityIds = $validated['facilities'];
                $rooms       = Room::findMany($roomIds);

                foreach ($rooms as $room) {
                    // merge_mode: 'attach' -> add without removing existing, otherwise replace
                    if ($request->merge_mode === 'attach') {
                        $room->facilities()->syncWithoutDetaching($facilityIds);
                    } else {
                        $room->facilities()->sync($facilityIds);
                    }
                }

                return [$rooms, $roomIds, $facilityIds];
            });

            $roomNames = $rooms->pluck('name')->join(', ');
            $msg = count($roomIds) > 1
                ? count($facilityIds) . ' fasilitas berhasil di-assign ke ' . count($roomIds) . ' kamar (' . $roomNames . ').'
                : 'Fasilitas berhasil di-assign ke kamar ' . $rooms->first()->name . '.';

            return redirect()->route('admin.facility_rooms.index')->with('success', $msg);

        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function edit($facility_room)
    {
        $room       = Room::with(['facilities', 'property'])->findOrFail($facility_room);
        $facilities = Facility::orderBy('name')->get();

        return view('admin.facility_rooms.edit', compact('room', 'facilities'));
    }

    public function update(Request $request, $facility_room)
    {
        $validated = $request->validate([
            'facilities'   => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);
        try {
            [$room, $oldCount, $newCount] = DB::transaction(function () use ($facility_room, $validated) {
                $room     = Room::findOrFail($facility_room);
                $oldCount = $room->facilities()->count();
                $room->facilities()->sync($validated['facilities'] ?? []);
                $newCount = count($validated['facilities'] ?? []);

                return [$room, $oldCount, $newCount];
            });

            $diff = $newCount - $oldCount;
            $msg  = 'Fasilitas kamar ' . $room->name . ' berhasil diperbarui.';
            if ($diff > 0)      $msg .= ' +' . $diff . ' fasilitas baru.';
            elseif ($diff < 0)  $msg .= ' ' . abs($diff) . ' fasilitas dihapus.';

            return redirect()->route('admin.facility_rooms.index')->with('success', $msg);

        } catch (Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($facility_room)
    {
        try {
            [$room, $count] = DB::transaction(function () use ($facility_room) {
                $room  = Room::with('facilities')->findOrFail($facility_room);
                $count = $room->facilities()->count();

                if ($count === 0) {
                    // don't perform detach; bubble up a custom exception-like structure by returning values
                    return [$room, 0];
                }

                $room->facilities()->detach();

                return [$room, $count];
            });

            if ($count === 0) {
                return redirect()->route('admin.facility_rooms.index')
                    ->with('error', 'Kamar ' . $room->name . ' tidak memiliki fasilitas.');
            }

            return redirect()->route('admin.facility_rooms.index')
                ->with('success', 'Berhasil menghapus ' . $count . ' fasilitas dari kamar ' . $room->name . '.');

        } catch (Throwable $e) {
            return redirect()->route('admin.facility_rooms.index')
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show($facility_room)
    {
        $room = Room::with(['property', 'facilities'])->findOrFail($facility_room);
        return view('admin.facility_rooms.show', compact('room'));
    }
}