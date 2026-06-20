<?php

namespace App\Services;

use App\Models\Notifikasi;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConsumer
{
    private AMQPStreamConnection $koneksi;
    private $kanal;

    public function __construct()
    {
        $this->koneksi = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'admin'),
            env('RABBITMQ_PASSWORD', 'rahasia123')
        );
        $this->kanal = $this->koneksi->channel();
    }

    public function konsumsiPesan(string $namaAntrian): void
    {
        $this->kanal->queue_declare($namaAntrian, false, true, false, false);

        echo "Consumer notifikasi dimulai. Menunggu pesan..." . PHP_EOL;

        $this->kanal->basic_consume(
            $namaAntrian,
            '',
            false,
            false,
            false,
            false,
            function ($pesanMasuk) {
                try {
                    echo 'Pesan diterima: ' . $pesanMasuk->body . PHP_EOL;
                    $dataPesan = json_decode($pesanMasuk->body, true);
                    $this->prosesNotifikasi($dataPesan);
                    $pesanMasuk->ack();
                    echo 'Notifikasi berhasil disimpan.' . PHP_EOL;
                } catch (\Exception $e) {
                    echo 'ERROR menyimpan notifikasi: ' . $e->getMessage() . PHP_EOL;
                    $pesanMasuk->ack();
                }
            }
        );

        while ($this->kanal->is_consuming()) {
            $this->kanal->wait();
        }
    }

    private function prosesNotifikasi(array $dataPesan): void
    {
        $kejadian = $dataPesan['kejadian'] ?? 'umum';

        $judulNotifikasi = match ($kejadian) {
            'peminjaman_baru' => 'Peminjaman Baru Dibuat',
            'status_berubah' => 'Status Peminjaman Diperbarui',
            default => 'Notifikasi Sistem',
        };

        $isiPesan = match ($kejadian) {
            'peminjaman_baru' => sprintf(
                'Peminjaman baru dengan ID #%d telah dibuat. Jumlah pinjam: %d. Tanggal pinjam: %s s/d %s.',
                $dataPesan['id_peminjaman'] ?? 0,
                $dataPesan['jumlah_pinjam'] ?? 0,
                $dataPesan['tanggal_pinjam'] ?? '-',
                $dataPesan['tanggal_kembali'] ?? '-'
            ),
            'status_berubah' => sprintf(
                'Status peminjaman #%d berubah dari "%s" menjadi "%s".',
                $dataPesan['id_peminjaman'] ?? 0,
                $dataPesan['status_sebelumnya'] ?? '-',
                $dataPesan['status_baru'] ?? '-'
            ),
            default => json_encode($dataPesan),
        };

        Notifikasi::create([
            'id_pengguna' => $dataPesan['id_pengguna'] ?? 0,
            'id_peminjaman' => $dataPesan['id_peminjaman'] ?? null,
            'judul' => $judulNotifikasi,
            'isi_pesan' => $isiPesan,
            'jenis_notifikasi' => $kejadian,
            'status_baca' => false,
        ]);
    }

    public function __destruct()
    {
        if (isset($this->kanal) && $this->kanal) {
            $this->kanal->close();
        }
        if (isset($this->koneksi) && $this->koneksi) {
            $this->koneksi->close();
        }
    }
}
