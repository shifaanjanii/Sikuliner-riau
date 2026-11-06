# Sprint 4 SiKuliner Riau — Formulir Pengajuan Promosi Toko & Navigasi Rute Maps

## Hasil dan Cakupan Sprint 4

Pada Sprint 4, fitur kemitraan promosi toko mandiri oleh pemilik usaha kuliner dan integrasi peta navigasi digital telah diselesaikan secara penuh:

1. **Formulir Pengajuan Promosi Toko (Frontend & Public Route)**:
   - Tombol navigasi `Promosikan Toko` pada Navbar publik.
   - Halaman formulir `/promosi` (`promotion.create` & `promotion.store`) untuk pemilik usaha mendaftarkan toko makan mereka.
   - Field data: Nama Toko, Nama Pemilik, Asal Kabupaten/Kota (12 daerah), Menu Makanan Khas Unggulan, Alamat Jalan Lengkap, Kontak WhatsApp, Jam Operasional, Titik Koordinat (Latitude & Longitude), Link Google Maps, Unggah Foto Tempat Makan, dan Deskripsi.
   - Validasi backend: sanitasi MIME foto (JPEG, PNG, WebP), batas maksimal 2MB, sanitasi input, dan notifikasi flash status berhasil.

2. **Skema Database Pengajuan Promosi (`pengajuan_toko`)**:
   - Migration `2026_11_06_050247_create_pengajuan_toko_table.php`.
   - Kolom status: `pending` (menunggu verifikasi), `approved` (disetujui & otomatis terbit), dan `rejected` (ditolak).
   - Model Eloquent `PengajuanToko` dengan relasi `belongsTo(Kabupaten::class)`.
   - Seeder `PengajuanTokoSeeder` dengan data pengajuan riil daerah Riau.

3. **Panel Admin Moderasi Pengajuan Promosi (`/admin/pengajuan`)**:
   - Filter status pengajuan: Semua, Menunggu Verifikasi, Disetujui, Ditolak dengan counter badge.
   - Aksi **Setujui (Approve)**: Data toko otomatis disalin dan diterbitkan ke tabel direktori utama `toko`, serta menghubungkan relasi kuliner khas terkait.
   - Aksi **Tolak (Reject)**: Merubah status menjadi ditolak beserta pencatatan alasan admin.

4. **Integrasi Peta Digital & Tombol Navigasi Rute (`/toko/{id}`)**:
   - Modul Google Maps Interaktif (iframe embed) pada halaman detail toko kuliner.
   - Tombol aksi utama **"Buka Rute di Google Maps"** dengan ikon peta yang langsung mengarahkan rute navigasi jalan ke Google Maps.

5. **Automated Testing**:
   - File pengujian `backend/tests/Feature/Sprint4Test.php` (4 passed tests, 16 assertions).

