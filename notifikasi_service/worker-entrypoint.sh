#!/bin/sh
set -e

echo "Worker: Menunggu database dan RabbitMQ siap..."
sleep 15

echo "Worker: Menjalankan config clear..."
php artisan config:clear 2>/dev/null || true

echo "Worker: Consumer RabbitMQ dimulai..."
exec php artisan notifikasi:konsumsi
