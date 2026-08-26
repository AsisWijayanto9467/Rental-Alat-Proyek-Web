<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_penyewaan',
        'user_id',
        'tanggal_pengajuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_hari',
        'subtotal',
        'denda',
        'total',
        'status',
        'catatan',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'total_hari' => 'integer',
            'subtotal' => 'decimal:2',
            'denda' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function detailPenyewaans(): HasMany
    {
        return $this->hasMany(DetailPenyewaan::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function dendas(): HasMany
    {
        return $this->hasMany(Denda::class);
    }

    public function generateKode(): string
    {
        $date = now()->format('Ymd');
        $last = self::where('kode_penyewaan', 'like', "PW-$date-%")
            ->orderByDesc('id')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->kode_penyewaan, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return "PW-$date-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
