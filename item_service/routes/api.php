<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

Route::prefix('barang')->group(function () {
    Route::get('/', [BarangController::class, 'ambilSemua']);
    Route::get('/{id}', [BarangController::class, 'ambilBerdasarkanId']);
    Route::post('/', [BarangController::class, 'tambah']);
    Route::put('/{id}', [BarangController::class, 'perbarui']);
    Route::delete('/{id}', [BarangController::class, 'hapus']);
    Route::patch('/{id}/kurangi-stok', [BarangController::class, 'kurangiStok']);
    Route::patch('/{id}/tambah-stok', [BarangController::class, 'tambahStok']);
});
