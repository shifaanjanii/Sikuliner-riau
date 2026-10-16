<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Toko;
use App\Support\LaravelFrontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TokoController extends Controller
{
    public function index(Request $request): Response
    {
        $shops = Toko::with('kabupaten')->orderBy('name')->get();
        $shopsData = $shops->map->toPresentationArray()->all();

        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
            'shops'   => $shopsData,
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.toko.list',
            [
                'title'  => 'Direktori Toko',
                'active' => 'shops.index',
                'entity' => 'shops',
                'items'  => $shopsData,
            ]
        );
    }

    public function create(Request $request): Response
    {
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.toko.form',
            [
                'title'  => 'Tambah Toko / Rumah Makan',
                'active' => 'shops.index',
                'entity' => 'shops',
                'edit'   => false,
                'item'   => [
                    'id'        => '',
                    'name'      => '',
                    'region_id' => '',
                    'alamat'    => '',
                    'telepon'   => '',
                    'jam_buka'  => '',
                    'deskripsi' => '',
                    'photo'     => null,
                    'latitude'  => '',
                    'longitude' => '',
                    'maps_url'  => '',
                ],
            ]
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'region_id' => ['required', 'exists:kabupaten,id'],
            'alamat'    => ['required', 'string', 'max:255'],
            'telepon'   => ['nullable', 'string', 'max:30'],
            'jam_buka'  => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'photo'     => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'maps_url'  => ['nullable', 'url', 'max:500'],
        ], [
            'name.required'      => 'Nama toko wajib diisi.',
            'region_id.required' => 'Pilih kabupaten/kota lokasi toko.',
            'region_id.exists'   => 'Kabupaten/kota tidak valid.',
            'alamat.required'    => 'Alamat toko wajib diisi.',
            'photo.image'        => 'File harus berupa gambar.',
            'photo.mimes'        => 'Format gambar harus JPEG, PNG, atau WebP.',
            'photo.max'          => 'Ukuran foto maksimal 2 MB.',
            'latitude.between'   => 'Nilai latitude tidak valid.',
            'longitude.between'  => 'Nilai longitude tidak valid.',
            'maps_url.url'       => 'Tautan Google Maps harus berupa URL yang valid.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/shops');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $photoPath = '/uploads/shops/' . $filename;
        }

        Toko::create([
            'kabupaten_id' => $validated['region_id'],
            'name'         => trim($validated['name']),
            'alamat'       => trim($validated['alamat']),
            'telepon'      => !empty($validated['telepon']) ? trim($validated['telepon']) : null,
            'jam_buka'     => !empty($validated['jam_buka']) ? trim($validated['jam_buka']) : null,
            'deskripsi'    => !empty($validated['deskripsi']) ? trim($validated['deskripsi']) : null,
            'photo'        => $photoPath,
            'latitude'     => isset($validated['latitude']) && $validated['latitude'] !== '' ? $validated['latitude'] : null,
            'longitude'    => isset($validated['longitude']) && $validated['longitude'] !== '' ? $validated['longitude'] : null,
            'maps_url'     => !empty($validated['maps_url']) ? trim($validated['maps_url']) : null,
        ]);

        return redirect()->route('admin.shops.index')
            ->with('status', 'Toko berhasil ditambahkan.');
    }

    public function edit(Request $request, $id): Response
    {
        $toko = Toko::findOrFail($id);
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.toko.form',
            [
                'title'  => 'Edit Toko / Rumah Makan',
                'active' => 'shops.index',
                'entity' => 'shops',
                'edit'   => true,
                'item'   => $toko->toPresentationArray(),
            ]
        );
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $toko = Toko::findOrFail($id);

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'region_id' => ['required', 'exists:kabupaten,id'],
            'alamat'    => ['required', 'string', 'max:255'],
            'telepon'   => ['nullable', 'string', 'max:30'],
            'jam_buka'  => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'photo'     => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'maps_url'  => ['nullable', 'url', 'max:500'],
        ], [
            'name.required'      => 'Nama toko wajib diisi.',
            'region_id.required' => 'Pilih kabupaten/kota lokasi toko.',
            'region_id.exists'   => 'Kabupaten/kota tidak valid.',
            'alamat.required'    => 'Alamat toko wajib diisi.',
            'photo.image'        => 'File harus berupa gambar.',
            'photo.mimes'        => 'Format gambar harus JPEG, PNG, atau WebP.',
            'photo.max'          => 'Ukuran foto maksimal 2 MB.',
            'latitude.between'   => 'Nilai latitude tidak valid.',
            'longitude.between'  => 'Nilai longitude tidak valid.',
            'maps_url.url'       => 'Tautan Google Maps harus berupa URL yang valid.',
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if ($toko->photo && str_starts_with($toko->photo, '/uploads/shops/')) {
                $oldFile = public_path(ltrim($toko->photo, '/'));
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/shops');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $toko->photo = '/uploads/shops/' . $filename;
        }

        $toko->kabupaten_id = $validated['region_id'];
        $toko->name         = trim($validated['name']);
        $toko->alamat       = trim($validated['alamat']);
        $toko->telepon      = !empty($validated['telepon']) ? trim($validated['telepon']) : null;
        $toko->jam_buka     = !empty($validated['jam_buka']) ? trim($validated['jam_buka']) : null;
        $toko->deskripsi    = !empty($validated['deskripsi']) ? trim($validated['deskripsi']) : null;
        $toko->latitude     = isset($validated['latitude']) && $validated['latitude'] !== '' ? $validated['latitude'] : null;
        $toko->longitude    = isset($validated['longitude']) && $validated['longitude'] !== '' ? $validated['longitude'] : null;
        $toko->maps_url     = !empty($validated['maps_url']) ? trim($validated['maps_url']) : null;
        $toko->save();

        return redirect()->route('admin.shops.index')
            ->with('status', 'Toko berhasil diperbarui.');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        $toko = Toko::findOrFail($id);

        if ($toko->photo && str_starts_with($toko->photo, '/uploads/shops/')) {
            $oldFile = public_path(ltrim($toko->photo, '/'));
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        $toko->delete();

        return redirect()->route('admin.shops.index')
            ->with('status', 'Toko berhasil dihapus.');
    }
}
