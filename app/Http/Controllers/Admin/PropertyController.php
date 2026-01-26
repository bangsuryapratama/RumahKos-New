<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('owner')
            ->latest()
            ->paginate(10);

        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $owners = User::orderBy('name')->get();
        return view('admin.properties.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'owner_id'    => 'required|exists:users,id',
            'name'        => 'required|string|max:255',
            'address'     => 'required|string',
            'maps_embed'  => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'whatsapp'    => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        Property::create($data);

        return redirect()
            ->route('admin.properties.index')
            ->with([
                'type' => 'success',
                'message' => 'Property berhasil ditambahkan'
            ]);
    }

    public function show(Property $property)
    {
        $property->load('owner', 'rooms');
        return view('admin.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $owners = User::orderBy('name')->get();
        return view('admin.properties.edit', compact('property', 'owners'));
    }

    public function update(Request $request, Property $property)
    {
        $data = $request->validate([
            'owner_id'    => 'required|exists:users,id',
            'name'        => 'required|string|max:255',
            'address'     => 'required|string',
            'maps_embed'  => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'whatsapp'    => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $property->update($data);

        return redirect()
            ->route('admin.properties.index')
            ->with([
                'type' => 'success',
                'message' => 'Property berhasil diperbarui'
            ]);
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()
            ->route('admin.properties.index')
            ->with([
                'type' => 'success',
                'message' => 'Property berhasil dihapus'
            ]);
    }
}
