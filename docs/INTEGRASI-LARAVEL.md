# Integrasi front-end dengan backend Naya

## Kondisi awal

Pada 16 Oktober 2026, folder `C:\xampp\htdocs\SiKuliner` tidak berisi proyek Laravel. Tidak ada route/controller/model yang bisa diperiksa atau dipakai kembali. Laporan DOCX menjadi referensi kebutuhan; klaim penyelesaian sprint di laporan tidak menjadi bukti backend tersedia dalam workspace ini.

Template PHP dipisahkan di `resources/views`, dengan presenter di `app/Support/Frontend.php`. Pratinjau memakai template yang sama. Tidak ada aplikasi React/Next terpisah. Setelah proyek Laravel diberikan, pertahankan struktur/versi framework serta route dan controller asli. Jika backend telah memiliki view Blade, gabungkan markup dan aset ke layout Blade yang ada sesuai kebutuhan; jangan mengganti backend dengan router pratinjau.

## Berkas yang dipindahkan atau digabung

1. `public/assets/` ke direktori aset publik Laravel.
2. `resources/views/` dan `app/Support/Frontend.php` untuk memakai renderer ini, atau pindahkan markup komponennya ke Blade yang sudah ada.
3. `app/Support/LaravelFrontend.php` jika memakai adapter yang disediakan.
4. Isi konfigurasi `integration/bindings.example.php` dengan nama route, Gate, parameter, dan field yang benar. Simpan sebagai `config/sikuliner-ui.php` di Laravel.

**Jangan menyalin** `public/index.php`, `index.php`, `.htaccess` proyek pratinjau, `preview/`, atau fixture ke entry point/route produksi Laravel. File tersebut hanya menjalankan pratinjau tanpa framework. Tidak ada migrate, seeder, perubahan foreign key, atau cascade delete dalam pekerjaan front-end ini.

## Pemetaan data tampilan

Controller asli menyediakan array normalisasi berikut. Nama ini adalah kontrak presentasi, **bukan usulan mengganti kolom database**.

| Objek | Field untuk tampilan |
| --- | --- |
| Wilayah | `id`, `name`, `description`, `photo` nullable |
| Makanan | `id`, `name`, `region_id` nullable, `category`, `description`, `photo` nullable; `source` opsional |
| Katalog | `foods` berisi hasil halaman saat ini, `regions` daftar asli, `q`, `region`, `page`, `pages`, `total`, `categories`, `featured` |
| Form edit | `item` satu record normalisasi; `edit` true; `entity` `foods` atau `regions` |
| Tabel admin | `items` daftar record normalisasi yang ingin ditampilkan; `entity` dan `title` |

`photo` dapat berupa URL HTTP(S), path absolut aplikasi seperti `/storage/...`, atau path relatif aset. Nilai nama/deskripsi ditampilkan sebagai teks dengan HTML escaping. Jika deskripsi backend mengandung HTML, normalisasikan menjadi teks sebelum dikirim. URL `source` yang ditampilkan harus HTTPS.

Kategori boleh menggunakan empat nilai awal dari spesifikasi; kirim pilihan dari backend bila sudah memiliki data kategorinya. Dropdown asal mengikuti record wilayah asli; **ID fixture berupa slug tidak boleh dipakai sebagai ID database asli**.

## Memakai adapter pada controller yang sudah ada

Contoh berikut menggambarkan titik integrasi setelah controller menyiapkan `$data` dari query aslinya:

```php
use App\Support\LaravelFrontend;

$ui = LaravelFrontend::make($request, $data, config('sikuliner-ui'));
return response($ui->render('catalog', [
    'title' => 'Jelajah Kuliner',
    'active' => 'catalog',
]));
```

View lainnya: `home`/layout `public`, `about`/`public`, `detail`/`public` dengan `food`, dan `auth.form`/`auth` dengan `register` true/false. Adapter memeriksa seluruh nama route; konfigurasi yang belum lengkap akan ditolak agar form tidak diam-diam memakai endpoint pratinjau.

Form memakai `fields` untuk menyesuaikan nama input dengan request validation yang sudah ada. Error validasi dan old input memakai nama field request tersebut. `queries` menyesuaikan nama parameter pencarian dan wilayah. `parameterMap` menyesuaikan parameter `{id}` ke parameter route model binding yang sebenarnya. `updateMethod` harus sesuai route, `PUT` atau `PATCH`.

## Autentikasi dan admin

