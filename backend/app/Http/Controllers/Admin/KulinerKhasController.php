<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Support\LaravelFrontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KulinerKhasController extends Controller
{
    private array $categories = ['Makanan Utama', 'Kue Tradisional', 'Minuman', 'Oleh-oleh'];

    public function index(Request $request): Response
    {
        $foods = KulinerKhas::with('kabupaten')->orderBy('name')->get();
        $foodsData = $foods->map->toPresentationArray()->all();

        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
            'foods' => $foodsData,
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.list',
            [
                'title' => 'Makanan Khas',
                'active' => 'foods.index',
                'entity' => 'foods',
                'items' => $foodsData,
            ]
        );
    }

    public function create(Request $request): Response
    {
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
            'categories' => $this->categories,
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.form',
            [
                'title' => 'Tambah Makanan Khas',
                'active' => 'foods.index',
                'entity' => 'foods',
                'edit' => false,
                'item' => [
                    'id' => '',
                    'name' => '',
                    'region_id' => '',
                    'category' => '',
                    'description' => '',
                    'photo' => null,
                    'source' => '',
                ],
            ]
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'region_id' => ['required', 'exists:kabupaten,id'],
            'category' => ['required', 'in:' . implode(',', $this->categories)],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'source' => ['nullable', 'url', 'max:255'],
        ], [
            'name.required' => 'Nama makanan khas wajib diisi.',
            'region_id.required' => 'Pilih asal daerah kabupaten/kota.',
            'region_id.exists' => 'Kabupaten/kota asal tidak valid.',
            'category.required' => 'Pilih kategori makanan.',
            'category.in' => 'Kategori yang dipilih tidak valid.',
            'description.required' => 'Sejarah atau deskripsi makanan wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'source.url' => 'Sumber informasi harus berupa URL yang valid.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/foods');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $photoPath = '/uploads/foods/' . $filename;
        }

        KulinerKhas::create([
            'kabupaten_id' => $validated['region_id'],
            'name' => trim($validated['name']),
            'category' => $validated['category'],
            'description' => trim($validated['description']),
            'photo' => $photoPath,
            'source' => !empty($validated['source']) ? trim($validated['source']) : null,
        ]);

        return redirect()->route('admin.foods.index')
            ->with('status', 'Makanan khas berhasil ditambahkan.');
    }

    public function edit(Request $request, $id): Response
    {
        $food = KulinerKhas::findOrFail($id);
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
            'categories' => $this->categories,
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.form',
            [
                'title' => 'Edit Makanan Khas',
                'active' => 'foods.index',
                'entity' => 'foods',
                'edit' => true,
                'item' => $food->toPresentationArray(),
            ]
        );
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $food = KulinerKhas::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'region_id' => ['required', 'exists:kabupaten,id'],
            'category' => ['required', 'in:' . implode(',', $this->categories)],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'source' => ['nullable', 'url', 'max:255'],
        ], [
            'name.required' => 'Nama makanan khas wajib diisi.',
            'region_id.required' => 'Pilih asal daerah kabupaten/kota.',
            'region_id.exists' => 'Kabupaten/kota asal tidak valid.',
            'category.required' => 'Pilih kategori makanan.',
            'category.in' => 'Kategori yang dipilih tidak valid.',
            'description.required' => 'Sejarah atau deskripsi makanan wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'source.url' => 'Sumber informasi harus berupa URL yang valid.',
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if ($food->photo && str_starts_with($food->photo, '/uploads/foods/')) {
                $oldFile = public_path(ltrim($food->photo, '/'));
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/foods');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $food->photo = '/uploads/foods/' . $filename;
        }

        $food->name = trim($validated['name']);
        $food->kabupaten_id = $validated['region_id'];
        $food->category = $validated['category'];
        $food->description = trim($validated['description']);
        $food->source = !empty($validated['source']) ? trim($validated['source']) : null;
        $food->save();

        return redirect()->route('admin.foods.index')
            ->with('status', 'Makanan khas berhasil diperbarui.');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        $food = KulinerKhas::findOrFail($id);

        if ($food->photo && str_starts_with($food->photo, '/uploads/foods/')) {
            $oldFile = public_path(ltrim($food->photo, '/'));
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        $food->delete();

        return redirect()->route('admin.foods.index')
            ->with('status', 'Makanan khas berhasil dihapus.');
    }
}
