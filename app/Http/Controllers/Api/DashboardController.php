<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mendapatkan statistik dashboard admin
     */
    public function getStats()
    {
        $totalSiswa = Student::count() ?? 128;
        $siswaBaru = Student::where('created_at', '>=', now()->subMonth())->count() ?? 12;
        
        $kelasAktif = 8; // Sesuaikan dengan logic Anda jika ada model Class
        $kategoriKelas = 3;
        
        $artikelPublished = Berita::count() ?? 45;
        $artikelBaru = Berita::where('created_at', '>=', now()->subWeek())->count() ?? 5;
        
        $koleksiBuku = 245; // Sesuaikan dengan logic Anda jika ada model Book
        $kategoriKeb = 15;

        return response()->json([
            'totalSiswa' => $totalSiswa,
            'siswaBaru' => $siswaBaru,
            'kelasAktif' => $kelasAktif,
            'kategoriKelas' => $kategoriKelas,
            'artikelPublished' => $artikelPublished,
            'artikelBaru' => $artikelBaru,
            'koleksiBuku' => $koleksiBuku,
            'kategoriKeb' => $kategoriKeb
        ]);
    }

    /**
     * Mendapatkan aktivitas terbaru
     */
    public function getAktivitasTerbaru()
    {
        $aktivitas = [];

        // Siswa baru
        $siswaBaru = Student::latest()->limit(1)->first();
        if ($siswaBaru) {
            $aktivitas[] = [
                'id' => 'siswa-' . $siswaBaru->id,
                'icon' => '👤',
                'deskripsi' => 'Siswa baru: ' . ($siswaBaru->nama ?? 'Siswa Baru') . ' mendaftar',
                'waktu' => $this->timeAgo($siswaBaru->created_at)
            ];
        }

        // Berita/Artikel terbaru
        $beritaTerbaru = Berita::latest()->limit(1)->first();
        if ($beritaTerbaru) {
            $aktivitas[] = [
                'id' => 'berita-' . $beritaTerbaru->id,
                'icon' => '📰',
                'deskripsi' => 'Artikel "' . $beritaTerbaru->judul . '" telah dipubilish',
                'waktu' => $this->timeAgo($beritaTerbaru->created_at)
            ];
        }

        // Guru/User baru
        $userBaru = User::latest()->limit(1)->first();
        if ($userBaru) {
            $aktivitas[] = [
                'id' => 'user-' . $userBaru->id,
                'icon' => '🧑‍🏫',
                'deskripsi' => 'Pengguna baru: ' . ($userBaru->name ?? 'User Baru') . ' bergabung',
                'waktu' => $this->timeAgo($userBaru->created_at)
            ];
        }

        // Default aktivitas jika tidak ada data
        if (empty($aktivitas)) {
            $aktivitas = [
                [
                    'id' => 1,
                    'icon' => '📌',
                    'deskripsi' => 'Tidak ada aktivitas terbaru',
                    'waktu' => 'Baru-baru ini'
                ]
            ];
        }

        return response()->json($aktivitas);
    }

    /**
     * Helper function untuk menghitung waktu yang lalu
     */
    private function timeAgo($datetime)
    {
        $now = now();
        $diff = $now->diffInSeconds($datetime);

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = $now->diffInMinutes($datetime);
            return $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = $now->diffInHours($datetime);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 604800) {
            $days = $now->diffInDays($datetime);
            return $days . ' hari yang lalu';
        } else {
            return $datetime->format('d M Y');
        }
    }
}
