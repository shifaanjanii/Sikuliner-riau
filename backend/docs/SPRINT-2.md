# Sprint 2 SiKuliner Riau — Backend & Manajemen Data

## Hasil dan Cakupan Sprint 2

Pada Sprint 2, seluruh modul data dan backend inti SiKuliner Riau telah diselesaikan menggunakan framework **Laravel 12**, meliputi:

1. **Wilayah (Kabupaten & Kota)**:
   - Migration tabel `kabupaten` (12 Kabupaten/Kota se-Provinsi Riau).
   - Model Eloquent `Kabupaten` dengan relasi `hasMany(KulinerKhas::class)` dan `hasMany(Toko::class)`.
   - Admin CRUD Kabupaten: Tambah, Ubah, Hapus (dengan proteksi integritas: tidak bisa dihapus jika masih ada data kuliner terkait).
   - Seeder `KabupatenKulinerSeeder` untuk 12 kabupaten/kota Riau.

2. **Katalog Kuliner Khas Riau**:
   - Migration tabel `kuliner_khas` (`name`, `category`, `kabupaten_id`, `description`, `photo`, `source`).
   - Model Eloquent `KulinerKhas` dengan relasi `belongsTo(Kabupaten::class)` dan `belongsToMany(Toko::class)` melalui pivot `kuliner_toko`.
   - Admin CRUD Kuliner: Tambah, Ubah, Hapus dengan validasi backend ketat dan upload foto (maksimal 2MB, sanitasi MIME image/jpeg,png,webp, penyimpanan storage aman, penghapusan file lama saat update/delete).
   - Filter wilayah (query parameter `region`), pencarian kata kunci (`q`), dan pagination (6 item/halaman) pada `CatalogController`.
   - Halaman detail makanan (`/kuliner/{id}`) yang menampilkan deskripsi, asal kabupaten, dan direktori tempat makan yang menyediakannya.

3. **Direktori Toko & Peta Navigasi (Google Maps)**:
   - Migration tabel `toko` (`name`, `kabupaten_id`, `alamat`, `telepon`, `jam_buka`, `latitude`, `longitude`, `maps_url`, `photo`, `deskripsi`).
   - Migration tabel pivot `kuliner_toko` (`kuliner_khas_id`, `toko_id`, `harga`).
   - Model Eloquent `Toko` dengan relasi `belongsTo(Kabupaten::class)` dan `belongsToMany(KulinerKhas::class)`.
   - Admin CRUD Toko: Tambah, Ubah, Hapus dengan upload foto, koordinat latitude/longitude, URL Google Maps, serta sinkronisasi kuliner yang dijual.
   - Seeder `TokoSeeder` dengan 5 toko/rumah makan nyata di berbagai wilayah Riau.

4. **Keamanan & Otorisasi Backend**:
   - Middleware `auth` dan `admin` pada seluruh route admin (`/admin/wilayah`, `/admin/makanan`, `/admin/toko`).
   - Perlindungan CSRF pada seluruh request mutasi (POST, PUT, DELETE).
   - Pencegahan penghapusan data berelasi (*Foreign Key Integrity Constraint*).
   - Validasi batas ukuran file upload server-side (maksimal 2048 KB).

---

## Ringkasan Struktur Database

```
users
  ├── id (PK)
  ├── role_id (FK -> roles.id)
  ├── name
  ├── email
  └── password (bcrypt hash)

roles
  ├── id (PK)
  └── name (admin, wisatawan)

kabupaten
  ├── id (PK)
  ├── name
  ├── description
  └── photo (nullable)

kuliner_khas
  ├── id (PK)
  ├── kabupaten_id (FK -> kabupaten.id, restrict onDelete)
  ├── name
  ├── category
  ├── description
  ├── photo (nullable)
  └── source (nullable)

toko
  ├── id (PK)
  ├── kabupaten_id (FK -> kabupaten.id, cascade onDelete)
  ├── name
  ├── alamat
  ├── telepon (nullable)
  ├── jam_buka (nullable)
  ├── deskripsi (nullable)
  ├── photo (nullable)
  ├── latitude (nullable, decimal 10,7)
  ├── longitude (nullable, decimal 10,7)
  └── maps_url (nullable)

kuliner_toko (Pivot)
  ├── id (PK)
  ├── kuliner_khas_id (FK -> kuliner_khas.id, cascade onDelete)
  ├── toko_id (FK -> toko.id, cascade onDelete)
  └── harga (nullable, integer)
```

