<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::where('id', '!=', 1)->latest()->paginate(10);

        return view('backend.role-permission.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        $role = new Role();

        return view('backend.role-permission.create', compact('permissions', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'guard_name' => 'required|string',
            'permissions' => 'array|nullable',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        if ($request->filled('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('role-permission.index')->with('success', 'Role created successfully.');
    }

    // Edit existing role
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('backend.role-permission.edit', compact('role', 'permissions'));
    }

    // Update the role
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id . '|max:255',
            'guard_name' => 'required|string',
            'permissions' => 'array|nullable',
        ]);

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        $role->permissions()->sync($request->permissions);

        return redirect()->route('role-permission.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Pastikan role dengan ID 1 (biasanya superadmin) tidak bisa dihapus
        if ($role->id == 1) {
            return redirect()->route('role-permission.index')->with('error', 'Role cannot be deleted.');
        }

        // Hapus semua permissions terkait terlebih dahulu (jika diperlukan)
        $role->permissions()->detach();

        // Hapus role
        $role->delete();

        return redirect()->route('role-permission.index')->with('success', 'Role deleted successfully.');
    }
}
