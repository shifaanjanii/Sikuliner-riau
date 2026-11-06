<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Database\Seeder;

class UlasanSeeder extends Seeder
{
    public function run(): void
    {
        $wisatawan = User::where('email', 'wisatawan@sikuliner.test')->first();
        $shops = Toko::all();

        if ($shops->isEmpty()) {
            return;
        }

        $reviewsPool = [
            [
                'rating' => 5,
                'nama' => 'Nanda Hidayah',
                'komentar' => 'Rasa kulinernya sangat autentik! Bolu kemojonya harum pandan dan sangat lembut. Wajib dikunjungi kalau ke Pekanbaru.',
            ],
            [
                'rating' => 5,
                'nama' => 'Prety Afriani',
                'komentar' => 'Gulai patin di sini kuahnya gurih kental dan tidak amis sama sekali. Pelayanannya cepat dan lokasinya mudah ditemukan via Maps.',
            ],
            [
                'rating' => 4,
                'nama' => 'Shifa Anjani',
                'komentar' => 'Lopek bugi pulut hitamnya manisnya pas dan legit banget! Bungkus daun pisangnya bikin makin harum. Sangat direkomendasikan.',
            ],
            [
                'rating' => 5,
                'nama' => 'Kanaya Aurellia',
                'komentar' => 'Paling suka sama mie sagunya, kenyal dan bumbu belacannya juara! Porsinya pas dan harganya sangat bersahabat untuk wisatawan.',
            ],
            [
                'rating' => 4,
                'nama' => 'Wisatawan Riau',
                'komentar' => 'Tempatnya bersih, nyaman untuk keluarga, dan pilihan oleh-oleh khas daerahnya sangat lengkap.',
            ],
        ];

        foreach ($shops as $index => $shop) {
            // Add 2 reviews per shop
            $r1 = $reviewsPool[$index % count($reviewsPool)];
            $r2 = $reviewsPool[($index + 1) % count($reviewsPool)];

            Ulasan::updateOrCreate(
                [
                    'toko_id' => $shop->id,
                    'nama_pengulas' => $r1['nama'],
                ],
                [
                    'user_id' => $wisatawan?->id,
                    'rating' => $r1['rating'],
                    'komentar' => $r1['komentar'],
                ]
            );

            Ulasan::updateOrCreate(
                [
                    'toko_id' => $shop->id,
                    'nama_pengulas' => $r2['nama'],
                ],
                [
                    'user_id' => null,
                    'rating' => $r2['rating'],
                    'komentar' => $r2['komentar'],
                ]
            );
        }
    }
}

