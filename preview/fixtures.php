<?php
// Explicit development fixtures: only loaded with ?demo=1. Never persisted or seeded.
// Unverified municipal origins are intentionally left null.
return [
    ['id' => 'contoh-gulai', 'name' => 'Gulai Ikan Patin', 'region_id' => null, 'category' => 'Makanan Utama', 'photo' => 'images/gulai-patin.png', 'description' => 'Gulai ikan patin adalah salah satu contoh makanan dalam rancangan SiKuliner Riau. Data asal daerah dan cerita lengkapnya menunggu verifikasi tim sebelum diterbitkan.', 'source' => null],
    ['id' => 'contoh-kemojo', 'name' => 'Bolu Kemojo', 'region_id' => null, 'category' => 'Kue Tradisional', 'photo' => 'images/bolu-kemojo.png', 'description' => 'Kue tradisional Melayu dengan aroma pandan dan bentuk menyerupai bunga kamboja. Bolu kemojo hadir dalam berbagai perayaan. Penetapan kabupaten atau kota asal menunggu data terverifikasi dari tim.', 'source' => 'https://ppid.riau.go.id/berita/19506/bolu-kemojo-luky--si-unik-yang-menggugah-selera?page=1'],
    ['id' => 'contoh-mie', 'name' => 'Mie Sagu', 'region_id' => null, 'category' => 'Makanan Utama', 'photo' => 'images/mie-sagu.png', 'description' => 'Contoh tampilan untuk mengenalkan olahan mi berbahan sagu dalam katalog. Asal kabupaten atau kota serta sejarahnya perlu dilengkapi dan diverifikasi oleh tim.', 'source' => null],
    ['id' => 'contoh-lopek', 'name' => 'Lopek Bugi', 'region_id' => 'kampar', 'category' => 'Kue Tradisional', 'photo' => null, 'description' => 'Lopek bugi merupakan lepat ketan dari Kabupaten Kampar. Dahulu disajikan untuk kaum bangsawan, kudapan ini kemudian menjadi bagian dari perayaan adat dan keagamaan serta dikenal lebih luas oleh masyarakat.', 'source' => 'https://tppkk.riau.go.id/post/lopek-bugi--makanan-bangsawan-yang-kini-digemari-semua-kalangan'],
];
