<?php

use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::prefix('peminjaman')->group(function () {
    Route::get('/', [PeminjamanController::class, 'ambilSemua']);
    Route::get('/{id}', [PeminjamanController::class, 'ambilBerdasarkanId']);
    Route::get('/pengguna/{idPengguna}', [PeminjamanController::class, 'ambilBerdasarkanPengguna']);
    Route::post('/', [PeminjamanController::class, 'tambah']);
    Route::put('/{id}', [PeminjamanController::class, 'perbarui']);
    Route::patch('/{id}/status', [PeminjamanController::class, 'perbaruiStatus']);
    Route::delete('/{id}', [PeminjamanController::class, 'hapus']);
});
