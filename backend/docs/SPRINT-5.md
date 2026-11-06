# Sprint 5 SiKuliner Riau — Fitur Ulasan Pengunjung & Dashboard Ringkasan Sistem

## Hasil dan Cakupan Sprint 5

Pada Sprint 5, fitur rating & review komunitas serta panel pemantauan statistik eksekutif (Admin Dashboard) telah diselesaikan secara penuh:

1. **Fitur Ulasan & Rating Bintang Pengunjung**:
   - Komponen rating bintang 1 hingga 5 (★) interaktif pada halaman detail toko kuliner (`/toko/{id}#ulasan`).
   - Formulir testimoni/komentar pengalaman kuliner, mendukung pengunjung terdaftar (Wisatawan) maupun pengunjung publik.
   - Perhitungan rating rata-rata toko secara otomatis dan real-time (`averageRating()`, `reviewsCount()`).
   - Daftar ulasan masuk dengan avatar inisial, rating bintang, tanggal ulasan, dan komentar pengunjung.

2. **Skema Database Ulasan (`ulasan`)**:
   - Migration `2026_11_06_050252_create_ulasan_table.php`.
   - Relasi foreign key: `toko_id` (cascade delete) dan `user_id` (null on delete).
   - Model Eloquent `Ulasan` dan pembaruan relasi pada model `Toko`.
   - Seeder `UlasanSeeder` dengan ulasan nyata khas kuliner Melayu Riau.

3. **Panel Moderasi Ulasan Pengunjung (`/admin/ulasan`)**:
   - Kartu metrik total ulasan dan rata-rata rating kepuasan wisatawan.
   - Tabel direktori ulasan yang memuat nama toko, identitas pengulas, bintang, komentar, dan tanggal.
   - Aksi moderasi **Hapus Ulasan** untuk menghapus ulasan spam atau konten tidak pantas.

4. **Dashboard Ringkasan Eksekutif & Visualisasi Grafik (Chart.js)**:
   - 5 Kartu Statistik Utama:
     1. Total Kabupaten/Kota (12 Wilayah se-Riau)
     2. Total Makanan Khas
     3. Total Direktori Toko Terdaftar
     4. Total Pengajuan Promosi (dengan counter pending)
     5. Total Ulasan Masuk (dengan rata-rata rating bintang)
   - Grafik Batang Interaktif (Chart.js): **"Sebaran Toko Kuliner per Kabupaten/Kota se-Riau"**.
   - Widget Aktivitas: 5 Pengajuan Toko Terbaru & 5 Ulasan Wisatawan Terbaru.
   - Panel navigasi pintas seluruh modul sistem.

5. **Automated Testing**:
   - File pengujian `backend/tests/Feature/Sprint5Test.php` (3 passed tests, 14 assertions).

