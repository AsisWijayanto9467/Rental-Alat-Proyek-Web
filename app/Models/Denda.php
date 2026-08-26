<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'pengembalian_id',
        'jenis_denda',
        'jumlah',
        'alasan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
        ];
    }

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class);
    }
}
