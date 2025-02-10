<?php

use App\Http\Controllers\BarangInventarisController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\VendorController;
use App\Models\Vendor;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => 'web'], function () {
    Route::get('/dashboard', [LoginController::class, 'view'])->name('dashboard');
    Route::group(['prefix' => 'barang-inventaris'], function () {
        Route::get('/', [BarangInventarisController::class, 'index'])->name('barang-inventaris');
        Route::post('/create', [BarangInventarisController::class, 'store'])->name('barang-inventaris.create');
        Route::post('/{id}', [BarangInventarisController::class, 'update'])->name('barang-inventaris.update');
    });

    Route::get('/search-barang', [BarangInventarisController::class, 'search'])->name('search.barang');
    Route::get('/search-siswa', [SiswaController::class, 'search'])->name('search.siswa');
    
    Route::group(['prefix' => 'pinjaman'], function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('pinjaman');
        Route::post('/create', [PeminjamanController::class, 'store'])->name('peminjaman.create');
        Route::get('/store', [PeminjamanController::class, 'create'])->name('peminjaman.store');
    });

    Route::group(['prefix' => 'pengembalian'], function () {
        Route::get('/', [PengembalianController::class, 'index'])->name('pengembalian');
        Route::post('/store', [PengembalianController::class, 'store'])->name('pengembalian.store');
    });

    Route::group(['prefix' => 'jenis-barang'], function () {
        Route::get('/', [JenisBarangController::class, 'index'])->name('jenis-barang');
        Route::post('/create', [JenisBarangController::class, 'store'])->name('jenis-barang.create');
        Route::post('/{id}', [JenisBarangController::class, 'update'])->name('jenis-barang.update');
        Route::delete('/{id}', [JenisBarangController::class, 'destroy'])->name('jenis-barang.delete');
    });

    
    Route::group(['prefix' => 'asal'], function() {
        Route::get('/', [VendorController::class, 'index'])->name('vendor');
        Route::post('/create', [VendorController::class, 'store'])->name('vendor.create');
        Route::post('/{id}', [VendorController::class, 'update'])->name('vendor.update');
        Route::delete('/{id}', [VendorController::class, 'destroy'])->name('vendor.delete');
    });
    Route::group(['prefix' => 'laporan'], function() {
        Route::get('/pdf/barang', [LaporanController::class, 'generatePDF'])->name('pdf.laporan.barang');
        Route::get('/pdf/peminjaman', [LaporanController::class, 'generatePDFPinjaman'])->name('pdf.laporan.peminjaman');
        Route::get('/pdf/pengembalian', [LaporanController::class, 'generatePDFPengembalian'])->name('pdf.laporan.pengembalian');

        Route::get('/barang', [LaporanController::class, 'index'])->name('laporan.barang');
        Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
        Route::get('/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');
    });     
});
