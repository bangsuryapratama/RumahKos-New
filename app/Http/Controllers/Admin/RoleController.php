<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil dibuat ✅');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role berhasil diupdate ✨');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Role masih digunakan user ❌');
        }

        $role->delete();

        return back()->with('success', 'Role berhasil dihapus 🗑️');
    }
}
