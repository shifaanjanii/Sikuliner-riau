<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Models\Toko;
use App\Support\LaravelFrontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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
                    'menus'     => [],
                ],
            ]
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->shopRules(), $this->shopMessages());
        $menuLinks = $this->menuLinks($request, $validated);

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

        $toko = Toko::create([
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
        if ($menuLinks !== null) {
            $toko->kulinerKhas()->sync($menuLinks);
        }

        return redirect()->route('admin.shops.index')
            ->with('status', 'Toko berhasil ditambahkan.');
    }

    public function edit(Request $request, $id): Response
    {
        $toko = Toko::with('kulinerKhas')->findOrFail($id);
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

        $validated = $request->validate($this->shopRules(), $this->shopMessages());
        $menuLinks = $this->menuLinks($request, $validated);

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
        if ($request->has('latitude')) {
            $toko->latitude = $validated['latitude'] !== '' ? $validated['latitude'] : null;
        }
        if ($request->has('longitude')) {
            $toko->longitude = $validated['longitude'] !== '' ? $validated['longitude'] : null;
        }
        if ($request->has('maps_url')) {
            $toko->maps_url = !empty($validated['maps_url']) ? trim($validated['maps_url']) : null;
        }
        $toko->save();
        if ($menuLinks !== null) {
            $toko->kulinerKhas()->sync($menuLinks);
        }

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

    private function shopRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'region_id' => ['required', 'exists:kabupaten,id'],
            'alamat' => ['required', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'jam_buka' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'maps_url' => ['nullable', 'url', 'max:500'],
            'menu' => ['nullable', 'array'],
            'menu.*.food_id' => ['required', 'integer', 'distinct', 'exists:kuliner_khas,id'],
            'menu.*.selected' => ['nullable', 'boolean'],
            'menu.*.price' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
        ];
    }

    private function shopMessages(): array
    {
        return [
            'name.required' => 'Nama toko wajib diisi.',
            'region_id.required' => 'Pilih kabupaten/kota lokasi toko.',
            'region_id.exists' => 'Kabupaten/kota tidak valid.',
            'alamat.required' => 'Alamat toko wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'latitude.between' => 'Nilai latitude tidak valid.',
            'longitude.between' => 'Nilai longitude tidak valid.',
            'maps_url.url' => 'Tautan Google Maps harus berupa URL yang valid.',
            'menu.*.food_id.exists' => 'Pilihan makanan tidak valid.',
            'menu.*.price.integer' => 'Harga menu harus berupa angka bulat.',
        ];
    }

    private function menuLinks(Request $request, array $validated): ?array
    {
        if (!$request->has('menu')) {
            return null;
        }

        $links = [];
        foreach ($validated['menu'] ?? [] as $index => $menu) {
            if (empty($menu['selected'])) {
                continue;
            }
            if (!array_key_exists('price', $menu) || $menu['price'] === null || $menu['price'] === '') {
                throw ValidationException::withMessages([
                    'menu' => 'Isi harga untuk setiap menu yang dipilih.',
                ]);
            }
            $links[(int) $menu['food_id']] = ['harga' => (int) $menu['price']];
        }

        return $links;
    }
}
