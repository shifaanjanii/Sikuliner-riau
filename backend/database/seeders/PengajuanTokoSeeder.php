<?php

namespace Database\Seeders;

use App\Models\Kabupaten;
use App\Models\PengajuanToko;
use Illuminate\Database\Seeder;

class PengajuanTokoSeeder extends Seeder
{
    public function run(): void
    {
        $pekanbaru = Kabupaten::where('name', 'like', '%Pekanbaru%')->first();
        $kampar = Kabupaten::where('name', 'like', '%Kampar%')->first();
        $siak = Kabupaten::where('name', 'like', '%Siak%')->first();
        $bengkalis = Kabupaten::where('name', 'like', '%Bengkalis%')->first();

        $samples = [
            [
                'kabupaten_id' => $pekanbaru?->id ?? 12,
                'nama_pemilik' => 'H. Ahmad Syafi\'i',
                'nama_toko' => 'Gerai Bolu Kemojo Melayu Delima',
                'nama_makanan' => 'Bolu Kemojo Pandan Wangi',
                'alamat' => 'Jl. Delima No. 45, Tampan, Kota Pekanbaru',
                'telepon' => '081276543210',
                'jam_buka' => '07.00 - 21.00 WIB',
                'deskripsi' => 'Menyediakan bolu kemojo khas Melayu Riau segar langsung dari panggangan oven tradisional dengan aroma pandan asli.',
                'photo' => null,
                'latitude' => 0.4851230,
                'longitude' => 101.4123450,
                'maps_url' => 'https://maps.google.com/?q=0.485123,101.412345',
                'status' => 'pending',
                'catatan_admin' => null,
            ],
            [
                'kabupaten_id' => $kampar?->id ?? 4,
                'nama_pemilik' => 'Ibu Fatimah',
                'nama_toko' => 'Dapur Lopek Bugi Danau Bingkuang',
                'nama_makanan' => 'Lopek Bugi Pulut Hitam & Putih',
                'alamat' => 'Jl. Lintas Pekanbaru - Bangkinang Km. 38, Danau Bingkuang, Kampar',
                'telepon' => '082165439870',
                'jam_buka' => '06.30 - 18.00 WIB',
                'deskripsi' => 'Pusat pembuatan lopek bugi legendaris di tepi Sungai Kampar, dibungkus daun pisang segar dengan isi kelapa gula merah legit.',
                'photo' => null,
                'latitude' => 0.3541200,
                'longitude' => 101.2789000,
                'maps_url' => 'https://maps.google.com/?q=0.35412,101.2789',
                'status' => 'pending',
                'catatan_admin' => null,
            ],
            [
                'kabupaten_id' => $siak?->id ?? 10,
                'nama_pemilik' => 'Datuk Marzuki',
                'nama_toko' => 'Kedai Asam Pedas Patin Sungai Siak',
                'nama_makanan' => 'Asam Pedas Ikan Baung & Patin',
                'alamat' => 'Jl. Sultan Syarif Kasim No. 12, Siak Sri Indrapura',
                'telepon' => '081378901234',
                'jam_buka' => '09.00 - 17.00 WIB',
                'deskripsi' => 'Kuliner khas istana Siak dengan kuah asam pedas kental bumbu rempah asli Melayu dan ikan tangkapan sungai segar.',
                'photo' => null,
                'latitude' => 0.7934500,
                'longitude' => 102.0456000,
                'maps_url' => 'https://maps.google.com/?q=0.79345,102.0456',
                'status' => 'approved',
                'catatan_admin' => 'Disetujui. Data telah sesuai dengan verifikasi lapangan.',
            ],
            [
                'kabupaten_id' => $bengkalis?->id ?? 1,
                'nama_pemilik' => 'Pak Usman',
                'nama_toko' => 'Warung Lempuk Durian Selat Bengkalis',
                'nama_makanan' => 'Lempuk Durian Asli Tanpa Pengawet',
                'alamat' => 'Jl. Jenderal Sudirman No. 88, Bengkalis Kota',
                'telepon' => '085298761234',
                'jam_buka' => '08.00 - 20.00 WIB',
                'deskripsi' => 'Oleh-oleh lempuk durian khas Pulau Bengkalis yang dibuat dari daging durian tembaga pilihan 100% murni.',
                'photo' => null,
                'latitude' => 1.4823000,
                'longitude' => 102.1294000,
                'maps_url' => 'https://maps.google.com/?q=1.4823,102.1294',
                'status' => 'pending',
                'catatan_admin' => null,
            ],
        ];

        foreach ($samples as $sample) {
            PengajuanToko::updateOrCreate(
                ['nama_toko' => $sample['nama_toko']],
                $sample
            );
        }
    }
}

