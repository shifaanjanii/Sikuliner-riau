# Aset SiKuliner Riau

Lima ilustrasi makanan bergaya foto dibuat dengan **built-in imagegen**. Gambar AI bukan foto dokumentasi hidangan asli. Keterangan tampil pada hero dan detail contoh. Screenshot pengguna hanya menjadi acuan desain, tidak dipotong menjadi aset.

| Aset di public/assets/images/ | PNG sumber | WebP untuk situs |
| --- | --- | --- |
| Gulai Ikan Patin | gulai-patin.png | gulai-patin.webp, sekitar 283 KB |
| Bolu Kemojo | bolu-kemojo.png | bolu-kemojo.webp, sekitar 231 KB |
| Mie Sagu | mie-sagu.png | mie-sagu.webp, sekitar 263 KB |
| Lopek Bugi | lopek-bugi.png | lopek-bugi.webp, sekitar 197 KB |
| Lempuk Durian | lempuk-durian.png | lempuk-durian.webp, sekitar 223 KB |

PNG sumber dipertahankan. WebP diperkecil ke lebar 960 px dengan Sharp, kualitas 86 dan alpha dipertahankan. Ulangi dengan npm run assets:optimize setelah PNG diganti. Ini optimasi aset bawaan, bukan kompresi unggahan pengguna.

## Prompt final

### gulai-patin

Single round white ceramic bowl of Indonesian Riau gulai ikan patin, catfish steaks in rich golden yellow turmeric coconut curry with red chili and tomato and light basil garnish. Strict top down flat lay, entire bowl visible centered with 8 percent padding. Photorealistic premium editorial food photography with natural daylight and appetizing authentic texture. Genuinely transparent background. No utensils, hands, text, watermark, extra objects. Square image, one bowl only. Website cutout asset, not a website screenshot.

### bolu-kemojo

Single round white ceramic plate with Indonesian Riau bolu kemojo pandan cake: low dense green custard cake molded into a six petal flower with toasted golden upper edges, one wedge slightly separated showing moist green inside. Strict top down flat lay, entire plate visible centered with 8 percent padding. Photorealistic premium editorial food photography, natural daylight. Genuinely transparent background. No utensils, hands, text, watermark, extra objects. Square image, one plate only. Website cutout asset, not a website screenshot.

### mie-sagu

Single round white ceramic plate of Indonesian Riau mie sagu goreng, glossy translucent thick sago noodles stir fried with bean sprouts, chives and tiny dried shrimp, brown sauce, red chili slices. Strict top down flat lay, entire round plate visible centered with 8 percent padding. Photorealistic premium editorial food photography, natural daylight, authentic texture. Genuinely transparent background. No utensils, hands, text, watermark, extra objects. Square image, one plate only. Website cutout asset, not a website screenshot.

### lopek-bugi

Use case product-mockup. A single small round dark taupe ceramic plate with Indonesian Riau Lopek Bugi: two small banana-leaf wrapped sticky rice parcels and one opened dark purple glutinous rice dumpling with a little sweet grated coconut filling visible. Strict top down overhead, entire plate and all leaf packets visible with a generous transparent margin. Photorealistic editorial food photography, natural daylight, true textures. Genuinely transparent background. No hands, utensils, text or other props. Square cutout asset for an animated food website hero.

### lempuk-durian

Use case product-mockup. A single small round pale beige ceramic plate with several small thick rectangular slices of Indonesian lempuk durian, glossy dense caramel-brown durian fudge with slightly fibrous chewy texture. Warm brown candy blocks piled naturally in center, no wrappers. Strict top down overhead, entire round plate visible with a generous transparent margin. Photorealistic editorial food photography, natural daylight, true textures. Genuinely transparent background. No hands, utensils, fruit, text or extra props. Square cutout asset for an animated food website hero.

## Font dan ikon

DM Sans dan Manrope berasal dari distribusi Fontsource dan disimpan lokal di public/assets/fonts/. Lisensi OFL disertakan sebagai dm-sans-LICENSE.txt dan manrope-LICENSE.txt. Website tidak membutuhkan Google Fonts. Ikon antarmuka dan logo mangkuk/daun dibuat sebagai SVG di Frontend::icon() dan partials/brand.php.

## Informasi data contoh

Fixture hanya dimuat dengan ?demo=1 dan ditandai sebagai data contoh. Asal wilayah gulai, bolu kemojo dan mie sagu sengaja tidak ditentukan karena tidak ada data relasi wilayah terverifikasi dalam proyek.

Deskripsi bolu kemojo tentang pandan, bentuk bunga dan perayaan diringkas dari [PPID Provinsi Riau](https://ppid.riau.go.id/berita/19506/bolu-kemojo-luky--si-unik-yang-menggugah-selera?page=1). Hubungan Lopek Bugi dengan Kampar dan ringkasan sejarahnya didukung [TP PKK Provinsi Riau](https://tppkk.riau.go.id/post/lopek-bugi--makanan-bangsawan-yang-kini-digemari-semua-kalangan). Tidak ada asal daerah yang ditetapkan dari foto.

## Aset yang masih diperlukan

Foto asli berizin untuk record makanan produksi, deskripsi dan sejarah terverifikasi, relasi asal kabupaten/kota yang benar, serta ikon/gambar wilayah bila backend memiliki field tersebut. Ganti URL foto dari backend; fallback rapi tersedia pada food-placeholder.svg.
