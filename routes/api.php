    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardOrangTuaController; // <--- Pastikan di-import
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BeritaController;
    use App\Http\Controllers\Api\LandingPageController; // Impor controller

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    */

    // Tambahkan route ini
    Route::get('/landing-data', [LandingPageController::class, 'getData']);

// --- RUTE PUBLIC (UNTUK TESTING) ---
// Pastikan baris ini ada dan TIDAK dikomentari
Route::get('/dashboard-orang-tua', [DashboardOrangTuaController::class, 'index']);
Route::get('/kegiatan-terbaru', [BeritaController::class, 'getKegiatanTerbaru']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Rute Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Rute Dashboard Orang Tua
    // (Kita kembalikan ke dalam middleware agar aman)
    Route::get('/dashboard-orang-tua', [DashboardOrangTuaController::class, 'index']);

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin API endpoints (basic list & create)
    use App\Http\Controllers\Api\StudentController;
    use App\Http\Controllers\Api\BeritaController;
    use App\Http\Controllers\Api\UserController;
    use App\Http\Controllers\Api\DashboardController;

    Route::get('/admin/students', [StudentController::class, 'index']);
    Route::post('/admin/students', [StudentController::class, 'store']);
    Route::get('/admin/students/stats', [StudentController::class, 'getStats']);

    Route::prefix('admin')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/stats', [UserController::class, 'stats']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});

    Route::get('/admin/berita', [BeritaController::class, 'index']);
    Route::post('/admin/berita', [BeritaController::class, 'store']);
    Route::get('/admin/berita/stats', [BeritaController::class, 'getStats']);    
    Route::delete('/admin/berita/{id}', [BeritaController::class, 'destroy']);

    // Route::get('/admin/users', [UserController::class, 'index']);
    // Route::post('/admin/users', [UserController::class, 'store']);

    // Dashboard endpoints
    Route::get('/admin/dashboard-stats', [DashboardController::class, 'getStats']);
    Route::get('/admin/aktivitas-terbaru', [DashboardController::class, 'getAktivitasTerbaru']);
    
    Route::get('/catatan-guru-lengkap', [DashboardOrangTuaController::class, 'getAllCatatan']);
    Route::get('/jadwal-lengkap', [DashboardOrangTuaController::class, 'getAllJadwal']); // <--- INI YANG PENTING
});
