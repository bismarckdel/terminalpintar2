<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Berita;

class BeritaController extends Controller
{
    /**
     * Display listing of berita
     */
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        
        $berita = Berita::with('penulis')
            ->when($keyword, function ($query, $keyword) {
                return $query->where('judul', 'LIKE', "%{$keyword}%")
                            ->orWhere('konten', 'LIKE', "%{$keyword}%");
            })
            ->latest()
            ->paginate(10);
        
        // Calculate statistics
        $stats = [
            'totalBerita' => Berita::count(),
            'totalFoto' => Berita::whereNotNull('gambar')->count(),
        ];
        
        return Inertia::render('Admin/KelolaBerita', [
            'user' => Auth::user(),
            'berita' => $berita,
            'stats' => $stats,
            'filters' => [
                'search' => $keyword,
            ],
        ]);
    }

    /**
     * Show the form for creating new berita
     */
    public function create()
    {
        return Inertia::render('Admin/Berita/Create', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Store a newly created berita
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'judul.required' => 'Judul berita wajib diisi',
            'konten.required' => 'Konten berita wajib diisi',
            'gambar.required' => 'Foto berita wajib diunggah',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            // Upload foto ke storage/app/public/berita
            $path = $request->file('gambar')->store('berita', 'public');

            // Simpan data berita
            Berita::create([
                'judul' => $validated['judul'],
                'konten' => $validated['konten'],
                'gambar' => $path,
                'penulis_id' => Auth::id(),
            ]);

            return redirect()->route('admin.berita.index')
                ->with('success', 'Berita berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menyimpan berita: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Show the form for editing berita
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        
        return Inertia::render('Admin/Berita/Edit', [
            'user' => Auth::user(),
            'berita' => $berita,
        ]);
    }

    /**
     * Update the specified berita
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        
        // Validasi data (gambar opsional untuk update)
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'judul.required' => 'Judul berita wajib diisi',
            'konten.required' => 'Konten berita wajib diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        try {
            // Update data berita
            $berita->judul = $validated['judul'];
            $berita->konten = $validated['konten'];

            // Jika ada foto baru yang diupload
            if ($request->hasFile('gambar')) {
                // Hapus foto lama jika ada
                if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                    Storage::disk('public')->delete($berita->gambar);
                }

                // Upload foto baru
                $path = $request->file('gambar')->store('berita', 'public');
                $berita->gambar = $path;
            }

            $berita->save();

            return redirect()->route('admin.berita.index')
                ->with('success', 'Berita berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal memperbarui berita: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified berita
     */
    public function destroy($id)
    {
        try {
            $berita = Berita::findOrFail($id);

            // Hapus file foto dari storage jika ada
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            // Gunakan forceDelete jika model mendukung soft delete, jika tidak fallback ke delete biasa
            if (method_exists($berita, 'forceDelete')) {
                $berita->forceDelete();
            } else {
                $berita->delete();
            }

            return redirect()->route('admin.berita.index')
                ->with('success', 'Berita berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menghapus berita: ' . $e->getMessage()
            ]);
        }
    }
}
