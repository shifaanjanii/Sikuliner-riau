<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Support\LaravelFrontend;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CatalogController extends Controller
{
    public function index(Request $request): Response
    {
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $featuredFoods = KulinerKhas::whereNotNull('photo')
            ->orderBy('id')
            ->take(3)
            ->get();
        $featuredData = $featuredFoods->map(function ($food) {
            return [
                'name' => $food->name,
                'category' => $food->category,
                'photo' => $food->photo,
                'caption' => 'Rasa khas ' . ($food->kabupaten?->name ?? 'Provinsi Riau'),
            ];
        })->all();

        $q = trim((string) $request->query('q', ''));
        $regionId = trim((string) $request->query('region', ''));
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 6;

        $foodQuery = KulinerKhas::with('kabupaten');
        if ($regionId !== '') {
            $foodQuery->where('kabupaten_id', $regionId);
        }
        if ($q !== '') {
            $foodQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $total = $foodQuery->count();
        $pages = max(1, (int) ceil($total / $perPage));
        if ($page > $pages) {
            $page = $pages;
        }

        $foods = $foodQuery->orderBy('id')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        $foodsData = $foods->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
            'foods' => $foodsData,
            'featured' => $featuredData,
            'categories' => ['Makanan Utama', 'Kue Tradisional', 'Minuman', 'Oleh-oleh'],
            'q' => $q,
            'region' => $regionId,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ];

        $ui = LaravelFrontend::make($request, $data, config('sikuliner-ui'));

        return response($ui->render('catalog', [
            'title' => 'Jelajah Kuliner — SiKuliner Riau',
            'active' => 'catalog',
        ], 'public'));
    }

    public function show(Request $request, $id): Response
    {
        $food = KulinerKhas::with(['kabupaten', 'toko.kabupaten'])->findOrFail($id);

        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $q = trim((string) $request->query('q', ''));
        $regionId = trim((string) $request->query('region', ''));
        $page = max(1, (int) $request->query('page', 1));

        $data = [
            'regions' => $regionsData,
            'food' => $food->toPresentationArray(),
            'q' => $q,
            'region' => $regionId,
            'page' => $page,
        ];

        $ui = LaravelFrontend::make($request, $data, config('sikuliner-ui'));

        return response($ui->render('detail', [
            'title' => $food->name . ' — SiKuliner Riau',
            'active' => 'catalog',
            'food' => $food->toPresentationArray(),
        ], 'public'));
    }
}
