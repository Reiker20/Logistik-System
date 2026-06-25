#!/bin/sh
set -e

echo "Menunggu database siap..."
MAX_TRIES=30
TRIES=0
until php artisan migrate:status > /dev/null 2>&1; do
  TRIES=$((TRIES+1))
  if [ $TRIES -ge $MAX_TRIES ]; then
    echo "Database tidak bisa dijangkau setelah $MAX_TRIES percobaan. Melanjutkan..."
    break
  fi
  sleep 3
  echo "Mencoba koneksi database ($TRIES/$MAX_TRIES)..."
done

echo "Menjalankan migrasi..."
php artisan key:generate --force --no-interaction 2>/dev/null || true
php artisan config:clear
php artisan migrate --force --no-interaction

echo "Memulai server PHP..."
exec php artisan serve --host=0.0.0.0 --port=8000
