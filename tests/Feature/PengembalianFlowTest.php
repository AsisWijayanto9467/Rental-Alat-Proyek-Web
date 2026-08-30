<?php

namespace Tests\Feature;

use App\Models\AlatProyek;
use App\Models\DetailPenyewaan;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengembalianFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createRental(User $user, string $status, string $tanggalSelesai): array
    {
        $alat = AlatProyek::factory()->create(['stok' => 5, 'stok_tersedia' => 3]);
        $penyewaan = Penyewaan::factory()->create([
            'user_id' => $user->id,
            'status' => $status,
            'tanggal_mulai' => now()->subDays(5)->toDateString(),
            'tanggal_selesai' => $tanggalSelesai,
            'total_hari' => 5,
            'subtotal' => 1000000,
            'denda' => 0,
            'total' => 1000000,
        ]);

        DetailPenyewaan::create([
            'penyewaan_id' => $penyewaan->id,
            'alat_id' => $alat->id,
            'jumlah' => 2,
            'harga_sewa' => $alat->harga_sewa_harian,
            'subtotal' => 1000000,
        ]);

        return [$penyewaan, $alat];
    }

    public function test_user_can_submit_return_and_it_waits_for_inspection(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        [$penyewaan, $alat] = $this->createRental($user, 'dibayar', now()->toDateString());

        $this->actingAs($user)
            ->from(route('customer.rental-detail', $penyewaan->id))
            ->post(route('pengembalian.store', $penyewaan->id), [
                'tanggal_pengembalian' => now()->toDateString(),
                'kondisi_alat' => 'Baik',
                'foto' => UploadedFile::fake()->image('foto.jpg', 800, 600),
            ])
            ->assertRedirect(route('customer.rental-detail', $penyewaan->id))
            ->assertSessionHas('success');

        // Pengajuan tidak langsung diterima; menunggu inspeksi petugas.
        $this->assertDatabaseHas('pengembalians', [
            'penyewaan_id' => $penyewaan->id,
            'status' => 'menunggu_inspeksi',
            'terlambat_hari' => 0,
        ]);

        $this->assertDatabaseMissing('pengembalians', [
            'penyewaan_id' => $penyewaan->id,
            'diterima_oleh' => $user->id,
        ]);

        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'dibayar',
        ]);

        $this->assertDatabaseHas('alat_proyeks', [
            'id' => $alat->id,
            'stok_tersedia' => 3,
        ]);
    }

    public function test_late_return_records_terlambat_hari(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        [$penyewaan] = $this->createRental($user, 'sedang_disewa', now()->toDateString());

        $this->actingAs($user)
            ->post(route('pengembalian.store', $penyewaan->id), [
                'tanggal_pengembalian' => now()->addDays(2)->toDateString(),
                'kondisi_alat' => 'Baik',
                'foto' => UploadedFile::fake()->image('foto.jpg', 800, 600),
            ])
            ->assertSessionHas('success');

        // Catatan keterlambatan disimpan untuk dicatat petugas saat inspeksi.
        $this->assertDatabaseHas('pengembalians', [
            'penyewaan_id' => $penyewaan->id,
            'status' => 'menunggu_inspeksi',
            'terlambat_hari' => 2,
        ]);
    }

    public function test_user_cannot_return_other_users_rental(): void
    {
        Storage::fake('public');

        $owner = User::factory()->create();
        $other = User::factory()->create();
        [$penyewaan] = $this->createRental($owner, 'dibayar', now()->toDateString());

        $this->actingAs($other)
            ->post(route('pengembalian.store', $penyewaan->id), [
                'tanggal_pengembalian' => now()->toDateString(),
                'foto' => UploadedFile::fake()->image('foto.jpg', 800, 600),
            ])
            ->assertForbidden();
    }

    public function test_user_cannot_return_rental_not_in_active_status(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        [$penyewaan] = $this->createRental($user, 'pending', now()->toDateString());

        $this->actingAs($user)
            ->post(route('pengembalian.store', $penyewaan->id), [
                'tanggal_pengembalian' => now()->toDateString(),
                'foto' => UploadedFile::fake()->image('foto.jpg', 800, 600),
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('pengembalians', 0);
    }
}
