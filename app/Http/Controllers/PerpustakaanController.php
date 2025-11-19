<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

class PerpustakaanController extends Controller
{
    /**
     * Display listing of books
     */
    public function index(Request $request)
    {
        $query = Buku::query();
        
        // Search by judul or penulis (based on sequence diagram: keyword search)
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', '%' . $keyword . '%');
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $buku = $query->latest()->get();
        
        // Get statistics (recalculated based on filtered results for accuracy)
        $stats = [
            'total' => Buku::count(),
            'tersedia' => Buku::where('status', 'Tersedia')->count(),
            'dipinjam' => Buku::where('status', 'Dipinjam')->count(),
        ];
        
        return Inertia::render('Admin/KelolaPerpustakaan', [
            'user' => Auth::user(),
            'buku' => $buku,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating new book
     */
    public function create()
    {
        return Inertia::render('Admin/Buku/Create', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'status' => 'required|in:Tersedia,Dipinjam,Hilang',
        ], [
            'judul.required' => 'Judul buku wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus Tersedia, Dipinjam, atau Hilang',
        ]);

        try {
            // Simpan data buku
            Buku::create($validated);

            return redirect()->route('admin.perpustakaan.index')
                ->with('success', 'Buku berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menyimpan buku: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Show the form for editing book
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        
        return Inertia::render('Admin/Buku/Edit', [
            'user' => Auth::user(),
            'buku' => $buku,
        ]);
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        // Validasi data
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'status' => 'required|in:Tersedia,Dipinjam,Hilang',
        ], [
            'judul.required' => 'Judul buku wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus Tersedia, Dipinjam, atau Hilang',
        ]);

        try {
            $buku->update($validated);

            return redirect()->route('admin.perpustakaan.index')
                ->with('success', 'Buku berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal memperbarui buku: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Remove the specified book
     */
    public function destroy($id)
    {
        try {
            $buku = Buku::withCount('peminjaman')->findOrFail($id);
            
            // Check if book is currently borrowed
            if ($buku->status === 'Dipinjam') {
                return back()->withErrors([
                    'error' => 'Tidak dapat menghapus buku yang sedang dipinjam!'
                ]);
            }

            // Check if book has borrowing history
            if ($buku->peminjaman_count > 0) {
                return back()->withErrors([
                    'error' => 'Tidak dapat menghapus buku yang memiliki riwayat peminjaman! Buku ini pernah dipinjam ' . $buku->peminjaman_count . ' kali. Data riwayat peminjaman harus tetap dijaga untuk keperluan audit.'
                ]);
            }

            $buku->delete();

            return redirect()->route('admin.perpustakaan.index')
                ->with('success', 'Buku berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Gagal menghapus buku: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show circulation form (peminjaman/pengembalian)
     */
    public function sirkulasi()
    {
        // Get available books for borrowing
        $bukuTersedia = Buku::where('status', 'Tersedia')->get();
        
        // Get currently borrowed books for return
        $bukuDipinjam = Buku::where('status', 'Dipinjam')
            ->with(['peminjaman' => function($query) {
                $query->where('status', 'Dipinjam')->with('peminjam');
            }])
            ->get();
        
        // Get all users for peminjam selection
        $users = User::select('id', 'nama', 'email')->get();
        
        return Inertia::render('Admin/Sirkulasi', [
            'user' => Auth::user(),
            'bukuTersedia' => $bukuTersedia,
            'bukuDipinjam' => $bukuDipinjam,
            'users' => $users,
        ]);
    }

    /**
     * Process borrowing (peminjaman)
     */
    public function pinjam(Request $request)
    {
        $validated = $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'peminjam_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
        ], [
            'buku_id.required' => 'Buku wajib dipilih',
            'peminjam_id.required' => 'Peminjam wajib dipilih',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $buku = Buku::findOrFail($validated['buku_id']);
            
            // Check if book is available
            if ($buku->status !== 'Tersedia') {
                return back()->withErrors([
                    'error' => 'Buku tidak tersedia untuk dipinjam!'
                ]);
            }

            // Create peminjaman record
            Peminjaman::create([
                'buku_id' => $validated['buku_id'],
                'peminjam_id' => $validated['peminjam_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'status' => 'Dipinjam',
            ]);

            // Update book status
            $buku->update(['status' => 'Dipinjam']);

            DB::commit();

            return redirect()->route('admin.perpustakaan.sirkulasi')
                ->with('success', 'Peminjaman berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Gagal mencatat peminjaman: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Process return (pengembalian)
     */
    public function kembalikan(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_kembali' => 'required|date',
        ], [
            'peminjaman_id.required' => 'Peminjaman wajib dipilih',
            'tanggal_kembali.required' => 'Tanggal kembali wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('buku')->findOrFail($validated['peminjaman_id']);
            
            // Check if already returned
            if ($peminjaman->status === 'Kembali') {
                return back()->withErrors([
                    'error' => 'Buku sudah dikembalikan sebelumnya!'
                ]);
            }

            // Update peminjaman record
            $peminjaman->update([
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'status' => 'Kembali',
            ]);

            // Update book status back to available
            $peminjaman->buku->update(['status' => 'Tersedia']);

            DB::commit();

            return redirect()->route('admin.perpustakaan.sirkulasi')
                ->with('success', 'Pengembalian berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Gagal mencatat pengembalian: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
