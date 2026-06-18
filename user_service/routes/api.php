<?php

use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

Route::prefix('pengguna')->group(function () {
    Route::get('/', [PenggunaController::class, 'ambilSemua']);
    Route::get('/{id}', [PenggunaController::class, 'ambilBerdasarkanId']);
    Route::post('/', [PenggunaController::class, 'tambah']);
    Route::put('/{id}', [PenggunaController::class, 'perbarui']);
    Route::delete('/{id}', [PenggunaController::class, 'hapus']);
});
