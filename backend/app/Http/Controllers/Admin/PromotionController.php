<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KulinerKhas;
use App\Models\PengajuanToko;
use App\Models\Toko;
use App\Support\LaravelFrontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromotionController extends Controller
{
    public function index(Request $request): Response
    {
        $status = $request->query('status', 'all');

        $query = PengajuanToko::with('kabupaten')->latest();
        if ($status !== 'all' && in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $submissions = $query->get();

        $data = [
            'submissions' => $submissions->map->toPresentationArray()->all(),
            'current_status' => $status,
            'counts' => [
                'all' => PengajuanToko::count(),
                'pending' => PengajuanToko::where('status', 'pending')->count(),
                'approved' => PengajuanToko::where('status', 'approved')->count(),
                'rejected' => PengajuanToko::where('status', 'rejected')->count(),
            ],
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.pengajuan.index',
            [
                'title' => 'Moderasi Pengajuan Promosi Toko — SiKuliner Riau',
                'active' => 'admin.promotions.index',
            ]
        );
    }

    public function approve(Request $request, int $id): RedirectResponse
    {
        $submission = PengajuanToko::findOrFail($id);

        // Publish to direct public directory (toko table)
        $toko = Toko::create([
            'kabupaten_id' => $submission->kabupaten_id,
            'name'         => $submission->nama_toko,
            'alamat'       => $submission->alamat,
            'telepon'      => $submission->telepon,
            'jam_buka'     => $submission->jam_buka,
            'deskripsi'    => $submission->deskripsi,
            'photo'        => $submission->photo,
            'latitude'     => $submission->latitude,
            'longitude'    => $submission->longitude,
            'maps_url'     => $submission->maps_url,
        ]);

        // Auto-match kuliner if present in that kabupaten
        if (!empty($submission->nama_makanan)) {
            $food = KulinerKhas::where('kabupaten_id', $submission->kabupaten_id)
                ->where('name', 'like', '%' . trim($submission->nama_makanan) . '%')
                ->first();
            if ($food) {
                $toko->kulinerKhas()->syncWithoutDetaching([$food->id => ['harga' => null]]);
            }
        }

        $submission->status = 'approved';
        $submission->catatan_admin = 'Disetujui oleh Administrator pada ' . now()->format('d M Y, H:i');
        $submission->save();

        return redirect()
            ->route('admin.promotions.index')
            ->with('status', 'Pengajuan toko "' . $submission->nama_toko . '" berhasil disetujui dan telah otomatis diterbitkan ke Direktori Toko publik!');
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $submission = PengajuanToko::findOrFail($id);
        $reason = $request->input('catatan_admin', 'Data yang diajukan belum memenuhi kriteria kelengkapan informasi.');

        $submission->status = 'rejected';
        $submission->catatan_admin = $reason;
        $submission->save();

        return redirect()
            ->route('admin.promotions.index')
            ->with('status', 'Pengajuan toko "' . $submission->nama_toko . '" telah ditolak.');
    }
}