---

## Daftar Route Backend

| Metode | URL | Nama Route | Middleware | Keterangan |
|---|---|---|---|---|
| GET | `/` | `home` | - | Beranda publik |
| GET | `/kuliner` | `catalog` | - | Katalog kuliner + filter & pencarian |
| GET | `/kuliner/{id}` | `food.show` | - | Detail kuliner & daftar toko penjual |
| GET | `/tentang` | `about` | - | Halaman tentang & daftar 12 wilayah |
| GET | `/login` | `login` | `guest` | Form login |
| POST | `/login` | `login.store` | `guest, throttle:30,1` | Proses autentikasi |
| GET | `/register` | `register` | `guest` | Form registrasi |
| POST | `/register` | `register.store` | `guest, throttle:10,1` | Proses registrasi user |
| POST | `/logout` | `logout` | `auth` | Akhiri sesi pengguna |
| GET | `/akun` | `account` | `auth` | Halaman akun pengguna |
| GET | `/admin` | `admin.dashboard` | `auth, admin` | Dashboard admin & ringkasan statistik |
| GET | `/admin/wilayah` | `admin.regions.index` | `auth, admin` | Daftar wilayah |
| GET | `/admin/wilayah/tambah` | `admin.regions.create` | `auth, admin` | Form tambah wilayah |
| POST | `/admin/wilayah` | `admin.regions.store` | `auth, admin` | Simpan wilayah baru |
| GET | `/admin/wilayah/{id}/edit` | `admin.regions.edit` | `auth, admin` | Form edit wilayah |
| PUT | `/admin/wilayah/{id}` | `admin.regions.update` | `auth, admin` | Perbarui wilayah |
| DELETE | `/admin/wilayah/{id}` | `admin.regions.destroy` | `auth, admin` | Hapus wilayah (jika kosong) |
| GET | `/admin/makanan` | `admin.foods.index` | `auth, admin` | Daftar kuliner khas |
| GET | `/admin/makanan/tambah` | `admin.foods.create` | `auth, admin` | Form tambah kuliner |
| POST | `/admin/makanan` | `admin.foods.store` | `auth, admin` | Simpan kuliner & upload foto |
| GET | `/admin/makanan/{id}/edit` | `admin.foods.edit` | `auth, admin` | Form edit kuliner |
| PUT | `/admin/makanan/{id}` | `admin.foods.update` | `auth, admin` | Perbarui kuliner & ganti foto |
| DELETE | `/admin/makanan/{id}` | `admin.foods.destroy` | `auth, admin` | Hapus kuliner & berkas foto |
| GET | `/admin/toko` | `admin.shops.index` | `auth, admin` | Daftar direktori toko |
| GET | `/admin/toko/tambah` | `admin.shops.create` | `auth, admin` | Form tambah toko |
| POST | `/admin/toko` | `admin.shops.store` | `auth, admin` | Simpan toko & upload foto |
| GET | `/admin/toko/{id}/edit` | `admin.shops.edit` | `auth, admin` | Form edit toko |
| PUT | `/admin/toko/{id}` | `admin.shops.update` | `auth, admin` | Perbarui toko & koordinat |
| DELETE | `/admin/toko/{id}` | `admin.shops.destroy` | `auth, admin` | Hapus toko & berkas foto |

---

## Pengujian dan Kriteria Penerimaan

Seluruh pengujian dijalankan otomatis menggunakan PHPUnit / Pest pada database SQLite in-memory:

```powershell
php artisan test
```

### Hasil Uji
- **Total Uji**: **64 tests passed (267 assertions)**
- `Tests\Feature\Sprint2Test`: 16 skenario (katalog, filter wilayah, pencarian, detail makanan, toko terhubung, CRUD kabupaten, validasi integritas relasi, CRUD makanan, validasi ukuran foto 2MB, pembatasan akses).
- `Tests\Feature\TokoTest`: 20 skenario (CRUD toko, validasi input nama/alamat/kabupaten/koordinat/URL peta, upload foto, relasi pivot kuliner, hak akses guest/user biasa).
- `Tests\Feature\AuthenticationTest`: 21 skenario (autentikasi Sprint 1).
- `Tests\Feature\AdminProvisioningTest`: 5 skenario (CLI admin).
