<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_barang');
            $table->integer('jumlah_pinjam')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->date('tanggal_dikembalikan')->nullable();
            $table->enum('status_transaksi', [
                'menunggu',
                'disetujui',
                'dipinjam',
                'dikembalikan',
                'ditolak',
                'terlambat',
            ])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('id_pengguna');
            $table->index('id_barang');
            $table->index('status_transaksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
