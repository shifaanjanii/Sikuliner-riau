<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use Illuminate\Database\Seeder;

class KabupatenKulinerSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            [
                'name' => 'Kabupaten Bengkalis',
                'description' => 'Kabupaten kepulauan dan pesisir di Riau yang kaya akan tradisi Melayu pesisir, penghasil olahan durian dan kuliner khas laut.',
            ],
            [
                'name' => 'Kabupaten Indragiri Hilir',
                'description' => 'Negeri Seribu Parit dan lumbung kelapa dunia di Riau dengan ragam kuliner khas perairan dan kue tradisional.',
            ],
            [
                'name' => 'Kabupaten Indragiri Hulu',
                'description' => 'Daerah bersejarah bekas pusat Kerajaan Indragiri, terkenal dengan minuman tradisi seperti Air Mata Pengantin.',
            ],
            [
                'name' => 'Kabupaten Kampar',
                'description' => 'Bumi Serambi Mekkah Riau dengan aliran Sungai Kampar yang subur, penghasil patin dan kudapan lepat ketan legendaris.',
            ],
            [
                'name' => 'Kabupaten Kepulauan Meranti',
                'description' => 'Sentra sagu terbesar di Indonesia yang melahirkan beragam inovasi kuliner berbahan dasar sagu seperti mie sagu.',
            ],
            [
                'name' => 'Kabupaten Kuantan Singingi',
                'description' => 'Tuan rumah tradisi Pacu Jalur yang legendaris di sepanjang Sungai Kuantan, memiliki ragam kuliner manis dan lauk tradisi.',
            ],
            [
                'name' => 'Kabupaten Pelalawan',
                'description' => 'Daerah yang dialiri Sungai Kampar dengan fenomena ombak Bono yang mendunia, kaya ragam ikan sungai tawar.',
            ],
            [
                'name' => 'Kabupaten Rokan Hilir',
                'description' => 'Kota bersejarah Bagan Siapi-api dengan kekayaan hasil laut, festival Bakar Tongkang, dan masakan perpaduan pesisir.',
            ],
            [
                'name' => 'Kabupaten Rokan Hulu',
                'description' => 'Negeri Seribu Suluk berbukit indah dengan kekayaan rempah alami serta masakan gulai dan sambal khas pedalaman.',
            ],
            [
                'name' => 'Kabupaten Siak',
                'description' => 'Pusat kejayaan Kesultanan Siak Sri Indrapura dengan istana megah dan sajian kuliner istana seperti asam pedas baung.',
            ],
            [
                'name' => 'Kota Dumai',
                'description' => 'Kota pelabuhan utama di Selat Malaka yang menyajikan kuliner laut segar dan perpaduan cita rasa Melayu pesisir.',
            ],
            [
                'name' => 'Kota Pekanbaru',
                'description' => 'Ibu kota Provinsi Riau yang menjadi pusat bertemunya budaya, oleh-oleh legendaris Bolu Kemojo, dan aneka kuliner Melayu modern.',
            ],
        ];

        $regionModels = [];
        foreach ($regions as $regionData) {
            $regionModels[$regionData['name']] = Kabupaten::firstOrCreate(
                ['name' => $regionData['name']],
                ['description' => $regionData['description']]
            );
        }

        $foods = [
            [
                'region_name' => 'Kota Pekanbaru',
                'name' => 'Bolu Kemojo',
                'category' => 'Kue Tradisional',
                'photo' => 'images/bolu-kemojo.webp',
                'description' => 'Kue tradisional Melayu dengan aroma pandan harum dan bentuk khas menyerupai kelopak bunga kamboja. Bolu kemojo memiliki tekstur padat dan legit, sering disajikan dalam upacara adat dan menjadi oleh-oleh utama dari Pekanbaru.',
                'source' => 'https://ppid.riau.go.id/berita/19506/bolu-kemojo-luky--si-unik-yang-menggugah-selera?page=1',
            ],
            [
                'region_name' => 'Kabupaten Kampar',
                'name' => 'Gulai Ikan Patin',
                'category' => 'Makanan Utama',
                'photo' => 'images/gulai-patin.webp',
                'description' => 'Hidangan ikonik Riau berbahan ikan patin segar dari Sungai Kampar yang dimasak dalam kuah kuning santan berempah gurih, serai, kunyit, dan daun ruku-ruku yang memberi aroma harum khas.',
                'source' => null,
            ],
            [
                'region_name' => 'Kabupaten Kepulauan Meranti',
                'name' => 'Mie Sagu',
                'category' => 'Makanan Utama',
                'photo' => 'images/mie-sagu.webp',
                'description' => 'Mi kenyal berbahan dasar tepung sagu khas Kepulauan Meranti. Dimasak dengan bumbu cabai, tauge, daun kucai, dan teri goreng, mie sagu menjadi alternatif pangan sehat bebas gluten.',
                'source' => null,
            ],
            [
                'region_name' => 'Kabupaten Kampar',
                'name' => 'Lopek Bugi',
                'category' => 'Kue Tradisional',
                'photo' => null,
                'description' => 'Lopek bugi merupakan kudapan lepat ketan berbalut daun pisang khas Kabupaten Kampar. Dahulu disajikan untuk jamuan bangsawan kerajaan, kini dinikmati oleh seluruh kalangan masyarakat luas.',
                'source' => 'https://tppkk.riau.go.id/post/lopek-bugi--makanan-bangsawan-yang-kini-digemari-semua-kalangan',
            ],
            [
                'region_name' => 'Kabupaten Siak',
                'name' => 'Asam Pedas Baung',
                'category' => 'Makanan Utama',
                'photo' => null,
                'description' => 'Olahan ikan baung sungai bertekstur lembut tanpa sisik yang dimasak kuah asam pedas segar menggunakan asam kandis dan cabai merah khas masakan istana Siak.',
                'source' => null,
            ],
            [
                'region_name' => 'Kabupaten Bengkalis',
                'name' => 'Roti Jala Cecah Kari',
                'category' => 'Makanan Utama',
                'photo' => null,
                'description' => 'Kudapan jala renda khas Melayu pesisir Bengkalis yang disantap dengan kuah kari kental berdaging empuk dan kaya rempah cengkeh dan kapulaga.',
                'source' => null,
            ],
            [
                'region_name' => 'Kabupaten Indragiri Hulu',
                'name' => 'Air Mata Pengantin',
                'category' => 'Minuman',
                'photo' => null,
                'description' => 'Minuman manis segar berisi agar-agar serut warna-warni, biji selasih, blewah, dan sirup manis dingin yang dahulu wajib ada saat perhelatan pernikahan adat.',
                'source' => null,
            ],
            [
                'region_name' => 'Kabupaten Bengkalis',
                'name' => 'Lempuk Durian',
                'category' => 'Oleh-oleh',
                'photo' => null,
                'description' => 'Penganan manis mirip dodol yang dibuat murni dari daging durian asli berkualitas tinggi dan gula tanpa campuran tepung, menjadi cinderamata khas Bengkalis.',
                'source' => null,
            ],
        ];

        foreach ($foods as $food) {
            $region = $regionModels[$food['region_name']] ?? null;
            KulinerKhas::firstOrCreate(
                ['name' => $food['name']],
                [
                    'kabupaten_id' => $region?->id,
                    'category' => $food['category'],
                    'photo' => $food['photo'],
                    'description' => $food['description'],
                    'source' => $food['source'],
                ]
            );
        }
    }
}
