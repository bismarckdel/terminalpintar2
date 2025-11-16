<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita; 
use App\Models\User;   

class LandingPageController extends Controller
{
    /**
     * Menyediakan data dinamis untuk landing page.
     */
    public function getData(Request $request)
    {
        // Ambil 3 berita/kegiatan terbaru
        // dari tabel 'berita' menggunakan Model Berita
        $kegiatan = Berita::latest()->take(3)->get();

        // Ambil data pengurus/guru (cth: role 'Admin' dan 'Guru')
        // dari tabel 'users' menggunakan Model User
        $pengurus = User::whereHas('role', function ($query) {
            $query->whereIn('nama_role', ['Admin', 'Guru']);
        })->take(3)->get();

        
        // Kembalikan data sebagai JSON
        return response()->json([
            'kegiatan' => $kegiatan,
            'pengurus' => $pengurus,
        ]);
    }
}