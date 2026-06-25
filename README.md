# Sistem Peminjaman Logistik Kampus - Microservices

## Arsitektur Sistem
| Service | Port | Database | Deskripsi |
|---------|------|----------|-----------|
| User Service | 8001 | db_user (PostgreSQL) | CRUD data pengguna + GraphQL Lighthouse |
| Item Service | 8002 | db_item (PostgreSQL) | CRUD data barang logistik |
| Peminjaman Service | 8003 | db_peminjaman (PostgreSQL) | Transaksi peminjaman + RabbitMQ Producer |
| Notifikasi Service | 8004 | db_notifikasi (PostgreSQL) | RabbitMQ Consumer + simpan notifikasi |
| RabbitMQ | 15672 | - | Message Broker (UI: admin/rahasia123) |
| Hasura GraphQL | 8080 | - | GraphQL Engine (admin secret: rahasiahasura) |

## Cara Menjalankan (Windows/Mac/Linux)

### Prasyarat
- Docker Desktop terinstall dan berjalan
- Port 5431-5434, 5672, 8001-8004, 8080, 15672 tidak digunakan

### Langkah
```bash
# 1. Clone/extract project
cd tugas_iae_logistik

# 2. Build dan jalankan semua container
docker-compose up -d --build

# 3. Tunggu sekitar 30-60 detik agar semua service siap
# Cek status:
docker-compose ps
```

Semua service akan otomatis:
- Menunggu database siap (healthcheck)
- Menjalankan migrasi database
- Memulai server Laravel
- Notifikasi service otomatis menjalankan RabbitMQ consumer

### Verifikasi
```bash
# User Service
curl http://localhost:8001/api/pengguna

# Item Service
curl http://localhost:8002/api/barang

# Peminjaman Service
curl http://localhost:8003/api/peminjaman

# Notifikasi Service
curl http://localhost:8004/api/notifikasi/pengguna/1

# Hasura GraphQL Console
# Buka browser: http://localhost:8080 (password: rahasiahasura)

# RabbitMQ Management
# Buka browser: http://localhost:15672 (admin/rahasia123)
```

## Flow Integrasi
1. POST /api/pengguna -> membuat data pengguna
2. POST /api/barang -> membuat data barang
3. POST /api/peminjaman -> membuat peminjaman + kirim pesan ke RabbitMQ
4. Consumer di notifikasi_service otomatis menerima pesan dan menyimpan notifikasi
5. GET /api/notifikasi/pengguna/{id} -> melihat notifikasi yang masuk

## Setup Hasura (Setelah Container Jalan)
1. Buka http://localhost:8080
2. Masukkan admin secret: rahasiahasura
3. Data > Add Database > Connect existing database
4. Tambahkan 4 database:
   - db_user: postgres://user_admin:rahasia123@db_user:5432/db_user
   - db_item: postgres://item_admin:rahasia123@db_item:5432/db_item
   - db_peminjaman: postgres://peminjaman_admin:rahasia123@db_peminjaman:5432/db_peminjaman
   - db_notifikasi: postgres://notifikasi_admin:rahasia123@db_notifikasi:5432/db_notifikasi
5. Track semua tabel di masing-masing database

## Teknologi
- Framework: Laravel 10
- Database: PostgreSQL 15
- Message Broker: RabbitMQ 3
- GraphQL: Hasura v2.36 + Lighthouse (Laravel)
- Container: Docker + Docker Compose
