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
        ['name' => 'Gulai Ikan Patin', 'category' => 'Makanan Utama', 'photo' => 'images/gulai-patin.png', 'caption' => 'Hangat rempah, kaya rasa.'],
        ['name' => 'Bolu Kemojo', 'category' => 'Kue Tradisional', 'photo' => 'images/bolu-kemojo.png', 'caption' => 'Manisnya cerita dari Riau.'],
        ['name' => 'Mie Sagu', 'category' => 'Makanan Utama', 'photo' => 'images/mie-sagu.png', 'caption' => 'Kenali ragam olahan sagu.'],
    ],
];
