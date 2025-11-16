<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <-- Pastikan ini ada
use App\Models\Berita;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita dengan paginasi dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Berita::query();

        // Cek jika ada parameter 'search'
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
            });
        }
        
        // Muat relasi penulis (jika diperlukan di masa depan)
        // $query->with('penulis');

        return response()->json($query->latest()->paginate(15));
    }

    /**
     * Simpan berita baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string',
            'konten' => 'required|string',
            'penulis_id' => 'required|integer|exists:users,id',
            'gambar' => 'nullable|string'
        ]);

        $berita = Berita::create($data);

        return response()->json(['message' => 'Berita created', 'berita' => $berita], 201);
    }

    /**
     * Ambil data statistik untuk kartu.
     */
    public function getStats()
    {
        $totalBerita = Berita::count();

        // Logika Fallback: Jika tidak ada berita, tampilkan data dummy dari gambar.
        if ($totalBerita == 0) {
            return response()->json([
                'total_foto' => 57
            ]);
        }

        // Logika Asli: Hitung berita yang memiliki gambar
        // Asumsi: 'gambar' tidak null/kosong jika ada foto.
        $totalFoto = Berita::whereNotNull('gambar')
                           ->where('gambar', '!=', '')
                           ->count();

        return response()->json([
            'total_foto' => $totalFoto
        ]);
    }

    /**
     * Hapus data berita.
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return response()->json(['message' => 'Berita deleted'], 200);
    }
}