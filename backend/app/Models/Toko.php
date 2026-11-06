<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'toko';

    protected $fillable = [
        'kabupaten_id',
        'name',
        'alamat',
        'telepon',
        'jam_buka',
        'deskripsi',
        'photo',
        'latitude',
        'longitude',
        'maps_url',
    ];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function kulinerKhas(): BelongsToMany
    {
        return $this->belongsToMany(KulinerKhas::class, 'kuliner_toko', 'toko_id', 'kuliner_khas_id')
            ->withPivot('harga');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(Ulasan::class, 'toko_id')->latest();
    }

    public function averageRating(): float
    {
        return round((float) $this->ulasan()->avg('rating'), 1);
    }

    public function reviewsCount(): int
    {
        return (int) $this->ulasan()->count();
    }

    public function toPresentationArray(): array
    {
        $avg = $this->averageRating();
        $count = $this->reviewsCount();

        $data = [
            'id'             => (string) $this->id,
            'name'           => $this->name,
            'region_id'      => $this->kabupaten_id ? (string) $this->kabupaten_id : null,
            'region_name'    => $this->kabupaten?->name ?? '',
            'alamat'         => $this->alamat,
            'telepon'        => $this->telepon,
            'jam_buka'       => $this->jam_buka,
            'deskripsi'      => $this->deskripsi ?? '',
            'photo'          => $this->photo,
            'latitude'       => $this->latitude ? (float) $this->latitude : null,
            'longitude'      => $this->longitude ? (float) $this->longitude : null,
            'maps_url'       => $this->maps_url,
            'average_rating' => $avg > 0 ? $avg : null,
            'reviews_count'  => $count,
        ];

        if ($this->relationLoaded('kulinerKhas')) {
            $data['menus'] = $this->kulinerKhas->map(static function (KulinerKhas $food): array {
                return [
                    'id' => (string) $food->id,
                    'name' => $food->name,
                    'category' => $food->category,
                    'price' => $food->pivot?->harga,
                ];
            })->values()->all();
        }

        if ($this->relationLoaded('ulasan')) {
            $data['reviews'] = $this->ulasan->map->toPresentationArray()->all();
        }

        return $data;
    }
}
