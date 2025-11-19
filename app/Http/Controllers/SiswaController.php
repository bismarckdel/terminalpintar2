<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Siswa;
use App\Models\User;

class SiswaController extends Controller
{
    /**
     * Display listing of siswa
     */
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        
        $siswa = Siswa::with('orangTua')
            ->when($keyword, function ($query, $keyword) {
                return $query->where('nama_lengkap', 'LIKE', "%{$keyword}%");
            })
            ->latest()
            ->get();
        
        return Inertia::render('Admin/KelolaSiswa', [
            'user' => Auth::user(),
            'siswa' => $siswa,
            'filters' => [
                'search' => $keyword,
            ],
        ]);
    }

    /**
     * Show the form for creating a new siswa
     */
    public function create()
    {
        return Inertia::render('Admin/Siswa/Create', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Store a newly created siswa
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Nonaktif',
            'nama_orang_tua' => 'required|string|max:255',
            'email_orang_tua' => 'required|email|unique:users,email',
            'password_orang_tua' => 'required|string|min:8',
        ], [
            'nama_lengkap.required' => 'Nama lengkap siswa wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus Aktif atau Nonaktif',
            'nama_orang_tua.required' => 'Nama orang tua wajib diisi',
            'email_orang_tua.required' => 'Email orang tua wajib diisi',
            'email_orang_tua.email' => 'Format email tidak valid',
            'email_orang_tua.unique' => 'Email sudah terdaftar, gunakan email lain',
            'password_orang_tua.required' => 'Password wajib diisi',
            'password_orang_tua.min' => 'Password minimal 8 karakter',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat akun orang tua dengan role 'orangtua'
            $parentUser = User::create([
                'nama' => $validated['nama_orang_tua'],
                'email' => $validated['email_orang_tua'],
                'password' => Hash::make($validated['password_orang_tua']),
            ]);

            // Assign role 'orangtua' menggunakan Spatie
            $parentUser->assignRole('orangtua');

            // 2. Simpan data siswa dengan relasi ke orang tua
            $siswa = Siswa::create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'status' => $validated['status'],
                'orang_tua_id' => $parentUser->id,
            ]);

            DB::commit();

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil ditambahkan! Akun orang tua telah dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Show the form for editing siswa
     */
    public function edit($id)
    {
        $siswa = Siswa::with('orangTua')->findOrFail($id);
        
        return Inertia::render('Admin/Siswa/Edit', [
            'user' => Auth::user(),
            'siswa' => $siswa,
        ]);
    }

    /**
     * Update the specified siswa
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::with('orangTua')->findOrFail($id);
        
        // Validasi data
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Nonaktif',
            'nama_orang_tua' => 'required|string|max:255',
            'email_orang_tua' => 'required|email|unique:users,email,' . $siswa->orang_tua_id,
        ], [
            'nama_lengkap.required' => 'Nama lengkap siswa wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus Aktif atau Nonaktif',
            'nama_orang_tua.required' => 'Nama orang tua wajib diisi',
            'email_orang_tua.required' => 'Email orang tua wajib diisi',
            'email_orang_tua.email' => 'Format email tidak valid',
            'email_orang_tua.unique' => 'Email sudah terdaftar, gunakan email lain',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update data siswa
            $siswa->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'status' => $validated['status'],
            ]);

            // 2. Update data orang tua (User)
            $parentUser = User::findOrFail($siswa->orang_tua_id);
            $parentUser->update([
                'nama' => $validated['nama_orang_tua'],
                'email' => $validated['email_orang_tua'],
            ]);

            DB::commit();

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Gagal memperbarui data: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified siswa
     */
    public function destroy($id)
    {
        // Delete logic nanti
        return redirect()->route('admin.siswa.index');
    }
}
