<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Models\Toko;
use Illuminate\Database\Seeder;

class TokoSeeder extends Seeder
{
    public function run(): void
    {
        $pekanbaru  = Kabupaten::where('name', 'Kota Pekanbaru')->first();
        $kampar     = Kabupaten::where('name', 'Kabupaten Kampar')->first();
        $meranti    = Kabupaten::where('name', 'Kabupaten Kepulauan Meranti')->first();
        $bengkalis  = Kabupaten::where('name', 'Kabupaten Bengkalis')->first();
        $siak       = Kabupaten::where('name', 'Kabupaten Siak')->first();

        $shops = [
            [
                'kabupaten'  => $pekanbaru,
                'name'       => 'Toko Bolu Kemojo Luky',
                'alamat'     => 'Jl. Jend. Sudirman No. 71, Pekanbaru',
                'telepon'    => '0761-34567',
                'jam_buka'   => '07.00–21.00 WIB',
                'deskripsi'  => 'Toko oleh-oleh khas Pekanbaru yang terkenal dengan bolu kemojo pandan autentik. Tersedia berbagai varian rasa dan kemasan siap bawa.',
                'latitude'   => 0.5070677,
                'longitude'  => 101.4478888,
                'maps_url'   => 'https://maps.google.com/?q=Toko+Bolu+Kemojo+Luky+Pekanbaru',
                'kuliner'    => ['Bolu Kemojo'],
            ],
            [
                'kabupaten'  => $kampar,
                'name'       => 'Rumah Makan Patin Pak Itam',
                'alamat'     => 'Jl. Bangkinang–Pekanbaru Km 35, Bangkinang',
                'telepon'    => '0812-7654-3210',
                'jam_buka'   => '10.00–22.00 WIB',
                'deskripsi'  => 'Rumah makan spesialis ikan patin Sungai Kampar. Gulai patin, patin bakar, dan patin goreng tepung tersedia segar setiap hari.',
                'latitude'   => 0.3337,
                'longitude'  => 101.0053,
                'maps_url'   => null,
                'kuliner'    => ['Gulai Ikan Patin'],
            ],
            [
                'kabupaten'  => $meranti,
                'name'       => 'Kedai Mie Sagu Bu Halimah',
                'alamat'     => 'Jl. Merdeka No. 12, Selatpanjang',
                'telepon'    => '0766-22113',
                'jam_buka'   => '06.00–14.00 WIB',
                'deskripsi'  => 'Kedai sarapan terkenal di Selatpanjang yang menyajikan mie sagu goreng dan kuah segar setiap pagi hari.',
                'latitude'   => 1.0102,
                'longitude'  => 102.7190,
                'maps_url'   => null,
                'kuliner'    => ['Mie Sagu'],
            ],
            [
                'kabupaten'  => $bengkalis,
                'name'       => 'Toko Oleh-Oleh Lempuk Duri',
                'alamat'     => 'Jl. Hang Tuah No. 5, Bengkalis',
                'telepon'    => '0766-25000',
                'jam_buka'   => '08.00–20.00 WIB',
                'deskripsi'  => 'Pusat oleh-oleh lempuk durian asli Bengkalis. Produksi langsung dari buah durian lokal pilihan tanpa campuran tepung.',
                'latitude'   => 1.4705,
                'longitude'  => 102.1095,
                'maps_url'   => null,
                'kuliner'    => ['Lempuk Durian'],
            ],
            [
                'kabupaten'  => $siak,
                'name'       => 'Restoran Istana Rasa Siak',
                'alamat'     => 'Jl. Sultan Ismail No. 3, Siak Sri Indrapura',
                'telepon'    => '0764-20081',
                'jam_buka'   => '11.00–22.00 WIB',
                'deskripsi'  => 'Restoran bergaya istana Melayu yang menyajikan masakan tradisional kesultanan Siak, termasuk asam pedas baung dan gulai rempah.',
                'latitude'   => 0.9833,
                'longitude'  => 102.0167,
                'maps_url'   => null,
                'kuliner'    => ['Asam Pedas Baung'],
            ],
        ];

        foreach ($shops as $shopData) {
            if (!$shopData['kabupaten']) {
                continue; // Kabupaten belum ter-seed
            }

            $toko = Toko::firstOrCreate(
                ['name' => $shopData['name'], 'kabupaten_id' => $shopData['kabupaten']->id],
                [
                    'alamat'    => $shopData['alamat'],
                    'telepon'   => $shopData['telepon'],
                    'jam_buka'  => $shopData['jam_buka'],
                    'deskripsi' => $shopData['deskripsi'],
                    'latitude'  => $shopData['latitude'],
                    'longitude' => $shopData['longitude'],
                    'maps_url'  => $shopData['maps_url'],
                ]
            );

            // Kaitkan dengan kuliner khas yang dijual
            foreach ($shopData['kuliner'] as $kulinerName) {
                $kuliner = KulinerKhas::where('name', $kulinerName)->first();
                if ($kuliner && !$toko->kulinerKhas()->where('kuliner_khas_id', $kuliner->id)->exists()) {
                    $toko->kulinerKhas()->attach($kuliner->id);
                }
            }
        }
    }
}
