<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\PengajuanToko;
use App\Support\LaravelFrontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromotionController extends Controller
{
    public function create(Request $request): Response
    {
        $regions = Kabupaten::orderBy('name')->get();

        $data = [
            'regions' => $regions->map->toPresentationArray()->all(),
        ];

        $ui = LaravelFrontend::make($request, $data, config('sikuliner-ui'));

        return response($ui->render('promotion.create', [
            'title' => 'Promosikan Toko Anda — SiKuliner Riau',
            'active' => 'promotion.create',
        ], 'public'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pemilik' => ['nullable', 'string', 'max:100'],
            'nama_toko'    => ['required', 'string', 'max:150'],
            'nama_makanan' => ['nullable', 'string', 'max:200'],
            'kabupaten_id' => ['required', 'exists:kabupaten,id'],
            'alamat'       => ['required', 'string', 'max:255'],
            'telepon'      => ['nullable', 'string', 'max:30'],
            'jam_buka'     => ['nullable', 'string', 'max:100'],
            'deskripsi'    => ['nullable', 'string', 'max:1000'],
            'latitude'     => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'    => ['nullable', 'numeric', 'between:-180,180'],
            'maps_url'     => ['nullable', 'string', 'max:500'],
            'photo'        => ['nullable', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
        ], [
            'nama_toko.required' => 'Nama tempat makan / toko wajib diisi.',
            'kabupaten_id.required' => 'Pilih asal kabupaten/kota.',
            'alamat.required' => 'Alamat jalan wajib dicantumkan.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'photo.image' => 'File harus berupa gambar (JPEG, PNG, atau WebP).',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('promosi', 'public');
        }

        PengajuanToko::create([
            'kabupaten_id' => $validated['kabupaten_id'],
            'nama_pemilik' => $validated['nama_pemilik'] ?? null,
            'nama_toko'    => $validated['nama_toko'],
            'nama_makanan' => $validated['nama_makanan'] ?? null,
            'alamat'       => $validated['alamat'],
            'telepon'      => $validated['telepon'] ?? null,
            'jam_buka'     => $validated['jam_buka'] ?? null,
            'deskripsi'    => $validated['deskripsi'] ?? null,
            'latitude'     => $validated['latitude'] ?? null,
            'longitude'    => $validated['longitude'] ?? null,
            'maps_url'     => $validated['maps_url'] ?? null,
            'photo'        => $photoPath,
            'status'       => 'pending',
        ]);

        return redirect()
            ->route('promotion.create')
            ->with('status', 'Terima kasih! Pengajuan promosi toko Anda telah berhasil dikirim dan sedang menunggu moderasi oleh Administrator.');
    }
}

