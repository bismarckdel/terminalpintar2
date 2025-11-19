<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display listing of users
     */
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        
        $users = User::with('roles')
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nama', 'LIKE', "%{$keyword}%");
            })
            ->latest()
            ->get();
        
        return Inertia::render('Admin/KelolaPengguna', [
            'user' => Auth::user(),
            'users' => $users,
            'filters' => [
                'search' => $keyword,
            ],
        ]);
    }

    /**
     * Show the form for creating new user
     */
    public function create()
    {
        // Get all available roles
        $roles = Role::all();
        
        return Inertia::render('Admin/User/Create', [
            'user' => Auth::user(),
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.exists' => 'Role tidak valid',
        ]);

        try {
            // Buat user baru
            $newUser = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign role menggunakan Spatie
            $newUser->assignRole($validated['role']);

            return redirect()->route('admin.user.index')
                ->with('success', 'Pengguna berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menyimpan pengguna: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Show the form for editing user
     */
    public function edit($id)
    {
        $editUser = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        
        return Inertia::render('Admin/User/Edit', [
            'user' => Auth::user(),
            'editUser' => $editUser,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $editUser = User::findOrFail($id);
        
        // Validasi data
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string|exists:roles,name',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'role.required' => 'Role wajib dipilih',
            'role.exists' => 'Role tidak valid',
        ]);

        try {
            // Update data user
            $editUser->nama = $validated['nama'];
            $editUser->email = $validated['email'];

            // Update role (hapus role lama, assign role baru)
            $editUser->syncRoles([$validated['role']]);

            $editUser->save();

            return redirect()->route('admin.user.index')
                ->with('success', 'Pengguna berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal memperbarui pengguna: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            $editUser = User::findOrFail($id);
            
            // Prevent deleting own account
            if ($editUser->id === Auth::id()) {
                return back()->withErrors([
                    'error' => 'Tidak dapat menghapus akun Anda sendiri!'
                ]);
            }

            // Check if user has role 'orangtua' and has related siswa
            if ($editUser->hasRole('orangtua')) {
                $siswaTerkait = \App\Models\Siswa::where('orang_tua_id', $editUser->id)->count();
                
                if ($siswaTerkait > 0) {
                    return back()->withErrors([
                        'error' => "Tidak dapat menghapus akun orang tua ini karena masih memiliki {$siswaTerkait} siswa terkait. Hapus siswa terlebih dahulu!"
                    ]);
                }
            }

            $editUser->delete();

            return redirect()->route('admin.user.index')
                ->with('success', 'Pengguna berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menghapus pengguna: ' . $e->getMessage()
            ]);
        }
    }
}
