<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanToko extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_toko';

    protected $fillable = [
        'kabupaten_id',
        'nama_pemilik',
        'nama_toko',
        'nama_makanan',
        'alamat',
        'telepon',
        'jam_buka',
        'deskripsi',
        'photo',
        'latitude',
        'longitude',
        'maps_url',
        'catatan_admin',
        'status',
    ];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    public function toPresentationArray(): array
    {
        return [
            'id' => (string) $this->id,
            'nama_pemilik' => $this->nama_pemilik,
            'nama_toko' => $this->nama_toko,
            'nama_makanan' => $this->nama_makanan,
            'kabupaten_id' => (string) $this->kabupaten_id,
            'region_name' => $this->kabupaten?->name ?? '',
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'jam_buka' => $this->jam_buka,
            'deskripsi' => $this->deskripsi ?? '',
            'photo' => $this->photo,
            'latitude' => $this->latitude ? (float) $this->latitude : null,
            'longitude' => $this->longitude ? (float) $this->longitude : null,
            'maps_url' => $this->maps_url,
            'status' => $this->status,
            'catatan_admin' => $this->catatan_admin,
            'created_at' => $this->created_at?->format('d M Y, H:i'),
        ];
    }
}

