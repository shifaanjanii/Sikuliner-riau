<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'toko_id',
        'user_id',
        'nama_pengulas',
        'rating',
        'komentar',
    ];

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toPresentationArray(): array
    {
        return [
            'id' => (string) $this->id,
            'toko_id' => (string) $this->toko_id,
            'toko_name' => $this->toko?->name ?? '',
            'user_id' => $this->user_id ? (string) $this->user_id : null,
            'nama_pengulas' => $this->nama_pengulas,
            'rating' => (int) $this->rating,
            'komentar' => $this->komentar,
            'created_at' => $this->created_at?->diffForHumans() ?? '',
            'formatted_date' => $this->created_at?->format('d M Y, H:i') ?? '',
        ];
    }
}

