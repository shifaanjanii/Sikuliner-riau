<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KulinerKhas extends Model
{
    use HasFactory;

    protected $table = 'kuliner_khas';

    protected $fillable = [
        'kabupaten_id',
        'name',
        'category',
        'description',
        'photo',
        'source',
    ];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function toko(): BelongsToMany
    {
        return $this->belongsToMany(Toko::class, 'kuliner_toko', 'kuliner_khas_id', 'toko_id')
            ->withPivot('harga');
    }

    public function toPresentationArray(): array
    {
        $data = [
            'id' => (string) $this->id,
            'name' => $this->name,
            'region_id' => $this->kabupaten_id ? (string) $this->kabupaten_id : null,
            'category' => $this->category,
            'description' => $this->description,
            'photo' => $this->photo,
            'source' => $this->source,
        ];

        if ($this->relationLoaded('toko')) {
            $data['shops'] = $this->toko->map(function ($shop) {
                $shopData = $shop->toPresentationArray();
                $shopData['harga'] = $shop->pivot?->harga;
                return $shopData;
            })->all();
        }

        return $data;
    }
}
