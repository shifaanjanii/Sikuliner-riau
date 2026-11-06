<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Models\PengajuanToko;
use App\Models\Toko;
use App\Models\Ulasan;
use App\Support\LaravelFrontend;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $regions = Kabupaten::withCount(['kulinerKhas', 'toko'])->orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        // Chart data: Distribution of Toko per Kabupaten/Kota
        $chartLabels = [];
        $chartValues = [];
        foreach ($regions as $reg) {
            $shortName = str_replace(['Kabupaten ', 'Kota '], '', $reg->name);
            $chartLabels[] = $shortName;
            $chartValues[] = $reg->toko_count;
        }

        $stats = [
            'total_regions'    => $regions->count(),
            'total_foods'      => KulinerKhas::count(),
            'total_shops'      => Toko::count(),
            'total_promotions' => PengajuanToko::count(),
            'pending_promotions' => PengajuanToko::where('status', 'pending')->count(),
            'total_reviews'    => Ulasan::count(),
            'avg_rating'       => round((float) Ulasan::avg('rating'), 1),
        ];

        $recentPromotions = PengajuanToko::with('kabupaten')->latest()->take(5)->get();
        $recentReviews = Ulasan::with('toko')->latest()->take(5)->get();

        $data = [
            'regions'          => $regionsData,
            'stats'            => $stats,
            'chart_labels'     => $chartLabels,
            'chart_values'     => $chartValues,
            'recent_promotions'=> $recentPromotions->map->toPresentationArray()->all(),
            'recent_reviews'   => $recentReviews->map->toPresentationArray()->all(),
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
