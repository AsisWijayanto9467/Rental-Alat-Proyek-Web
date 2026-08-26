<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'kode_pembayaran',
        'tanggal_pembayaran',
        'jumlah',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pembayaran' => 'date',
            'jumlah' => 'decimal:2',
            'tanggal_verifikasi' => 'datetime',
        ];
    }

    public function penyewaan(): BelongsTo
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function generateKode(): string
    {
        $date = now()->format('Ymd');
        $last = self::where('kode_pembayaran', 'like', "PAY-$date-%")
            ->orderByDesc('id')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->kode_pembayaran, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return "PAY-$date-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
