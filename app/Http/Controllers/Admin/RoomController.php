<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('property')
            ->latest()
            ->paginate(10);

        return view('admin.rooms.index', compact('rooms'));
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

    public function show(Room $room)
    {
        Room::with('property', 'facilities', 'residents')->find($room->id);
        return view('admin.rooms.show', compact('room'));

    }

    public function destroy(Room $room)
    {
        if ($room->image) Storage::disk('public')->delete($room->image);
        $room->delete();

        return back()->with('success', 'Kamar berhasil dihapus');
    }
}
