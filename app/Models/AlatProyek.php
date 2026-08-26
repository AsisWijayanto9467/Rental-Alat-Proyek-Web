<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlatProyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_id',
        'lokasi_id',
        'kode_alat',
        'nama_alat',
        'deskripsi',
        'harga_sewa_harian',
        'stok',
        'stok_tersedia',
        'kondisi',
        'status',
        'gambar',
    ];

    protected function casts(): array
    {
        return [
            'harga_sewa_harian' => 'decimal:2',
            'stok' => 'integer',
            'stok_tersedia' => 'integer',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function detailPenyewaans(): HasMany
    {
        return $this->hasMany(DetailPenyewaan::class, 'alat_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'tersedia' && $this->stok_tersedia > 0;
    }
}
