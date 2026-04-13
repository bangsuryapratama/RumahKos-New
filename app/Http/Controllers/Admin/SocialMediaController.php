<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $list = SocialMedia::all();
        return view('admin.socialmedia.index', ['list' => $list]);
    }

    public function create()
    {
        return view('admin.socialmedia.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'instagram' => 'nullable|string|max:255',
            'facebook'  => 'nullable|string|max:255',
            'tiktok'    => 'nullable|string|max:255',
        ]);

        SocialMedia::create($data);

        return redirect()
            ->route('admin.socialmedia.index')
            ->with(['type' => 'success', 'message' => 'Sosmed berhasil ditambahkan']);
    }

    public function show($id)
    {
        $item = SocialMedia::findOrFail($id);
        return view('admin.socialmedia.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = SocialMedia::findOrFail($id);
        return view('admin.socialmedia.edit', ['item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = SocialMedia::findOrFail($id);

        $data = $request->validate([
            'instagram' => 'nullable|string|max:255',
            'facebook'  => 'nullable|string|max:255',
            'tiktok'    => 'nullable|string|max:255',
        ]);

        $item->update($data);

        return redirect()
            ->route('admin.socialmedia.index')
            ->with(['type' => 'success', 'message' => 'Sosmed berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $item = SocialMedia::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('admin.socialmedia.index')
            ->with(['type' => 'success', 'message' => 'Sosmed berhasil dihapus']);
    }
}