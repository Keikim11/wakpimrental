<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenyewaanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Redirect /home to dashboard
Route::get('/home', function () {
    return redirect('/dashboard');
});

// Authentication Routes
Auth::routes();

// Public Routes (Optional - untuk landing page jika ada)
Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

// Protected Routes - Hanya untuk user yang sudah login
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - bisa diakses semua user yang login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes - bisa diakses semua user yang login
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    });

    // ==================== ROUTES UNTUK ADMIN & KARYAWAN ====================
    
    // Manajemen Mobil - bisa diakses admin & karyawan
    Route::prefix('mobils')->group(function () {
        Route::get('/', [MobilController::class, 'index'])->name('mobils.index');
        Route::get('/create', [MobilController::class, 'create'])->name('mobils.create');
        Route::post('/', [MobilController::class, 'store'])->name('mobils.store');
        Route::get('/{mobil}', [MobilController::class, 'show'])->name('mobils.show');
        Route::get('/{mobil}/edit', [MobilController::class, 'edit'])->name('mobils.edit');
        Route::put('/{mobil}', [MobilController::class, 'update'])->name('mobils.update');
        Route::get('/{id}/status/{status}', [MobilController::class, 'updateStatus'])->name('mobils.status');
        Route::get('/search/available', [MobilController::class, 'searchAvailable'])->name('mobils.search.available');
    });

    // Manajemen Pelanggan - bisa diakses admin & karyawan
    Route::prefix('pelanggans')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('pelanggans.index');
        Route::get('/create', [PelangganController::class, 'create'])->name('pelanggans.create');
        Route::post('/', [PelangganController::class, 'store'])->name('pelanggans.store');
        Route::get('/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggans.show');
        Route::get('/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggans.edit');
        Route::put('/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggans.update');
        Route::get('/{id}/riwayat', [PelangganController::class, 'riwayat'])->name('pelanggans.riwayat');
    });

    // Penyewaan - bisa diakses admin & karyawan
    Route::prefix('penyewaans')->group(function () {
        Route::get('/', [PenyewaanController::class, 'index'])->name('penyewaans.index');
        Route::get('/create', [PenyewaanController::class, 'create'])->name('penyewaans.create');
        Route::get('/create/{mobil_id?}', [PenyewaanController::class, 'create'])->name('penyewaans.create.with_mobil');
        Route::post('/', [PenyewaanController::class, 'store'])->name('penyewaans.store');
        Route::get('/{penyewaan}', [PenyewaanController::class, 'show'])->name('penyewaans.show');
        Route::get('/{penyewaan}/edit', [PenyewaanController::class, 'edit'])->name('penyewaans.edit');
        Route::put('/{penyewaan}', [PenyewaanController::class, 'update'])->name('penyewaans.update');
        Route::get('/{id}/invoice', [PenyewaanController::class, 'invoice'])->name('penyewaans.invoice');
        Route::post('/{id}/selesai', [PenyewaanController::class, 'selesai'])->name('penyewaans.selesai');
        Route::post('/{id}/batal', [PenyewaanController::class, 'batal'])->name('penyewaans.batal');
    });

    // Pengembalian - bisa diakses admin & karyawan
    Route::prefix('pengembalians')->group(function () {
        Route::get('/', [PengembalianController::class, 'index'])->name('pengembalians.index');
        Route::get('/create', [PengembalianController::class, 'create'])->name('pengembalians.create');
        Route::get('/create/{penyewaan_id}', [PengembalianController::class, 'create'])->name('pengembalians.create.with_penyewaan');
        Route::post('/', [PengembalianController::class, 'store'])->name('pengembalians.store');
        Route::get('/{pengembalian}', [PengembalianController::class, 'show'])->name('pengembalians.show');
        Route::get('/{pengembalian}/edit', [PengembalianController::class, 'edit'])->name('pengembalians.edit');
        Route::put('/{pengembalian}', [PengembalianController::class, 'update'])->name('pengembalians.update');
        Route::post('/{id}/hitung-denda', [PengembalianController::class, 'hitungDenda'])->name('pengembalians.hitung_denda');
        // Route untuk riwayat pengembalian
        Route::get('/{pengembalian}/riwayat', [PengembalianController::class, 'riwayat'])->name('pengembalians.riwayat');
    });

    // ==================== ROUTES KHUSUS ADMIN ====================
    Route::middleware(['admin'])->group(function () {
        
        // Hapus data (hanya admin)
        Route::delete('/mobils/{mobil}', [MobilController::class, 'destroy'])->name('mobils.destroy');
        Route::delete('/pelanggans/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggans.destroy');
        Route::delete('/penyewaans/{penyewaan}', [PenyewaanController::class, 'destroy'])->name('penyewaans.destroy');
        Route::delete('/pengembalians/{pengembalian}', [PengembalianController::class, 'destroy'])->name('pengembalians.destroy');

        // Manajemen User (hanya admin)
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
            Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::get('/{user}/reset-password', [UserController::class, 'showResetPasswordForm'])->name('users.reset-password');
            Route::put('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password.update');
        });

        // Laporan (hanya admin)
        Route::prefix('laporan')->group(function () {
            Route::get('/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
            Route::get('/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
            Route::get('/tahunan', [LaporanController::class, 'tahunan'])->name('laporan.tahunan');
            Route::get('/mobil', [LaporanController::class, 'mobil'])->name('laporan.mobil');
            Route::get('/pelanggan', [LaporanController::class, 'pelanggan'])->name('laporan.pelanggan');
            
            // Export Laporan (hanya admin)
            Route::get('/export/harian', [LaporanController::class, 'exportHarian'])->name('laporan.export.harian');
            Route::get('/export/bulanan', [LaporanController::class, 'exportBulanan'])->name('laporan.export.bulanan');
            Route::get('/export/tahunan', [LaporanController::class, 'exportTahunan'])->name('laporan.export.tahunan');
            Route::get('/export/mobil', [LaporanController::class, 'exportMobil'])->name('laporan.export.mobil');
            Route::get('/export/pelanggan', [LaporanController::class, 'exportPelanggan'])->name('laporan.export.pelanggan');
        });
    });

    // ==================== API ROUTES UNTUK AJAX ====================
    Route::prefix('api')->group(function () {
        Route::get('/mobil-tersedia', [MobilController::class, 'getMobilTersedia'])->name('api.mobil.tersedia');
        Route::get('/tarif-mobil/{id}', [MobilController::class, 'getTarifMobil'])->name('api.mobil.tarif');
        Route::get('/cek-pelanggan/{email}', [PelangganController::class, 'cekPelanggan'])->name('api.pelanggan.cek');
        Route::get('/penyewaan-aktif', [PenyewaanController::class, 'getPenyewaanAktif'])->name('api.penyewaan.aktif');
        Route::post('/calculate-total', [PenyewaanController::class, 'calculateTotal'])->name('api.penyewaan.calculate');
        Route::get('/pelanggans/search', [PelangganController::class, 'search'])->name('api.pelanggan.search');
    });

});

// Fallback Route - untuk handle 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});