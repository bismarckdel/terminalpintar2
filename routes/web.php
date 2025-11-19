<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerpustakaanController;

// --- Rute Publik ---

// Render Landing Page secara statis tanpa controller/props
Route::get('/', function () {
    return Inertia::render('LandingPage');
})->name('landing');

// Halaman Login
Route::get('/login', function () {
    return Inertia::render('Auth/LoginPage');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

// --- Rute Admin (Contoh Group) ---
// Batasi hanya untuk authenticated users dengan role 'admin'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Kelola Pengguna - Resource Routes
    Route::get('/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::post('/user/{id}/update', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
    
    // Kelola Siswa - Resource Routes
    Route::get('/siswa', [SiswaController::class, 'index'])->name('admin.siswa.index');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('admin.siswa.create');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('admin.siswa.store');
    Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('admin.siswa.edit');
    Route::post('/siswa/{id}/update', [SiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');
    
    // Kelola Berita - Resource Routes
    Route::get('/berita', [BeritaController::class, 'index'])->name('admin.berita.index');
    Route::get('/berita/create', [BeritaController::class, 'create'])->name('admin.berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('admin.berita.store');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit');
    Route::post('/berita/{id}/update', [BeritaController::class, 'update'])->name('admin.berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');
    
    // Kelola Perpustakaan - Resource Routes
    Route::get('/perpustakaan', [PerpustakaanController::class, 'index'])->name('admin.perpustakaan.index');
    Route::get('/perpustakaan/create', [PerpustakaanController::class, 'create'])->name('admin.perpustakaan.create');
    Route::post('/perpustakaan', [PerpustakaanController::class, 'store'])->name('admin.perpustakaan.store');
    Route::get('/perpustakaan/{id}/edit', [PerpustakaanController::class, 'edit'])->name('admin.perpustakaan.edit');
    Route::post('/perpustakaan/{id}/update', [PerpustakaanController::class, 'update'])->name('admin.perpustakaan.update');
    Route::delete('/perpustakaan/{id}', [PerpustakaanController::class, 'destroy'])->name('admin.perpustakaan.destroy');
    
    // Sirkulasi (Peminjaman & Pengembalian)
    Route::get('/perpustakaan/sirkulasi', [PerpustakaanController::class, 'sirkulasi'])->name('admin.perpustakaan.sirkulasi');
    Route::post('/perpustakaan/pinjam', [PerpustakaanController::class, 'pinjam'])->name('admin.perpustakaan.pinjam');
    Route::post('/perpustakaan/kembalikan', [PerpustakaanController::class, 'kembalikan'])->name('admin.perpustakaan.kembalikan');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboardadmin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});