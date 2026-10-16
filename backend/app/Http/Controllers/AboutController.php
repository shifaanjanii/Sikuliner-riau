<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Support\LaravelFrontend;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AboutController extends Controller
{
    public function index(Request $request): Response
    {
        $regions = Kabupaten::orderBy('name')->get();
        $regionsData = $regions->map->toPresentationArray()->all();

        $data = [
            'regions' => $regionsData,
        ];

        $ui = LaravelFrontend::make($request, $data, config('sikuliner-ui'));

        return response($ui->render('about', [
            'title' => 'Tentang SiKuliner Riau',
            'active' => 'about',
        ], 'public'));
    }
}
