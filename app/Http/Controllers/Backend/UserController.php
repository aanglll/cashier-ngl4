<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', 1)->latest()->paginate(10);
        return view('backend.user.index', compact('users'));
    }

    public function create()
    {
        return view('backend.user.create');
    }

    public function store(Request $request)
    {
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
        if ($id == 1) {
            abort(403, 'Unauthorized Access');
        }

        $user = User::findOrFail($id);
        return view('backend.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
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
        if ($user->hasAnyRole(['superadmin', 'admin', 'officer', 'warehouse admin'])) {
            $user->roles()->detach(); // Hapus role lama
        }

        $role = Role::where('name', $request->user_priv)->first();
        if ($role) {
            $user->assignRole($role);
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if ($id == 1) {
            return redirect()->back()->with('error', 'This user cannot be deleted.');
        }

        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
