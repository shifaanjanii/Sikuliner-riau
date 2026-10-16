<?php
// UI copy and decorative artwork. These are not database records.
return [
    'categories' => ['Makanan Utama', 'Kue Tradisional', 'Minuman', 'Oleh-oleh'],
    'regions' => array_map(static function ($id, $name) {
        return ['id' => $id, 'name' => $name, 'description' => '', 'photo' => null];
    }, ['bengkalis', 'indragiri-hilir', 'indragiri-hulu', 'kampar', 'kepulauan-meranti', 'kuantan-singingi', 'pelalawan', 'rokan-hilir', 'rokan-hulu', 'siak', 'dumai', 'pekanbaru'], [
        'Kabupaten Bengkalis', 'Kabupaten Indragiri Hilir', 'Kabupaten Indragiri Hulu',
        'Kabupaten Kampar', 'Kabupaten Kepulauan Meranti', 'Kabupaten Kuantan Singingi',
        'Kabupaten Pelalawan', 'Kabupaten Rokan Hilir', 'Kabupaten Rokan Hulu',
        'Kabupaten Siak', 'Kota Dumai', 'Kota Pekanbaru',
    ]),
    'featured' => [
        ['name' => 'Gulai Ikan Patin', 'category' => 'Makanan Utama', 'photo' => 'images/gulai-patin.webp', 'caption' => 'Hangat rempah, kaya rasa.'],
        ['name' => 'Bolu Kemojo', 'category' => 'Kue Tradisional', 'photo' => 'images/bolu-kemojo.webp', 'caption' => 'Manisnya cerita dari Riau.'],
        ['name' => 'Mie Sagu', 'category' => 'Makanan Utama', 'photo' => 'images/mie-sagu.webp', 'caption' => 'Kenali ragam olahan sagu.'],
        ['name' => 'Lopek Bugi', 'category' => 'Kue Tradisional', 'photo' => 'images/lopek-bugi.webp', 'caption' => 'Kudapan dengan cerita tradisi.'],
        ['name' => 'Lempuk Durian', 'category' => 'Oleh-oleh', 'photo' => 'images/lempuk-durian.webp', 'caption' => 'Manis legit untuk dikenal.'],
    ],
];
