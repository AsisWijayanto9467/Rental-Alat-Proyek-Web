<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lokasi',
        'alamat',
        'keterangan',
        'status',
    ];

    public function alatProyeks(): HasMany
    {
        return $this->hasMany(AlatProyek::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }
}
