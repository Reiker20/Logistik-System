<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_peminjaman')->nullable();
            $table->string('judul');
            $table->text('isi_pesan');
            $table->enum('jenis_notifikasi', [
                'peminjaman_baru',
                'status_berubah',
                'pengingat_pengembalian',
                'umum',
            ])->default('umum');
            $table->boolean('status_baca')->default(false);
            $table->timestamps();

            $table->index('id_pengguna');
            $table->index('status_baca');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
