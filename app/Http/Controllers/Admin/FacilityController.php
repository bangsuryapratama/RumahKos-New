<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::withCount('rooms');
     
        
            //cari berdasarkan icon
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

         //digunakan atau tidak
        if ($request->filled('usage')) {
            if ($request ->usage == 'used') {
                $query->having('rooms_count', '>', 0);
            } elseif ($request->usage == 'unused'){
                $query->having('rooms_count', '=', 0);
            }
        }

        //sort
        match ($request->sort){
            'name_asc' => $query->orderBy('name','asc'),
            'name_dessc' => $query->orderBy('name','desc'),
            'most_used' => $query->orderByDesc('rooms_count'),
            'least_used' => $query->orderBy('rooms_count', 'asc'),
            default => $query->latest(),
        };


        $facilities = $query->paginate(15)->withQueryString();

        return view('admin.facilities.index', compact('facilities'));
    }
    public function create()
    {
        return view('admin.facilities.create');
    }

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

    public function show(Facility $facility)
    {
        $facility->load(['rooms.property']);
        return view('admin.facilities.show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }


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