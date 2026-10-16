# Sprint 1 SiKuliner Riau

## Hasil

Backend Laravel 12 untuk registrasi pengguna, login, logout, role admin/pengguna, halaman akun dan dashboard admin dasar. Katalog, wilayah dan pencarian belum dibuat; modul tersebut menjadi Sprint 2.

Pengerjaan C diprioritaskan berurutan: migration role dan relasi user, autentikasi session, pembatasan akses, integrasi formulir, pengujian. Tampilan sementara dapat diganti B dengan mempertahankan kontrak formulir dan route. CSS sementara berada di public/css/sprint1.css; penggantian dengan komponen Bootstrap desain B belum dilakukan. Navbar beranda ditambahkan akses masuk/daftar/keluar dan pembuka HTML/head diperbaiki.

## Menjalankan di Windows

Aktifkan MySQL pada XAMPP Control Panel (instalasi ini menggunakan MariaDB dengan koneksi mysql Laravel).

Dari PowerShell:

```powershell
Set-Location 'C:\xampp\htdocs\SiKuliner'
php artisan migrate
php artisan db:seed
php artisan sikuliner:admin
php artisan serve --host=127.0.0.1 --port=8000
```

Perintah admin meminta nama, email, password dan konfirmasi. Password tidak ditampilkan saat diketik. Pilih sendiri password; tidak ada akun admin atau password bawaan. Seeder default hanya memastikan role tersedia. Jika membuat admin melalui seeder, isi ADMIN_NAME, ADMIN_EMAIL dan ADMIN_PASSWORD pada environment lokal saja, jalankan config:clear kemudian db:seed, lalu hapus nilai ADMIN_PASSWORD dari environment setelah selesai. Seeder tidak mempromosikan pengguna biasa atau mengganti password admin yang sudah ada.

Buka http://127.0.0.1:8000/login atau http://127.0.0.1:8000/register. Registrasi masuk ke /akun; login admin ke /admin. npm tidak diperlukan untuk tampilan sementara karena aset dilayani langsung dari public.

Database pengembangan bernama sikuliner. Jangan membagikan .env atau kredensial. Pada komputer anggota tim lain, buat database kosong dan sesuaikan .env miliknya, lalu jalankan migration. Jangan menggunakan migrate:fresh pada database tim.

## Kontrak frontend B

| Halaman | Form / variabel |
|---|---|
| auth/login.blade.php | POST route('login.store'): email, password |
| auth/register.blade.php | POST route('register.store'): name, email, password, password_confirmation |
| Logout | POST route('logout'), @csrf; bukan tautan GET |
| account.blade.php | auth()->user(): name, email, role; URL /akun |
| admin/dashboard.blade.php | auth()->user(); admin saja; URL /admin |

Semua form POST memakai @csrf. Tampilkan @error per input dan old() untuk nama/email, jangan old() untuk password. Pesan sukses tersedia di session('success'). Jangan menambahkan pilihan role di registrasi. Middleware tetap memeriksa akses meskipun tombol disembunyikan di frontend.

File backend baru: Role, AuthController, LoginRequest, RegisterRequest, EnsureAdmin, PasswordByteLength, CreateAdmin, migration roles, RoleSeeder, AdminSeeder, config/sikuliner.php. File backend yang diperbarui: User, bootstrap/app.php, routes/web.php, DatabaseSeeder. phpunit.xml memaksa database SQLite in-memory untuk menjaga data pengembangan saat pengujian.

## Pengujian dan kriteria penerimaan

```powershell
Set-Location 'C:\xampp\htdocs\SiKuliner'
php artisan test --compact
php artisan route:list --except-vendor
```

Pada 15 Oktober 2026, pengujian pada salinan kerja menghasilkan **28 lulus, 122 assertions** menggunakan SQLite in-memory (termasuk 2 tes bawaan proyek). Tes baru meliputi registrasi, hashing, email duplikat, input tidak valid, pembatasan role, session login/logout, lockout login, pembuatan admin, output aman, serta CSRF dengan bypass pengujian Laravel dinonaktifkan khusus untuk skenario tersebut. Ini bukan klaim bahwa semua aspek keamanan sudah diaudit.

Pemeriksaan browser: halaman login desktop dan lebar 390px diperiksa secara visual; tombol Lihat mengubah tipe input menjadi text. Registrasi, redirect admin dan logout diuji melalui tes HTTP Laravel; alur browser penuh dan integrasi desain final B masih perlu review bersama.

| Kriteria | Status di salinan kerja |
|---|---|
| Pengguna bisa mendaftar dengan password tersimpan sebagai hash | Lulus |
| Input role admin dari registrasi tidak menaikkan akses | Lulus |
| Login benar/salah menghasilkan respons yang sesuai | Lulus |
| Session ID berubah setelah login | Lulus |
| Tamu diarahkan ke login dan pengguna biasa ditolak dari admin | Lulus |
| Middleware admin menolak tindakan tulis pengguna biasa | Lulus pada route uji |
| Logout menghapus session pribadi dan akses terlindungi | Lulus |
| Permintaan form tanpa CSRF ditolak | Lulus |
| Seeder tidak membuat password admin bawaan | Lulus |
| Login desktop/ponsel dan tombol tampil password | Diperiksa di browser |

Perbaikan yang dilakukan: melengkapi struktur HTML/head layout beranda; menambahkan batas byte password untuk mencegah pemotongan bcrypt; membuat role_id tidak dapat diisi massal melalui model; menjaga pengujian pada database terpisah. Hasil penerapan ke database XAMPP dicatat setelah deployment, bukan diasumsikan dari tes SQLite.

## Review dan lanjutan

Pengguna membuat akun admin melalui perintah lokal, mencoba login, dan meninjau pembagian frontend B. Setelah Sprint 1 disetujui, lanjut ke migration wilayah/kuliner, CRUD, upload foto, filter dan pencarian Sprint 2. Diagram A harus mengikuti nama tabel, relasi, alur login, dan pemeriksaan role aktual. Daily Scrum tetap diisi berdasarkan pekerjaan tim yang benar-benar dilakukan.
