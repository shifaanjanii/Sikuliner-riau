<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Models\Toko;
use App\Support\LaravelFrontend;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions'      => $regionsData,
            'region_count' => Kabupaten::count(),
            'food_count'   => KulinerKhas::count(),
            'shop_count'   => Toko::count(),
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.home',
            [
                'title' => 'Dashboard Pengelola — SiKuliner Riau',
                'active' => 'admin.home',
            ]
        );
    }
}
