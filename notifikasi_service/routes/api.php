<?php

use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifikasi')->group(function () {
    Route::get('/', [NotifikasiController::class, 'ambilSemua']);
    Route::get('/{id}', [NotifikasiController::class, 'ambilBerdasarkanId']);
    Route::get('/pengguna/{idPengguna}', [NotifikasiController::class, 'ambilBerdasarkanPengguna']);
    Route::get('/pengguna/{idPengguna}/belum-dibaca', [NotifikasiController::class, 'ambilBelumDibaca']);
    Route::post('/', [NotifikasiController::class, 'tambah']);
    Route::patch('/{id}/tandai-baca', [NotifikasiController::class, 'tandaiBaca']);
    Route::patch('/pengguna/{idPengguna}/tandai-semua-baca', [NotifikasiController::class, 'tandaiSemuaBaca']);
    Route::delete('/{id}', [NotifikasiController::class, 'hapus']);
});
