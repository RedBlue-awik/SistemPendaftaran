<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GelombangController;
use App\Http\Controllers\JalurController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/gelombangs', [GelombangController::class, 'index'])->name('gelombangs.index');
    Route::get('/jalurs', [JalurController::class, 'index'])->name('jalurs.index');
    Route::get('/pendaftaran', [\App\Http\Controllers\PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran', [\App\Http\Controllers\PendaftaranController::class, 'store'])->name('pendaftaran.store');

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('users/{user}/approve', [AdminUserController::class, 'approve'])->name('admin.users.approve');
        Route::post('users/{user}/reject', [AdminUserController::class, 'reject'])->name('admin.users.reject');

        Route::get('gelombangs', [GelombangController::class, 'adminIndex'])->name('admin.gelombangs.index');
        Route::post('gelombangs', [GelombangController::class, 'store'])->name('admin.gelombangs.store');
        Route::put('gelombangs/{gelombang}', [GelombangController::class, 'update'])->name('admin.gelombangs.update');
        Route::delete('gelombangs/{gelombang}', [GelombangController::class, 'destroy'])->name('admin.gelombangs.destroy');

        Route::get('jalurs', [JalurController::class, 'adminIndex'])->name('admin.jalurs.index');
        Route::post('jalurs', [JalurController::class, 'store'])->name('admin.jalurs.store');
        Route::put('jalurs/{jalur}', [JalurController::class, 'update'])->name('admin.jalurs.update');
        Route::delete('jalurs/{jalur}', [JalurController::class, 'destroy'])->name('admin.jalurs.destroy');
        
        // admin pendaftaran
        Route::get('pendaftarans', [\App\Http\Controllers\Admin\PendaftaranController::class, 'index'])->name('admin.pendaftars.index');
        Route::get('pendaftarans/{pendaftaran}', [\App\Http\Controllers\Admin\PendaftaranController::class, 'show'])->name('admin.pendaftars.show');
        Route::post('pendaftarans/{pendaftaran}/verify', [\App\Http\Controllers\Admin\PendaftaranController::class, 'verify'])->name('admin.pendaftars.verify');
        Route::post('pendaftarans/{pendaftaran}/select', [\App\Http\Controllers\Admin\PendaftaranController::class, 'setSelection'])->name('admin.pendaftars.select');
        
        Route::post('pendaftarans/{pendaftaran}/confirm-daftar-ulang', [\App\Http\Controllers\Admin\DaftarUlangController::class, 'confirm'])->name('admin.pendaftars.confirm_daftar_ulang');
    });
});
