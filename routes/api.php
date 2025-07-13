<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SignaController;
use App\Http\Controllers\ObatalkesController;
use App\Http\Controllers\ResepController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });
    
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'apiIndex']);
    
    // Master Data Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        // Signa API
        Route::get('/signa', [SignaController::class, 'apiIndex']);
        Route::post('/signa', [SignaController::class, 'apiStore']);
        Route::get('/signa/{signa}', [SignaController::class, 'apiShow']);
        Route::put('/signa/{signa}', [SignaController::class, 'apiUpdate']);
        Route::delete('/signa/{signa}', [SignaController::class, 'apiDestroy']);
        
        // Obatalkes API
        Route::get('/obatalkes', [ObatalkesController::class, 'apiIndex']);
        Route::post('/obatalkes', [ObatalkesController::class, 'apiStore']);
        Route::get('/obatalkes/{obatalkes}', [ObatalkesController::class, 'apiShow']);
        Route::put('/obatalkes/{obatalkes}', [ObatalkesController::class, 'apiUpdate']);
        Route::delete('/obatalkes/{obatalkes}', [ObatalkesController::class, 'apiDestroy']);
    });
    
    // Prescription Management
    Route::middleware(['can.create.prescriptions'])->group(function () {
        Route::post('/resep', [ResepController::class, 'apiStore']);
    });
    
    Route::middleware(['can.view.prescriptions'])->group(function () {
        Route::get('/resep', [ResepController::class, 'apiIndex']);
        Route::get('/resep/{resep}', [ResepController::class, 'apiShow']);
        Route::get('/resep/{resep}/pdf', [ResepController::class, 'apiPdf']);
    });
    
    Route::middleware(['can.edit.prescriptions'])->group(function () {
        Route::put('/resep/{resep}', [ResepController::class, 'apiUpdate']);
    });
    
    Route::middleware(['can.delete.prescriptions'])->group(function () {
        Route::delete('/resep/{resep}', [ResepController::class, 'apiDestroy']);
    });
    
    // Approval Routes (Admin and Dokter only)
    Route::middleware(['can.approve.prescriptions'])->group(function () {
        Route::post('/resep/{resep}/approve', [ResepController::class, 'apiApprove']);
        Route::post('/resep/{resep}/reject', [ResepController::class, 'apiReject']);
    });
    
    // Receive Routes (Apoteker only)
    Route::middleware(['can.receive.prescriptions'])->group(function () {
        Route::post('/resep/{resep}/receive', [ResepController::class, 'apiReceive']);
    });
    
    // AJAX Routes
    Route::get('/obatalkes', [ResepController::class, 'getObatalkes']);
    Route::get('/signa', [ResepController::class, 'getSigna']);
});

// Role-based test routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/test/admin', function () {
        return response()->json(['message' => 'Admin access granted']);
    })->middleware('role:admin');
    
    Route::get('/test/dokter', function () {
        return response()->json(['message' => 'Dokter access granted']);
    })->middleware('role:dokter');
    
    Route::get('/test/apoteker', function () {
        return response()->json(['message' => 'Apoteker access granted']);
    })->middleware('role:apoteker');
    
    Route::get('/test/pasien', function () {
        return response()->json(['message' => 'Pasien access granted']);
    })->middleware('role:pasien');
}); 