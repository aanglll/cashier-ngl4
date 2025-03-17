<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view role')) {
            return redirect()->back()->with('error', 'You do not have permission to view the role.');
        }
        $roles = Role::where('id', '!=', 1)->latest()->paginate(10);
        $settings = Setting::first();
        return view('backend.role-permission.index', compact('roles', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create role')) {
            return redirect()->back()->with('error', 'You do not have permission to create the role.');
        }
        $permissions = Permission::all();
        $role = new Role();

        return view('backend.role-permission.create', compact('permissions', 'role', 'settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create role')) {
            return redirect()->back()->with('error', 'You do not have permission to create the role.');
        }
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

    public function edit($id)
    {
        if (!auth()->user()->can('edit role')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the role.');
        }
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $settings = Setting::first();

        return view('backend.role-permission.edit', compact('role', 'permissions', 'settings'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit role')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the role.');
        }
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
        if (!auth()->user()->can('delete role')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the role.');
        }
        $role = Role::findOrFail($id);

        // Pastikan role dengan ID 1 (biasanya superadmin) tidak bisa dihapus
        if ($role->id == 1) {
            return redirect()->route('role-permission.index')->with('error', 'Role cannot be deleted.');
        }

        // Hapus semua permissions terkait terlebih dahulu (jika diperlukan)
        $role->permissions()->detach();

        $role->delete();

        return redirect()->route('role-permission.index')->with('success', 'Role deleted successfully.');
    }
}
