<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Siswa;
use App\Models\Berita;
use App\Models\Buku;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        // Get real statistics from database
        $totalSiswa = Siswa::count();
        $totalBerita = Berita::count();
        $koleksiBuku = Buku::count();

        return Inertia::render('Admin/DashboardAdmin', [
            'user' => Auth::user(),
            'stats' => [
                'totalSiswa' => $totalSiswa,
                'totalBerita' => $totalBerita,
                'koleksiBuku' => $koleksiBuku,
                'totalUsers' => \App\Models\User::count()
            ],
            'daftarAktivitas' => [
                [
                    'id' => 1,
                    'icon' => '👤',
                    'deskripsi' => '5 Siswa baru mendaftar hari ini',
                    'waktu' => '2 jam yang lalu'
                ],
                [
                    'id' => 2,
                    'icon' => '📰',
                    'deskripsi' => 'Artikel "Workshop Robotika" telah dipubilish',
                    'waktu' => '4 jam yang lalu'
                ],
                [
                    'id' => 3,
                    'icon' => '🧑‍🏫',
                    'deskripsi' => '2 guru baru bergabung',
                    'waktu' => '1 hari yang lalu'
                ],
                [
                    'id' => 4,
                    'icon' => '📚',
                    'deskripsi' => '15 buku baru ditambahkan ke perpustakaan',
                    'waktu' => '3 hari yang lalu'
                ]
            ]
        ]);
    }
}
