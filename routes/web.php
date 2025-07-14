<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SignaController;
use App\Http\Controllers\ObatalkesController;
use App\Http\Controllers\ResepController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Email verification (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi!');
    })->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link verifikasi baru telah dikirim!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Register routes
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);

// Protected routes (auth required)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Prescription routes
    Route::resource('resep', ResepController::class);
    Route::post('/resep/{resep}/approve', [ResepController::class, 'approve'])->name('resep.approve');
    Route::post('/resep/{resep}/reject', [ResepController::class, 'reject'])->name('resep.reject');
    Route::post('/resep/{resep}/receive', [ResepController::class, 'receive'])->name('resep.receive');
    Route::post('/resep/{resep}/complete', [ResepController::class, 'complete'])->name('resep.complete');
    Route::get('/resep/{resep}/pdf', [ResepController::class, 'pdf'])->name('resep.pdf');
    Route::get('/resep-processing', [ResepController::class, 'processing'])->name('resep.processing');
    Route::get('/resep-completed', [ResepController::class, 'completed'])->name('resep.completed');
    Route::get('/resep-export', [ResepController::class, 'exportExcel'])->name('resep.export');

    // API routes for AJAX
    Route::get('/api/obatalkes', [ResepController::class, 'getObatalkes'])->name('api.obatalkes');
    Route::get('/api/signa', [ResepController::class, 'getSigna'])->name('api.signa');

    // Master data routes (restricted to admin, dokter, apoteker)
    Route::resource('obatalkes', ObatalkesController::class);
    Route::resource('signa', SignaController::class);

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});
