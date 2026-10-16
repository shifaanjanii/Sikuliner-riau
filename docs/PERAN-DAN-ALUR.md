# Peran dan alur akun SiKuliner Riau

Keputusan pengguna pada 16 Oktober 2026: pekerjaan tetap dibatasi Sprint 1 dan 2. Registrasi publik membuat akun pengguna; akun admin dibuat melalui backend oleh pengelola. Admin dan pengguna masuk melalui halaman Login yang sama.

## Cakupan saat ini

| Peran | Alur akun | Akses Sprint 1–2 |
| --- | --- | --- |
| Pengunjung | Dapat menjelajah tanpa login | Beranda, katalog, filter wilayah, pencarian dan informasi makanan |
| Pengguna | Registrasi publik, lalu login | Halaman publik dengan identitas akun dan Logout |
| Admin | Akun dibuat melalui backend, lalu login | Area admin untuk mengelola wilayah dan makanan khas, serta akses website publik |

Form registrasi tidak memiliki pilihan role admin. Form login hanya meminta email dan kata sandi. Backend menentukan peran akun yang berhasil diautentikasi, mengarahkan admin ke area admin dan pengguna ke halaman publik. Logout harus mengakhiri sesi di server.

Teks Login/Register sudah diperjelas untuk alur ini. Tidak ada pendaftaran admin publik, login palsu, atau perubahan role melalui JavaScript. Backend Laravel belum tersedia dalam workspace; form saat ini tetap merupakan pratinjau dan tidak membuat akun/sesi.

## Kebutuhan tahap berikutnya

| Kebutuhan | Peran dan hubungan data | Tahap sesuai laporan |
| --- | --- | --- |
| Tempat makan/restoran | Admin menambahkan dan mengubah tempat makan di kabupaten/kota yang sesuai | Sprint 3 |
| Google Maps dan rute | Lokasi atau tautan Maps disimpan pada data tempat makan | Sprint 4 |
| Ulasan dan bintang 1–5 | Pengguna yang sudah login memberikan ulasan pada restoran tertentu | Sprint 5 |

Ulasan terkait akun pengguna dan tempat makan/restoran. Informasi makanan khas tetap menjadi entitas katalog tersendiri. Tidak ada menu atau formulir fitur tahap berikutnya yang ditambahkan pada Sprint 1–2.

## Tindak lanjut backend Naya

Gunakan struktur role dan mekanisme pembuatan admin yang sudah dirancang tim. Endpoint registrasi publik harus menetapkan akun sebagai pengguna dan menolak upaya menaikkan role dari payload permintaan. Login membaca role tersimpan; middleware/Gate melindungi seluruh route admin termasuk aksi CRUD. Saat autentikasi berhasil, sesi diregenerasi dan pengalihan mengikuti role; Logout mengakhiri sesi.

Tidak ada schema, seeder atau akun admin baru yang dibuat oleh pekerjaan front-end ini. Pemetaan route, CSRF, validation bag dan Gate tersedia dalam [panduan integrasi Laravel](INTEGRASI-LARAVEL.md).
