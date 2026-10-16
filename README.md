# SiKuliner Riau

Repository Monorepo Sistem Informasi Pusat Kuliner Khas dan Direktori Toko Makanan Daerah Provinsi Riau.

Proyek ini telah dipisahkan menjadi dua folder utama:
- [`/frontend`](./frontend/): Kode antarmuka (Front-End) berbasis template PHP modular, CSS vanilla, JavaScript, aset gambar, dan pratinjau standalone.
- [`/backend`](./backend/): Kode arsitektur server (Back-End) berbasis **Laravel 12**, dilengkapi model, migrasi database, seeder, form request validation, authentication, middleware RBAC, CRUD panel admin, serta automated testing.

---

## 📂 Struktur Folder Proyek

```
Sikuliner-riau/
├── frontend/               # Front-End UI & Preview
│   ├── app/                # Frontend presenter & helper
│   ├── docs/               # Panduan integrasi UI
│   ├── preview/            # Router & server standalone
│   ├── public/             # CSS, JS, dan aset ilustrasi
│   ├── resources/views/    # Template halaman publik & admin
│   └── tests/              # Playwright E2E UI tests
│
└── backend/                # Back-End Framework (Laravel 12)
    ├── app/                # Controllers, Models, Middleware, Rules
    ├── config/             # Konfigurasi aplikasi & UI adapter
    ├── database/           # Migrations, Factories, Seeders
    ├── docs/               # Dokumentasi SPRINT-1.md & SPRINT-2.md
    ├── routes/             # web.php & console.php
    ├── storage/            # File upload & logs
    └── tests/              # Feature & Unit tests (64 passed tests)
```

---

## 🚀 Panduan Menjalankan

### A. Menjalankan Back-End (Laravel 12)

1. Masuk ke folder backend:
   ```powershell
   cd backend
   ```
2. Salin environment dan siapkan key (jika baru pertama kali):
   ```powershell
   cp .env.example .env
   php artisan key:generate
   ```
3. Pastikan MySQL di XAMPP telah menyala, lalu jalankan migrasi dan seeder:
   ```powershell
   php artisan migrate
   php artisan db:seed
   ```
4. Jalankan server lokal:
   ```powershell
   php artisan serve
   ```
   Akses aplikasi di browser melalui: **http://127.0.0.1:8000**

5. Jalankan unit test:
   ```powershell
   php artisan test
   ```

---

### B. Menjalankan Front-End (Standalone Preview)

1. Masuk ke folder frontend:
   ```powershell
   cd frontend
   ```
2. Jalankan PHP built-in server:
   ```powershell
   & 'C:\xampp\php\php.exe' -S 127.0.0.1:8080 -t public preview/router.php
   ```
   Buka di browser: **http://127.0.0.1:8080**
