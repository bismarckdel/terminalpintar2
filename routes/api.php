    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    */

    // Note: Authentication routes moved to web.php (Inertia uses session-based auth)
    // API routes can be added here if needed for external integrations

Route::middleware('auth:sanctum')->group(function () {
    
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // Add your API routes here as needed
    // Example:
    // Route::prefix('admin')->group(function () {
    //     Route::apiResource('users', UserController::class);
    // });

});
