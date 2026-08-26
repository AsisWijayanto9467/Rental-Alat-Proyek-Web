<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'tanggal_pengembalian',
        'diterima_oleh',
        'kondisi_alat',
        'terlambat_hari',
        'catatan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengembalian' => 'date',
            'terlambat_hari' => 'integer',
        ];
    }

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function diterimaOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }

    public function dendas(): HasMany
    {
        return $this->hasMany(Denda::class, 'pengembalian_id');
    }
}
