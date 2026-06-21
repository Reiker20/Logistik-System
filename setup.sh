#!/bin/bash

echo "=== Setup Proyek Sistem Peminjaman Logistik ==="
echo ""

SERVICES=("user_service" "item_service" "peminjaman_service" "notifikasi_service")
NAMES=("Fitria Herista Zulvadhl" "Reika Elvaretta" "Anata Budi Ayuni" "neshaolivia")
EMAILS=("fitriaherista@gmail.com" "reikaelvaretta@gmail.com" "anataayn@gmail.com" "neshapramitha32@gmail.com")

for i in "${!SERVICES[@]}"; do
    svc="${SERVICES[$i]}"
    nama="${NAMES[$i]}"
    email="${EMAILS[$i]}"

    echo "--- Inisialisasi $svc ---"

    cd "$svc"

    if [ ! -d ".git" ]; then
        git init
    fi

    git config user.name "$nama"
    git config user.email "$email"

    if [ ! -f ".env" ]; then
        cp .env.example .env
    fi

    composer install --no-interaction

    php artisan key:generate --force

    echo "$svc selesai diinisialisasi."
    echo ""

    cd ..
done

echo "=== Menjalankan Docker Compose ==="
docker-compose up -d --build

echo ""
echo "=== Menjalankan Migrasi Database ==="

sleep 10

for svc in "${SERVICES[@]}"; do
    echo "Migrasi $svc..."
    docker-compose exec "$svc" php artisan migrate --force
done

echo ""
echo "=== Menjalankan Consumer Notifikasi ==="
docker-compose exec -d notifikasi_service php artisan notifikasi:konsumsi

echo ""
echo "=== Setup Selesai ==="
echo ""
echo "Akses service:"
echo "  User Service     : http://localhost:8001/api/pengguna"
echo "  Item Service     : http://localhost:8002/api/barang"
echo "  Peminjaman Service: http://localhost:8003/api/peminjaman"
echo "  Notifikasi Service: http://localhost:8004/api/notifikasi"
echo "  GraphQL (masing2) : http://localhost:800X/graphql"
echo "  Hasura Console    : http://localhost:8080"
echo "  RabbitMQ Dashboard: http://localhost:15672 (admin/rahasia123)"
