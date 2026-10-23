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
        $inhu       = Kabupaten::where('name', 'Kabupaten Indragiri Hulu')->first();

        $shops = [
            [
                'kabupaten'  => $pekanbaru,
                'name'       => 'Toko Bolu Kemojo Luky',
                'alamat'     => 'Jl. Jend. Sudirman No. 71, Pekanbaru',
                'telepon'    => '0761-34567',
                'jam_buka'   => '07.00–21.00 WIB',
                'deskripsi'  => 'Pusat oleh-oleh khas Pekanbaru legendaris yang memproduksi bolu kemojo pandan asli setiap hari. Memiliki tekstur lembut legit serta tersedia varian mini hingga loyang besar.',
                'photo'      => '/assets/images/bolu-kemojo.webp',
                'latitude'   => 0.5070677,
                'longitude'  => 101.4478888,
                'maps_url'   => 'https://maps.google.com/?q=Toko+Bolu+Kemojo+Luky+Pekanbaru',
                'menus'      => [
                    ['name' => 'Bolu Kemojo', 'harga' => 35000],
                ],
            ],
            [
                'kabupaten'  => $kampar,
                'name'       => 'Rumah Makan Patin Pak Itam',
                'alamat'     => 'Jl. Bangkinang–Pekanbaru Km 35, Bangkinang, Kampar',
                'telepon'    => '0812-7654-3210',
                'jam_buka'   => '10.00–21.30 WIB',
                'deskripsi'  => 'Rumah makan spesialis olahan ikan patin segar dari Sungai Kampar. Kuah kuning gulai patin berpadu aroma daun ruku-ruku menghasilkan rasa gurih asam yang autentik.',
                'photo'      => '/assets/images/gulai-patin.webp',
                'latitude'   => 0.3337,
                'longitude'  => 101.0053,
                'maps_url'   => 'https://maps.google.com/?q=Rumah+Makan+Patin+Pak+Itam+Kampar',
                'menus'      => [
                    ['name' => 'Gulai Ikan Patin', 'harga' => 45000],
                ],
            ],
            [
                'kabupaten'  => $kampar,
                'name'       => 'Gerai Lopek Bugi Danau Bingkuang',
                'alamat'     => 'Jl. Lintas Riau–Sumbar, Danau Bingkuang, Tambang, Kampar',
                'telepon'    => '0852-6543-9876',
                'jam_buka'   => '06.30–22.00 WIB',
                'deskripsi'  => 'Sentra oleh-oleh lopek bugi ketan putih dan ketan hitam khas Kampar. Dibungkus daun pisang segar dan dikukus tradisional dengan isian kelapa manis legit.',
                'photo'      => '/assets/images/lopek-bugi.webp',
                'latitude'   => 0.3812,
                'longitude'  => 101.2789,
                'maps_url'   => 'https://maps.google.com/?q=Lopek+Bugi+Danau+Bingkuang+Kampar',
                'menus'      => [
                    ['name' => 'Lopek Bugi', 'harga' => 20000],
                ],
            ],
            [
                'kabupaten'  => $meranti,
                'name'       => 'Kedai Mie Sagu Selatpanjang Bu Halimah',
                'alamat'     => 'Jl. Merdeka No. 12, Selatpanjang, Kepulauan Meranti',
                'telepon'    => '0766-22113',
                'jam_buka'   => '06.00–14.30 WIB',
                'deskripsi'  => 'Kedai kuliner sarapan legendaris di Selatpanjang. Mengolah tepung sagu lokal menjadi mie kenyal gurih dengan taburan tauge, kucai, dan teri Selat Malaka.',
                'photo'      => '/assets/images/mie-sagu.webp',
                'latitude'   => 1.0102,
                'longitude'  => 102.7190,
                'maps_url'   => 'https://maps.google.com/?q=Mie+Sagu+Selatpanjang+Kepulauan+Meranti',
                'menus'      => [
                    ['name' => 'Mie Sagu', 'harga' => 18000],
                ],
            ],
            [
                'kabupaten'  => $bengkalis,
                'name'       => 'Pusat Oleh-Oleh Lempuk Durian Bengkalis',
                'alamat'     => 'Jl. Hang Tuah No. 5, Bengkalis',
                'telepon'    => '0766-25000',
                'jam_buka'   => '08.00–21.00 WIB',
                'deskripsi'  => 'Produsen lempuk durian murni khas Bengkalis. Dibuat 100% dari daging durian lokal pilihan tanpa tambahan tepung, beraroma harum dan manis alami.',
                'photo'      => '/assets/images/lempuk-durian.webp',
                'latitude'   => 1.4705,
                'longitude'  => 102.1095,
                'maps_url'   => 'https://maps.google.com/?q=Lempuk+Durian+Bengkalis',
                'menus'      => [
                    ['name' => 'Lempuk Durian', 'harga' => 40000],
                    ['name' => 'Roti Jala Cecah Kari', 'harga' => 25000],
                ],
            ],
            [
                'kabupaten'  => $siak,
                'name'       => 'Restoran Tepian Sungai Jantan',
                'alamat'     => 'Jl. Sultan Ismail No. 3, Siak Sri Indrapura',
                'telepon'    => '0764-20081',
                'jam_buka'   => '10.00–22.00 WIB',
                'deskripsi'  => 'Restoran tepi Sungai Siak dengan panorama Istana Siak. Menyajikan hidangan khas kesultanan Melayu, terutama asam pedas ikan baung kuah kental rempah.',
                'photo'      => null,
                'latitude'   => 0.9833,
                'longitude'  => 102.0167,
                'maps_url'   => 'https://maps.google.com/?q=Istana+Siak+Sri+Indrapura',
                'menus'      => [
                    ['name' => 'Asam Pedas Baung', 'harga' => 50000],
                ],
            ],
            [
                'kabupaten'  => $inhu,
                'name'       => 'Kedai Es Pengantin Rengat',
                'alamat'     => 'Jl. Narasinga No. 18, Rengat, Indragiri Hulu',
                'telepon'    => '0813-9876-1234',
                'jam_buka'   => '09.00–18.00 WIB',
                'deskripsi'  => 'Kedai minuman tradisional penyedia Es Air Mata Pengantin otentik di Rengat. Disajikan segar dengan agar-agar warna-warni, biji selasih, dan serutan blewah.',
                'photo'      => null,
                'latitude'   => -0.3758,
                'longitude'  => 102.5489,
                'maps_url'   => 'https://maps.google.com/?q=Rengat+Indragiri+Hulu',
                'menus'      => [
                    ['name' => 'Air Mata Pengantin', 'harga' => 15000],
                ],
            ],
        ];

        foreach ($shops as $shopData) {
            if (!$shopData['kabupaten']) {
                continue;
            }

            $toko = Toko::updateOrCreate(
                ['name' => $shopData['name'], 'kabupaten_id' => $shopData['kabupaten']->id],
                [
                    'alamat'    => $shopData['alamat'],
                    'telepon'   => $shopData['telepon'],
                    'jam_buka'  => $shopData['jam_buka'],
                    'deskripsi' => $shopData['deskripsi'],
                    'photo'     => $shopData['photo'],
                    'latitude'  => $shopData['latitude'],
                    'longitude' => $shopData['longitude'],
                    'maps_url'  => $shopData['maps_url'],
                ]
            );

            // Kaitkan kuliner khas dengan harga spesifik
            $syncData = [];
            foreach ($shopData['menus'] as $menu) {
                $kuliner = KulinerKhas::where('name', $menu['name'])->first();
                if ($kuliner) {
                    $syncData[$kuliner->id] = ['harga' => $menu['harga']];
                }
            }

            if (!empty($syncData)) {
                $toko->kulinerKhas()->sync($syncData);
            }
        }
    }
}
