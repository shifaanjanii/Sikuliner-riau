<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';

    protected $fillable = [
        'name',
        'description',
        'photo',
    ];

    public function kulinerKhas(): HasMany
    {
        return $this->hasMany(KulinerKhas::class, 'kabupaten_id');
    }

    public function toko(): HasMany
    {
        return $this->hasMany(Toko::class, 'kabupaten_id');
    }

    public function toPresentationArray(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description ?? '',
            'photo' => $this->photo,
        ];
    }
}
