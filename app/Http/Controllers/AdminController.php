<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        return Inertia::render('Admin/DashboardAdmin', [
            'user' => Auth::user(),
            'stats' => [
                'totalSiswa' => 128,
                'siswaBaru' => 12,
                'kelasAktif' => 8,
                'kategoriKelas' => 3,
                'artikelPublished' => 45,
                'artikelBaru' => 5,
                'koleksiBuku' => 245,
                'kategoriKeb' => 15
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
