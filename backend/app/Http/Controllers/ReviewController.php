<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\Ulasan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, int $tokoId): RedirectResponse
    {
        $toko = Toko::findOrFail($tokoId);

        $validated = $request->validate([
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'komentar'      => ['required', 'string', 'min:5', 'max:1000'],
            'nama_pengulas' => ['nullable', 'string', 'max:100'],
        ], [
            'rating.required' => 'Pilih rating bintang 1 hingga 5.',
            'komentar.required' => 'Tuliskan ulasan atau pengalaman kuliner Anda.',
            'komentar.min' => 'Ulasan minimal terdiri atas 5 karakter.',
        ]);

        $user = Auth::user();
        $namaPengulas = $user ? $user->name : ($validated['nama_pengulas'] ?: 'Wisatawan');

        Ulasan::create([
            'toko_id'       => $toko->id,
            'user_id'       => $user?->id,
            'nama_pengulas' => $namaPengulas,
            'rating'        => (int) $validated['rating'],
            'komentar'      => $validated['komentar'],
        ]);

        return redirect()
            ->to(route('shops.show', ['id' => $toko->id]) . '#ulasan')
            ->with('status', 'Terima kasih atas ulasan dan rating Anda untuk ' . $toko->name . '!');
    }
}

