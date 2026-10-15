# SiKuliner Riau

Front-end proyek kuliah untuk **Sprint 1 dan 2**: halaman publik, autentikasi, dan antarmuka pengelolaan wilayah serta makanan khas.

Folder proyek saat diperiksa kosong. Tidak ada `composer.json`, Laravel, controller, model, migration, database, route, atau aset backend Naya. Karena itu, implementasi ini menggunakan template PHP modular dan CSS/JavaScript tanpa dependensi runtime. Tidak ada framework lain, database pengganti, login palsu, atau penyimpanan di localStorage.

## Menjalankan

Dengan Apache XAMPP aktif, buka **http://localhost/SiKuliner/**. `mod_rewrite` dan `AllowOverride` dibutuhkan untuk URL halaman. Tidak perlu menjalankan MySQL atau migrasi.

Alternatif dari PowerShell di folder proyek:

```powershell
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8080 -t public preview/router.php