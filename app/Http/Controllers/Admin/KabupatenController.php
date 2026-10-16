<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Support\LaravelFrontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KabupatenController extends Controller
{
    public function index(Request $request): Response
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
            'admin.list',
            [
                'title' => 'Kabupaten / Kota',
                'active' => 'regions.index',
                'entity' => 'regions',
                'items' => $regionsData,
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
            'admin.form',
            [
                'title' => 'Tambah Kabupaten / Kota',
                'active' => 'regions.index',
                'entity' => 'regions',
                'edit' => false,
                'item' => [
                    'id' => '',
                    'name' => '',
                    'description' => '',
                    'photo' => null,
                ],
            ]
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:kabupaten,name'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama kabupaten/kota wajib diisi.',
            'name.unique' => 'Nama kabupaten/kota sudah terdaftar.',
            'description.required' => 'Deskripsi budaya atau kuliner daerah wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/regions');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $photoPath = '/uploads/regions/' . $filename;
        }

        Kabupaten::create([
            'name' => trim($validated['name']),
            'description' => trim($validated['description']),
            'photo' => $photoPath,
        ]);

        return redirect()->route('admin.regions.index')
            ->with('status', 'Kabupaten/Kota berhasil ditambahkan.');
    }

    public function edit(Request $request, $id): Response
    {
        $kabupaten = Kabupaten::findOrFail($id);
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.form',
            [
                'title' => 'Edit Kabupaten / Kota',
                'active' => 'regions.index',
                'entity' => 'regions',
                'edit' => true,
                'item' => $kabupaten->toPresentationArray(),
            ]
        );
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $kabupaten = Kabupaten::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:kabupaten,name,' . $kabupaten->id],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama kabupaten/kota wajib diisi.',
            'name.unique' => 'Nama kabupaten/kota sudah terdaftar.',
            'description.required' => 'Deskripsi budaya atau kuliner daerah wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete old uploaded file if present
            if ($kabupaten->photo && str_starts_with($kabupaten->photo, '/uploads/regions/')) {
                $oldFile = public_path(ltrim($kabupaten->photo, '/'));
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/regions');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $kabupaten->photo = '/uploads/regions/' . $filename;
        }

        $kabupaten->name = trim($validated['name']);
        $kabupaten->description = trim($validated['description']);
        $kabupaten->save();

        return redirect()->route('admin.regions.index')
            ->with('status', 'Kabupaten/Kota berhasil diperbarui.');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        $kabupaten = Kabupaten::findOrFail($id);

        // Foreign key relation guard: do not allow deletion if foods are linked
        if ($kabupaten->kulinerKhas()->exists()) {
            return redirect()->route('admin.regions.index')
                ->with('error', 'Tidak dapat menghapus kabupaten ini karena masih memiliki data makanan khas terkait.');
        }

        if ($kabupaten->photo && str_starts_with($kabupaten->photo, '/uploads/regions/')) {
            $oldFile = public_path(ltrim($kabupaten->photo, '/'));
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        $kabupaten->delete();

        return redirect()->route('admin.regions.index')
            ->with('status', 'Kabupaten/Kota berhasil dihapus.');
    }
}
