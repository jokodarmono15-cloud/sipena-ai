<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WFAController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', [ChatController::class, 'index'])->name('chat.public');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
        Route::get('/history', [AttendanceController::class, 'history'])->name('attendance.history');
        Route::get('/report', [AttendanceController::class, 'report'])->name('attendance.report');
        Route::post('/{attendance}/approve', [AttendanceController::class, 'approve'])
            ->name('attendance.approve')
            ->middleware('role:kepala_sekolah|admin');
    });

    Route::prefix('wfa')->group(function () {
        Route::get('/', [WFAController::class, 'index'])->name('wfa.index');
        Route::get('/create', [WFAController::class, 'create'])->name('wfa.create');
        Route::post('/', [WFAController::class, 'store'])->name('wfa.store');
        Route::post('/{wfa}/approve', [WFAController::class, 'approve'])
            ->name('wfa.approve')
            ->middleware('role:kepala_sekolah|admin');
    });

    Route::prefix('leave')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('leave.index');
        Route::get('/create', [LeaveController::class, 'create'])->name('leave.create');
        Route::post('/', [LeaveController::class, 'store'])->name('leave.store');
        Route::post('/{leave}/approve', [LeaveController::class, 'approve'])
            ->name('leave.approve')
            ->middleware('role:kepala_sekolah|admin');
    });

    Route::get('/chat-internal', [ChatController::class, 'index'])->name('chat.internal');
    Route::post('/chat-internal/send', [ChatController::class, 'send'])->name('chat.internal.send');
});

require __DIR__.'/auth.php';
