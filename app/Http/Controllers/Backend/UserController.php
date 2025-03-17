<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view users')) {
            return redirect()->back()->with('error', 'You do not have permission to view the user.');
        }
        $users = User::where('id', '!=', 1)->latest()->paginate(10);
        $settings = Setting::first();
        return view('backend.user.index', compact('users', 'settings'));
    }

    public function create()
    {
        if (!auth()->user()->can('create users')) {
            return redirect()->back()->with('error', 'You do not have permission to create the user.');
        }
        $settings = Setting::first();
        return view('backend.user.create', compact('settings'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create users')) {
            return redirect()->back()->with('error', 'You do not have permission to create the user.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'user_priv' => 'required|in:superadmin,admin,officer,warehouse admin',
            'status' => 'required|in:active,inactive',
            'address' => 'required|string',
            'phone' => 'required|digits_between:1,15',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_priv' => $request->user_priv,
            'status' => $request->status,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        // Berikan role ke user berdasarkan `user_priv`
        $role = Role::where('name', $request->user_priv)->first();
        if ($role) {
            $user->assignRole($role);
        }

        return redirect()->route('backend.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit users')) {
            return redirect()->back()->with('error', 'You do not have permission to edit the user.');
        }
        if ($id == 1) {
            abort(403, 'Unauthorized Access');
        }

        $user = User::findOrFail($id);
        $settings = Setting::first();
        return view('backend.user.edit', compact('user', 'settings'));
    }

    public function update(Request $request, $id)
    {
        // if (!auth()->user()->can('edit users')) {
        //     return redirect()->back()->with('error', 'You do not have permission to edit the user.');
        // }
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'user_priv' => 'required|in:superadmin,admin,officer,warehouse admin',
            'status' => 'required|in:active,inactive',
            'address' => 'required|string',
            'phone' => 'required|digits_between:1,15',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_priv' => $request->user_priv,
            'status' => $request->status,
            'address' => $request->address,
            'phone' => $request->phone,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Update role user
        // if ($user->hasAnyRole(['superadmin', 'admin', 'officer', 'warehouse admin'])) {
        //     $user->roles()->detach(); // Hapus role lama
        // }

        $role = Role::where('name', $request->user_priv)->first();
        if ($role) {
            $user->assignRole($role);
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete users')) {
            return redirect()->back()->with('error', 'You do not have permission to delete the user.');
        }
        if ($id == 1) {
            return redirect()->back()->with('error', 'This user cannot be deleted.');
        }

        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
