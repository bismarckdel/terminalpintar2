<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // <-- TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Ganti DB dengan Hash
use Illuminate\Validation\Rule; // <-- TAMBAHKAN INI

class UserController extends Controller
{
    // Diperbarui untuk memuat relasi 'role'
    public function index(Request $request)
    {
        $query = User::with('role'); // <-- UBAH DI SINI: Muat relasi role

        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                // Hapus 'telepon' jika tidak ada di migrasi Anda
            });
        }

        return response()->json($query->paginate(15));
    }

    // Diperbarui untuk menggunakan role_id
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer|exists:roles,id', // <-- UBAH DI SINI
            'is_active' => 'boolean', // <-- Tambahkan ini jika perlu
        ]);

        $data['password'] = Hash::make($data['password']); // Gunakan Hash::make

        $user = User::create($data);

        return response()->json(['message' => 'User created', 'user' => $user], 201);
    }

    // Diperbarui untuk menggunakan relasi role
    public function stats()
    {
        $total = User::count();
        $aktif = User::count(); // Asumsikan 'is_active' belum ada di migrasi
        
        $admin_guru = User::whereHas('role', function($q) {
            $q->whereIn('nama_role', ['Admin', 'Guru']); // <-- UBAH DI SINI
        })->count();
        
        $orang_tua = User::whereHas('role', function($q) {
            $q->where('nama_role', 'Orang Tua'); // <-- UBAH DI SINI
        })->count();

        return response()->json([
            'total' => $total,
            'aktif' => $aktif,
            'admin_guru' => $admin_guru,
            'orang_tua' => $orang_tua
        ]);
    }

    // Diperbarui untuk menggunakan role_id
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|integer|exists:roles,id', // <-- UBAH DI SINI
            'is_active' => 'boolean',
        ]);

        if ($request->has('password') && $request->input('password')) {
            $data['password'] = Hash::make($request->input('password')); // Gunakan Hash::make
        }

        $user->update($data);

        return response()->json(['message' => 'User updated', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted'], 200);
    }
}