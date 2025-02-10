<?php

use App\Http\Controllers\BarangInventarisController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', [LoginController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {

    Route::group(['prefix' => 'peminjaman'], function () {
        Route::get('/', [PeminjamanController::class, 'index']);
        Route::post('/create', [PeminjamanController::class, 'store']);
    });

    Route::group(['prefix' => 'pengembalian'], function () {
        Route::get('/', [PengembalianController::class, 'index']);
        Route::post('/create', [PengembalianController::class, 'store']);
    });


    Route::put('/peminjaman/update/{id}', [PeminjamanController::class, 'update']);

    Route::group(['prefix' => 'barang-inventaris'], function () {
        Route::get('/', [BarangInventarisController::class, 'index']);
        Route::post('/create', [BarangInventarisController::class, 'store']);
        Route::get('/show/{id}', [BarangInventarisController::class, 'show']);
        Route::put('/update/{id}', [BarangInventarisController::class, 'update']);
        Route::delete('/delete/{id}', [BarangInventarisController::class, 'destroy']);
    });

    Route::group(['prefix' => 'kelas'], function () {
        Route::get('/', [KelasController::class, 'index']);
        Route::post('/create', [KelasController::class, 'store']);
        Route::get('/show/{id}', [KelasController::class, 'show']);
        Route::put('/update/{id}', [KelasController::class, 'update']);
        Route::delete('/delete/{id}', [KelasController::class, 'destroy']);
    });

    Route::group(['prefix' => 'jurusan'], function () {
        Route::get('/', [JurusanController::class, 'index']);
        Route::post('/create', [JurusanController::class, 'store']);
        Route::get('/show/{id}', [JurusanController::class, 'show']);
        Route::put('/update/{id}', [JurusanController::class, 'update']);
        Route::delete('/delete/{id}', [JurusanController::class, 'destroy']);
    });

    Route::group(['prefix' => 'jenis'], function () {
        Route::get('/', [JenisBarangController::class, 'index']);
        Route::post('/create', [JenisBarangController::class, 'store']);
        Route::get('/show/{id}', [JenisBarangController::class, 'show']);
        Route::put('/update/{id}', [JenisBarangController::class, 'update']);
        Route::delete('/delete/{id}', [JenisBarangController::class, 'destroy']);
    });

    Route::group(['prefix' => 'siswa'], function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::post('/create', [SiswaController::class, 'store']);
        Route::get('/show/{id}', [SiswaController::class, 'show']);
        Route::put('/update/{id}', [SiswaController::class, 'update']);
        Route::delete('/delete/{id}', [SiswaController::class, 'destroy']);
    });

    Route::group(['prefix' => 'vendor'], function () {
        Route::get('/', [VendorController::class, 'index']);
        Route::post('/create', [VendorController::class, 'store']);
        Route::get('/show/{id}', [VendorController::class, 'show']);
        Route::put('/update/{id}', [VendorController::class, 'update']);
        Route::delete('/delete/{id}', [VendorController::class, 'destroy']);
    });

});