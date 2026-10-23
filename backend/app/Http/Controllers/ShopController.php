<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Toko;
use App\Support\LaravelFrontend;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShopController extends Controller
{
    public function index(Request $request): Response
    {
        $regions = Kabupaten::orderBy('name')->get();
        $regionId = trim((string) $request->query('region', ''));
        $selectedRegion = $regionId === '' ? null : $regions->firstWhere('id', (int) $regionId);

        $shops = collect();
        if ($selectedRegion) {
            $shops = Toko::with(['kabupaten', 'kulinerKhas'])
                ->where('kabupaten_id', $selectedRegion->id)
                ->orderBy('name')
                ->get();
        }

        $data = [
            'regions' => $regions->map->toPresentationArray()->all(),
            'shops' => $shops->map->toPresentationArray()->all(),
            'region' => $selectedRegion ? (string) $selectedRegion->id : '',
            'selected_region' => $selectedRegion?->toPresentationArray(),
        ];

        $ui = LaravelFrontend::make($request, $data, config('sikuliner-ui'));

        return response($ui->render('shops.index', [
            'title' => 'Direktori Toko - SiKuliner Riau',
            'active' => 'shops.browse',
        ], 'public'));
    }

    public function show(Request $request, $id): Response
    {
        $shop = Toko::with(['kabupaten', 'kulinerKhas'])->findOrFail($id);
        $regions = Kabupaten::orderBy('name')->get();
        $regionId = trim((string) $request->query('region', $shop->kabupaten_id));

        $data = [
            'regions' => $regions->map->toPresentationArray()->all(),
            'shop' => $shop->toPresentationArray(),
            'region' => $regionId,
        ];

        $ui = LaravelFrontend::make($request, $data, config('sikuliner-ui'));

        return response($ui->render('shops.detail', [
            'title' => $shop->name . ' - SiKuliner Riau',
            'active' => 'shops.browse',
            'shop' => $shop->toPresentationArray(),
        ], 'public'));
    }
}
