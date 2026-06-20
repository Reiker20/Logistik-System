<?php

namespace App\Console\Commands;

use App\Services\RabbitMQConsumer;
use Illuminate\Console\Command;

class KonsumNotifikasiCommand extends Command
{
    protected $signature = 'notifikasi:konsumsi';

    protected $description = 'Menjalankan consumer RabbitMQ untuk menerima notifikasi peminjaman';

    public function handle(): int
    {
        $this->info('Consumer notifikasi dimulai. Menunggu pesan...');

        $consumer = new RabbitMQConsumer();
        $namaAntrian = env('RABBITMQ_QUEUE', 'antrian_peminjaman');
        $consumer->konsumsiPesan($namaAntrian);

        return Command::SUCCESS;
    }
}
