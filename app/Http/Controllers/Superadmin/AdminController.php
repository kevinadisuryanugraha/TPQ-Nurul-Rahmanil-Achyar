<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->paginate(10);
        return view('superadmin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin',
            'is_active' => 'required|boolean',
        ]);

        $admin = Admin::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        // Assign Spatie permission role
        $roleName = $request->role;
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'admin']);
        $admin->assignRole($role);

        return redirect()->route('superadmin.admins.index')->with('success', 'Akun pengurus baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('superadmin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:admins,email,' . $id,
            'no_hp' => 'nullable|string|max:20',
            'role' => 'required|in:superadmin,admin',
            'is_active' => 'required|boolean',
        ]);

        $admin->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        // Sync Spatie role
        $roleName = $request->role;
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'admin']);
        $admin->syncRoles([$role]);

        return redirect()->route('superadmin.admins.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $admin->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('superadmin.admins.index')->with('success', 'Password untuk pengurus ' . $admin->nama . ' berhasil direset.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        
        // Prevent deleting oneself
        if (auth()->guard('admin')->id() == $id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // soft delete: set active = false
        $admin->update(['is_active' => false]);

        return redirect()->route('superadmin.admins.index')->with('success', 'Akun pengurus berhasil dinonaktifkan.');
    }
}
