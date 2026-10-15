# SiKuliner Riau

Front-end proyek kuliah untuk **Sprint 1 dan 2**: halaman publik, autentikasi, dan antarmuka pengelolaan wilayah serta makanan khas.

Folder proyek saat diperiksa kosong. Tidak ada `composer.json`, Laravel, controller, model, migration, database, route, atau aset backend Naya. Karena itu, implementasi ini menggunakan template PHP modular dan CSS/JavaScript tanpa dependensi runtime. Tidak ada framework lain, database pengganti, login palsu, atau penyimpanan di localStorage.

## Menjalankan

Dengan Apache XAMPP aktif, buka **http://localhost/SiKuliner/**. `mod_rewrite` dan `AllowOverride` dibutuhkan untuk URL halaman. Tidak perlu menjalankan MySQL atau migrasi.

Alternatif dari PowerShell di folder proyek:

```powershell
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8080 -t public preview/router.php
```

Kemudian buka **http://127.0.0.1:8080/**. Pratinjau kompatibel dengan PHP 8.0 yang tersedia di komputer ini. Versi PHP untuk Laravel nantinya mengikuti proyek backend Naya.

| Halaman | URL pada server pratinjau |
| --- | --- |
| Beranda | `/` |
| Jelajah Kuliner | `/kuliner` |
| Tentang | `/tentang` |
| Login | `/masuk` |
| Register | `/daftar` |
| Beranda admin, pratinjau | `/preview/admin` |
| Kabupaten/Kota, pratinjau | `/preview/admin/wilayah` |
| Tambah wilayah | `/preview/admin/wilayah/tambah` |
| Edit wilayah | `/preview/admin/wilayah/kampar/edit` |
| Makanan khas, pratinjau | `/preview/admin/makanan?demo=1` |
| Tambah makanan | `/preview/admin/makanan/tambah` |
| Edit makanan contoh | `/preview/admin/makanan/contoh-kemojo/edit?demo=1` |

Tambahkan `?demo=1` pada beranda/katalog untuk melihat **data contoh yang diberi label**. Misalnya `http://127.0.0.1:8080/?demo=1`. Tanpa parameter tersebut, katalog menampilkan keadaan kosong yang siap menerima data asli. Daftar 12 wilayah berasal dari permintaan pengguna, bukan isi database.

## Yang sudah dibuat

- Beranda putih–mint, hero tiga ilustrasi makanan, kontrol sebelumnya/berikutnya, animasi masuk dan gerak melayang.
- Katalog dengan filter wilayah dan pencarian gabungan, query URL, pagination, kartu dan detail makanan, reset serta keadaan kosong.
- Halaman Tentang dengan akses katalog per wilayah.
- Navbar responsif dengan hamburger; varian akun pengguna/admin dan form Logout POST siap menerima identitas dari server.
- Login/Register dengan kontrol kata sandi, validasi dekat field, old input aman, serta tempat pesan error/status backend.
- Layout admin, sidebar responsif, sambutan tanpa statistik, daftar wilayah/makanan, form tambah/edit, preview unggahan, konfirmasi hapus dan tempat pesan penolakan relasi.
- Pengurangan animasi mengikuti `prefers-reduced-motion`; konten tetap tersedia tanpa JavaScript.

## Status integrasi

**Belum ada integrasi backend yang dapat dinyatakan berfungsi.** Login/Register tidak membuat sesi atau akun; submit autentikasi/CRUD pada pratinjau tidak menyimpan data dan tidak pernah mengklaim berhasil. URL `/admin` beserta turunannya mengembalikan HTTP 403. `/preview/admin` hanya memperlihatkan antarmuka pengembangan tanpa akun dan tanpa operasi data.

`app/Support/LaravelFrontend.php` menyediakan adapter untuk route asli, CSRF Laravel, old input, validation bag, flash, Gate, field model, serta aturan unggahan. Adapter belum bisa dieksekusi atau diuji di Laravel karena framework dan backend belum diberikan. Rincian penghubung ada di [docs/INTEGRASI-LARAVEL.md](docs/INTEGRASI-LARAVEL.md).

Naya perlu menyediakan proyek backend, nama route dan parameter, pemetaan field model, hasil query katalog/pagination, validasi foto, izin admin, redirect berdasarkan role, dan aturan hapus relasi. Tidak ada skema database yang dibuat atau diubah.

## Mengubah tampilan

| Kebutuhan | Lokasi |
| --- | --- |
| Warna, font, spacing, responsivitas | `public/assets/css/app.css`, variabel `:root` dan media queries |
| Animasi hero, hover dan reduced motion | Bagian hero dan keyframes di `app.css` |
| Slider, hamburger, password, preview foto, dialog hapus | `public/assets/js/app.js` |
| Teks beranda dan Tentang | `resources/views/home.php`, `resources/views/about.php` |
| Identitas dan menu | `resources/views/partials/brand.php`, `navbar.php`, `sidebar.php`, `footer.php` |
| Login/Register | `resources/views/auth/form.php` |
| Filter, kartu, pagination | `resources/views/components/` |
| Form dan tabel admin | `resources/views/admin/` |
| Pilihan hero dan wilayah referensi | `preview/content.php` (pengembangan saja) |
| Data contoh | `preview/fixtures.php` (hanya `?demo=1`) |
| Foto | `public/assets/images/`; setelah integrasi, gunakan URL dari backend |

Tiga gambar makanan dibuat dengan **built-in imagegen** sebagai ilustrasi AI, bukan dokumentasi hidangan asli. Prompt, sumber data contoh, dan aset yang masih dibutuhkan tercatat di [docs/ASET.md](docs/ASET.md).

## Pemeriksaan

```powershell
& 'C:\xampp\php\php.exe' tests/presentation.php
node --test tests/http.test.mjs
npm run test:browser
```

Uji HTTP memerlukan server pratinjau di port 8080. `UI_BASE_URL` dapat diarahkan ke URL XAMPP, misalnya `http://localhost/SiKuliner`. Node/npm hanya digunakan untuk alat pengembangan; situs berjalan tanpa `node_modules`.

Hasil pengujian dan batas verifikasinya dicatat di [docs/PEMERIKSAAN.md](docs/PEMERIKSAAN.md). Fitur toko, harga, peta, ulasan, statistik, transaksi, dan pemesanan tidak ditambahkan.
