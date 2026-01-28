# Rental PS Microservices

Project ini dibuat sebagai tugas UAS mata kuliah Backend Development.
Aplikasi ini merupakan aplikasi Rental PlayStation yang dibangun dengan konsep microservices.

## Deskripsi Singkat

Aplikasi Rental PlayStation terdiri dari beberapa komponen utama, yaitu frontend, backend service, database, dan message broker.
Backend service dipisahkan ke dalam beberapa layanan untuk mengelola user dan transaksi rental.
Frontend dibuat menggunakan Laravel agar aplikasi dapat langsung digunakan dan diuji.

## Teknologi yang Digunakan

- Laravel (Frontend)
- NestJS (Backend Service)
- MySQL (Database)
- RabbitMQ (Message Broker)
- Docker & Docker Compose
- GitHub Actions (CI/CD)

## Struktur Project

Project ini menggunakan struktur Laravel sebagai dasar utama, dengan tambahan folder services untuk logic layanan backend.
Seluruh service dijalankan menggunakan Docker Compose agar lebih mudah saat setup dan pengujian.

## Cara Menjalankan Aplikasi

1. Pastikan Docker dan Docker Compose sudah terinstall
2. Clone repository ini
3. Jalankan perintah:
   ```bash
   docker-compose up -d

