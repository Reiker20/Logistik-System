<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQProducer
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

    public function kirimPesan(string $namaAntrian, array $dataPesan): void
    {
        $this->kanal->queue_declare($namaAntrian, false, true, false, false);

        $isiPesan = json_encode($dataPesan);
        $pesan = new AMQPMessage($isiPesan, [
            'content_type' => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $this->kanal->basic_publish($pesan, '', $namaAntrian);
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
