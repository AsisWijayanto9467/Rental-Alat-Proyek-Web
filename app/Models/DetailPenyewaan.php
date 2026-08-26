<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'alat_id',
        'jumlah',
        'harga_sewa',
        'subtotal',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'harga_sewa' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(AlatProyek::class, 'alat_id');
    }
}
