# SiKuliner Riau

Front-end proyek kuliah untuk **Sprint 1 dan 2**. Halaman publik, form autentikasi, dan antarmuka admin sudah dibuat. Folder awal kosong; backend Laravel Naya, database, model, dan route belum tersedia untuk diintegrasikan.

## Menjalankan

Dengan Apache XAMPP aktif, buka **http://localhost/SiKuliner/**. Apache memerlukan mod_rewrite dan AllowOverride untuk URL halaman. MySQL tidak diperlukan untuk pratinjau ini.

Alternatif dari PowerShell di folder proyek:

```powershell
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8080 -t public
```

Buka **http://127.0.0.1:8080/**. Pratinjau memakai PHP 8.0 yang tersedia. Tidak ada build frontend atau dependensi Node yang diperlukan untuk menjalankan situs.

| Halaman | URL |
| --- | --- |
| Beranda | / |
| Katalog | /kuliner |
| Tentang | /tentang |
| Login / Register | /masuk dan /daftar |
| Admin, pratinjau | /preview/admin |
| Daftar / tambah wilayah | /preview/admin/wilayah dan /preview/admin/wilayah/tambah |
| Edit wilayah contoh | /preview/admin/wilayah/kampar/edit |
| Daftar makanan contoh | /preview/admin/makanan?demo=1 |
| Tambah makanan | /preview/admin/makanan/tambah |
| Edit makanan contoh | /preview/admin/makanan/contoh-kemojo/edit?demo=1 |

Untuk melihat katalog berisi **data contoh yang ditandai**, buka **http://127.0.0.1:8080/?demo=1**. Tanpa parameter tersebut, katalog memperlihatkan keadaan kosong. Tidak ada data contoh yang disimpan sebagai data asli.

## Tampilan dan animasi

Landing page mengikuti referensi: logo mendatar, judul besar di kiri, lingkaran mint besar dengan satu piring utama dan empat piring kecil, panah dan dot carousel, pita kuning, panel filter mint, katalog tiga kolom, serta footer ringkas. Peta, toko, harga, rating, statistik dan transaksi belum dibuat sesuai batas Sprint 1–2.

Animasi meliputi teks masuk bertahap, piring masuk dari bawah sambil berputar, piring kecil muncul bergiliran, gerak melayang, parallax mouse ringan, pergantian foto dan judul, carousel otomatis setiap 7 detik dengan tombol jeda, kontrol panah/dot/keyboard, swipe HP, reveal saat scroll, hover foto/kartu/tombol, dan transisi hasil pencarian. Pengaturan reduced motion dihormati; konten tetap terlihat tanpa JavaScript.

Login/Register memiliki validasi dekat field dan kontrol kata sandi. Admin menyediakan sidebar HP, sambutan tanpa statistik, tabel wilayah/makanan, form tambah/edit, old values, preview foto dan konfirmasi hapus.

## Peran akun

Registrasi publik ditujukan untuk **pengguna**. **Akun admin dibuat melalui backend** oleh pengelola. Pengguna dan admin memakai halaman Login yang sama; backend menentukan peran dan pengalihan setelah masuk. Tidak ada pilihan menjadi admin pada registrasi publik.

Cakupan tetap Sprint 1–2 sesuai keputusan pengguna. Pengelolaan restoran, Google Maps, ulasan dan bintang pada restoran dicatat sebagai kebutuhan tahap berikutnya di [Peran dan alur akun](docs/PERAN-DAN-ALUR.md).

## Integrasi backend

**Belum ada integrasi backend yang dapat diklaim berfungsi.** Form pratinjau tidak membuat akun/sesi, menyimpan atau menghapus data. Submit menampilkan status belum tersedia. URL /admin beserta turunannya mengembalikan 403; /preview/admin hanya untuk meninjau tampilan tanpa akun.

Adapter di app/Support/LaravelFrontend.php siap menerima nama route asli, CSRF Laravel, validation bag, old input, flash, izin Gate, pemetaan field, dan aturan unggahan. Adapter belum diuji terhadap Laravel karena framework dan backend belum diberikan. Tidak ada migration, seeder, perubahan schema, cascade delete, ataupun login localStorage.

Naya perlu menyediakan backend dan memastikan autentikasi/role/redirect/logout, middleware semua route admin, CRUD, pencarian/filter/pagination, penolakan hapus relasi, serta validasi foto. Laporan menyebut 2 MB; pratinjau memakai acuan JPG/PNG/WebP 2 MB yang harus disamakan dengan aturan server.

Petunjuk: [Integrasi Laravel](docs/INTEGRASI-LARAVEL.md) dan [contoh konfigurasi](integration/bindings.example.php). Jangan menyalin entry point atau folder preview ini ke entry point produksi Laravel.

## File yang diubah untuk menyesuaikan desain

| Kebutuhan | File |
| --- | --- |
| Struktur hero dan bagian penutup | resources/views/home.php |
| Tampilan publik sesuai referensi, posisi piring, animasi dan responsivitas | public/assets/css/landing.css |
| Warna dasar, font, form dan tampilan admin | public/assets/css/app.css |
| Carousel, autoplay/jeda, parallax, swipe, reveal, hamburger, password, foto dan hapus | public/assets/js/app.js |
| Logo, navbar, footer | resources/views/partials/brand.php, navbar.php, footer.php |
| Panel filter dan kartu | resources/views/components/catalog.php, filter.php, food-card.php |
| Pilihan makanan hero dan wilayah referensi | preview/content.php |
| Data contoh, hanya pada mode demo | preview/fixtures.php |
| Foto PNG asli dan WebP yang digunakan | public/assets/images/ |
| Template Login/Register dan admin | resources/views/auth/ dan resources/views/admin/ |

Lima gambar makanan dibuat dengan **built-in imagegen** sebagai ilustrasi AI. Semua hasil dipindahkan ke workspace. Foto asli dapat menggantikannya. Prompt dan sumber contoh dicatat di [Aset](docs/ASET.md). Font Manrope dan DM Sans disimpan lokal bersama lisensinya.

## Pemeriksaan

```powershell
& 'C:\xampp\php\php.exe' tests/presentation.php
node --test --test-isolation=none tests/http.test.mjs
npm run test:browser
```

Uji HTTP memakai server port 8080. UI_BASE_URL dapat diarahkan ke URL lain. Playwright memakai Chrome yang sudah terpasang; PHP_BINARY dan CHROME_PATH bisa disesuaikan di environment. Node hanya untuk alat pengembangan. Perintah npm ci diperlukan pada komputer baru bila ingin menjalankan pemeriksaan browser atau optimasi gambar.

Hasil pemeriksaan dan bagian yang belum dapat diuji dicatat di [Pemeriksaan](docs/PEMERIKSAAN.md).
