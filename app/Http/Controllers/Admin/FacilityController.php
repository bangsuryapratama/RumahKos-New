<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::withCount('rooms')
            ->latest()
            ->paginate(15);
        
        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name',
            'icon' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Nama fasilitas sudah ada.',
            'icon.required' => 'Icon wajib diisi.',
        ]);

        try {
            Facility::create($validated);

            return redirect()
                ->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        $facility->load(['rooms.property']);
        return view('admin.facilities.show', compact('facility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name,' . $facility->id,
            'icon' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama fasilitas wajib diisi.',
            'name.unique' => 'Nama fasilitas sudah ada.',
            'icon.required' => 'Icon wajib diisi.',
        ]);

        try {
            $facility->update($validated);

            return redirect()
                ->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        try {
            DB::beginTransaction();

            // Detach all rooms before deleting
            $facility->rooms()->detach();
            $facility->delete();

            DB::commit();

            return redirect()
                ->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('admin.facilities.index')
                ->with('error', 'Gagal menghapus fasilitas: ' . $e->getMessage());
        }
    }
}