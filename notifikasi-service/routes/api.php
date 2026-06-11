<?php

use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('notifikasi', [NotifikasiController::class, 'index']);
Route::get('notifikasi/{id}', [NotifikasiController::class, 'show']);
Route::delete('notifikasi/{id}', [NotifikasiController::class, 'destroy']);