- `login`/`register` adalah route GET; `login.store`/`register.store` adalah nama route POST asli, yang boleh memetakan ke nama route GET bila Laravel menggunakan URL yang sama.
- Semua form mutasi menyertakan `csrf_field()` dari sesi Laravel. Preview tidak membuat token atau sesi autentikasi palsu.
- Login valid/tidak valid, hashing, registrasi dengan role pengguna biasa, regenerasi sesi, rate limit, redirect role, serta logout/invalidate sesi tetap dikerjakan controller autentikasi asli.
- `adminAbility` mengacu pada Gate/ability **yang benar-benar didaftarkan backend**. Adapter tidak menyimpulkan izin dari parameter URL atau localStorage.
- Untuk render admin, gunakan `LaravelFrontend::admin(...)`. Fungsi ini memeriksa user dan Gate di server. Tetap pertahankan middleware `auth` dan middleware/otorisasi admin pada **semua** route CRUD, termasuk POST/PUT/PATCH/DELETE; pembatasan tampilan saja tidak melindungi operasi data.

```php
return LaravelFrontend::admin($request, $data, config('sikuliner-ui'), 'admin.list', [
    'title' => 'Makanan Khas',
    'active' => 'foods.index',
    'entity' => 'foods',
    'items' => $normalisedRows,
]);
```

Backend menentukan Gate berdasarkan implementasi role Naya yang sebenarnya. Pengguna tanpa izin harus mendapat 403 atau pengalihan yang sesuai. Logout harus POST dan benar-benar mengakhiri sesi, lalu mengarah ke halaman publik.

## Filter, pencarian dan pagination

Gunakan query backend asli untuk kondisi wilayah **dan** nama secara bersama, kemudian kirim hasil paginasi ke kontrak tampilan. `preview/catalog.php` tidak digunakan di produksi. Kartu dan pagination mempertahankan parameter query. Semua filter adalah form GET biasa; tidak ada request AJAX yang bisa menimpa hasil pilihan terbaru.

Contoh pola query konseptual, sesuaikan seluruh nama dengan model asli: kondisi `where` asal bila dipilih, kondisi pencarian nama bila diisi, `paginate`, lalu normalisasi item dan metadata. Kueri harus dibatasi dan menggunakan binding Eloquent/query builder yang sudah ada, bukan interpolasi SQL dari input.

## CRUD dan foto

- Form create memakai POST, edit memakai POST dengan `_method` sesuai route update, hapus POST dengan `_method=DELETE` setelah konfirmasi.
- Controller sukses mengirim flash `status` berisi pesan ramah. Validasi kembali dengan validation bag serta old input; error umum/penolakan hapus relasi dapat memakai flash `error`.
- Pengecekan dan penolakan hapus wilayah yang masih memiliki data terkait dilakukan di backend. Front-end tidak mengubah foreign key atau menghapus data terkait.
- Tentukan `regionPhotoEnabled` sesuai keberadaan field wilayah. Default false.
- Laporan menyebut batas 2 MB; kode validasi backend belum tersedia untuk diverifikasi. Preview memakai **acuan** JPG/PNG/WebP 2 MB, dinyatakan pada form. Isi `uploadMaxBytes` dan `uploadAccept` sesuai aturan controller yang sebenarnya, termasuk jika WebP tidak diizinkan.
- Preview foto menggunakan object URL lokal, tidak mengunggah saat memilih file. Foto lama dipertahankan bila tidak ada file baru hanya jika controller mendukungnya. Backend tetap harus memeriksa MIME, ukuran, penyimpanan, dan akses file; batas pada browser bukan pengganti validasi server.
- Tidak ada klaim kompresi unggahan. Pembuatan WebP untuk ilustrasi bawaan adalah optimasi aset situs, bukan kompresi file unggahan pengguna.

## Pemeriksaan setelah backend digabung

Uji kredensial valid/tidak valid, register duplikat/invalid, redirect dua role, blokir pengguna biasa/guest pada seluruh route admin, logout dan request setelah logout, CSRF salah, create/edit dengan foto lama/baru, penolakan file > batas, penolakan hapus wilayah terkait, flash sukses/error, filter gabungan dan pagination. Semua skenario ini belum teruji terhadap Laravel pada workspace saat ini.

Rujukan implementasi adapter: dokumentasi resmi [Views Laravel](https://laravel.com/docs/12.x/views), [CSRF](https://laravel.com/docs/12.x/csrf), dan [Authorization](https://laravel.com/docs/12.x/authorization). Rujukan tersebut tidak menentukan versi framework yang harus dipakai tim.
