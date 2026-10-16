# Pemeriksaan SiKuliner Riau

Tanggal pemeriksaan: 16 Oktober 2026. Lingkungan: Windows, PHP XAMPP 8.0.30, Node 24, Chrome headless melalui Playwright. Server pratinjau: http://127.0.0.1:8080 dengan document root public.

## Hasil yang sudah diuji

| Pemeriksaan | Hasil |
| --- | --- |
| Syntax seluruh berkas PHP presentasi, preview dan adapter | Lulus php -l |
| Syntax JavaScript | Lulus node --check |
| Presenter, escaping dan kontrak form | 16 pemeriksaan lulus |
| HTTP halaman, filter dan status error | 25 pengujian lulus |
| Interaksi browser dan responsivitas | 8 skenario lulus; skenario layar 320/768 diulang setelah perbaikan min-width form |
| Perubahan whitespace | git diff --check lulus |
| Review screenshot desktop dan HP | Diperiksa; susunan hero, kartu, filter, Login dan form admin terlihat tanpa tumpang tindih |

Uji presenter memastikan error server tampil dekat field, old input/nama pengguna di-escape, kata sandi tidak diisi ulang, registrasi tidak mempunyai pilihan role, Logout memakai POST dan field CSRF yang disuplai backend, serta tautan admin mengikuti capability dari data server. Pengujian ini memakai data uji terisolasi; bukan bukti sesi Laravel sudah berfungsi.

Uji HTTP memeriksa Beranda, Katalog, Tentang, Login/Register, detail contoh dan halaman admin pratinjau. Query wilayah dan nama bekerja bersama pada fixture; parameter terpilih tetap terlihat, reset dan keadaan tanpa hasil tersedia, input tidak menyisipkan script. Data contoh tidak muncul tanpa demo=1. URL admin asli mengembalikan 403; semua POST pratinjau mengembalikan 503 tanpa menyimpan data atau membuat cookie sesi. URL tak dikenal mengembalikan 404. CSS, JS, font lokal, lima gambar WebP dan fallback dapat dimuat.

Skenario browser mencakup:

1. Hero, foto, panah, dot, tombol jeda serta hasil klik slider terakhir saat pergantian cepat.
2. Login/Register: isian wajib, format email, konfirmasi kata sandi, tampilkan/sembunyikan kata sandi dan pesan layanan belum tersedia tanpa login palsu.
3. Filter/pencarian gabungan, refresh, detail makanan, kembali dengan query yang sama dan reset.
4. Form edit memakai nilai lama, preview file valid, penolakan format yang tidak diizinkan, batalkan foto baru, dan submit yang tidak mengklaim berhasil menyimpan.
5. Hapus dengan dialog, Batal, Escape dan konfirmasi pada mode pratinjau tanpa mengubah data.
6. Hamburger publik dan sidebar admin pada HP, serta tidak ada overflow horizontal pada halaman utama, katalog, Tentang, autentikasi, tabel dan form admin di 390 px.
7. Reduced motion dan katalog tanpa JavaScript.
8. Hero, katalog, autentikasi, tabel dan form admin pada lebar 320 dan 768 px; judul hero tetap di dalam viewport.

Desktop diperiksa pada 1440 px. Screenshot hasil QA berada di test-results/home-desktop.png, home-mobile.png, login-desktop.png dan admin-form-desktop.png. Folder test-results tidak masuk Git dan merupakan hasil pemeriksaan, bukan aset website.

## Batas yang belum dapat diuji

Backend Laravel, Composer, model, database dan route Naya belum tersedia dalam folder proyek. Hal berikut belum diuji terhadap backend nyata: kredensial benar/salah, registrasi tersimpan, redirect berdasarkan role, pembatasan pengguna biasa pada route CRUD asli, regenerasi/invalidate sesi, Logout asli, CSRF middleware Laravel, penyimpanan/ubah/hapus database, unggahan ke storage, penolakan hapus wilayah terkait, filter SQL dan pagination database.

Adapter Laravel hanya lolos syntax dan perlu dijalankan setelah dikonfigurasi dengan route/Gate/field yang benar. Ia tidak membuat backend pengganti. Data fixture digunakan hanya untuk pengembangan dan tidak di-seed. Tidak ada migrate:fresh atau perubahan database yang dijalankan.

Apache XAMPP tidak sedang aktif pada saat pemeriksaan, sehingga rewrite URL pada http://localhost/SiKuliner/ belum diuji melalui Apache. Pratinjau PHP pada port 8080 telah diuji. .htaccess menyediakan rewrite halaman dan membatasi akses langsung ke folder kode/alat pengembangan; route virtual preview/admin diarahkan ke entry point pratinjau.

## Mengulang pemeriksaan

Jalankan server dari folder proyek dengan PHP -S 127.0.0.1:8080 -t public. Kemudian jalankan php tests/presentation.php, node --test --test-isolation=none tests/http.test.mjs dan npm run test:browser. Gunakan path PHP XAMPP penuh apabila php belum berada di PATH. Browser headless perlu izin membuat proses worker pada lingkungan Windows yang membatasi sandbox.
