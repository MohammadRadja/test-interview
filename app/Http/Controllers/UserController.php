<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Jobs\ExportUserExcel;
use App\Models\Export;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    // Tampilkan detail export
    public function showExport()
    {
        $exports = Export::with('user')->latest()->get();
        return view('exports.index', compact('exports'));
    }


    // Tampilkan form tambah user
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('users.create', compact('roles', 'permissions'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'nullable|array',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign roles ke user
        $user->syncRoles($request->role);

        // Assign permissions ke user
        $permissions = $request->permissions ?? [];
        $user->syncPermissions($permissions);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    // Tampilkan form edit user
    public function edit(User $user)
    {

        $roles = Role::all();
        $permissions = Permission::all();

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required|string|exists:roles,name',
            'permissions' => 'nullable|array',
        ]);

        try {
            $user = User::findOrFail($id);

            // Simpan data lama untuk log
            $oldData = [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray(),
                'permissions' => $user->permissions->pluck('name')->toArray(),
            ];

            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Sync roles dan permissions baru
            $user->syncRoles($request->role);
            $permissions = $request->permissions ?? [];
            $user->syncPermissions($permissions);

            // Simpan data baru untuk log
            $newData = [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray(),
                'permissions' => $user->permissions->pluck('name')->toArray(),
            ];

            // Log perubahan user
            Log::info('User updated', [
                'user_id' => $user->id,
                'old_data' => $oldData,
                'new_data' => $newData,
                'updated_by' => auth()->user()->id ?? null,
                'updated_at' => now(),
            ]);

            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');

        } catch (Exception $e) {
            // Log error
            Log::error('Failed to update user', [
                'user_id' => $id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'attempted_data' => $request->all(),
                'updated_by' => auth()->user()->id ?? null,
                'failed_at' => now(),
            ]);

            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui user.']);
        }
    }


    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }

    // Export users via job (background)
    public function export()
    {
        $export = Export::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        ExportUserExcel::dispatch($export->id);

        return redirect()->route('exports.index')->with('success', 'Export sedang diproses...');
    }

    public function downloadExport($id)
    {
        $export = Export::findOrFail($id);

        if ($export->status !== 'done' || !$export->file_path) {
            return redirect()->back()->with('error', 'File export belum tersedia atau sedang diproses.');
        }

        return Storage::disk('public')->download($export->file_path);
    }
}
