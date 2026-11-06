<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Support\LaravelFrontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function index(Request $request): Response
    {
        $reviews = Ulasan::with(['toko', 'user'])->latest()->get();

        $data = [
            'reviews' => $reviews->map->toPresentationArray()->all(),
            'total_reviews' => $reviews->count(),
            'avg_overall_rating' => round((float) $reviews->avg('rating'), 1),
        ];

        return LaravelFrontend::admin(
            $request,
            $data,
            config('sikuliner-ui'),
            'admin.ulasan.index',
            [
                'title' => 'Moderasi Ulasan Pengunjung — SiKuliner Riau',
                'active' => 'admin.reviews.index',
            ]
        );
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $review = Ulasan::findOrFail($id);
        $shopName = $review->toko?->name ?? 'Toko';
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('status', 'Ulasan untuk "' . $shopName . '" telah berhasil dihapus dari sistem.');
    }
}

