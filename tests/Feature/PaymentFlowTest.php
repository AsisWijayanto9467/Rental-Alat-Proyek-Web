<?php

namespace Tests\Feature;

use App\Models\AlatProyek;
use App\Models\Denda;
use App\Models\DetailPenyewaan;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeRental(User $user, string $status): Penyewaan
    {
        $alat = AlatProyek::factory()->create(['stok' => 10, 'stok_tersedia' => 8]);
        $penyewaan = Penyewaan::factory()->create([
            'user_id' => $user->id,
            'status' => $status,
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addDays(2)->toDateString(),
        ]);

        DetailPenyewaan::create([
            'penyewaan_id' => $penyewaan->id,
            'alat_id' => $alat->id,
            'jumlah' => 2,
            'harga_sewa' => $alat->harga_sewa_harian,
            'subtotal' => $penyewaan->subtotal,
        ]);

        return $penyewaan;
    }

    public function test_user_can_upload_denda_payment_that_stays_pending(): void
    {
        Storage::fake('cross');

        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'selesai');
        $denda = Denda::factory()->create(['penyewaan_id' => $penyewaan->id, 'status' => 'pending']);

        $this->actingAs($user)
            ->post(route('payment.store'), [
                'penyewaan_id' => $penyewaan->id,
                'denda_id' => $denda->id,
                'metode_pembayaran' => 'transfer',
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg', 800, 600),
            ])
            ->assertRedirect(route('customer.rental-detail', $penyewaan->id))
            ->assertSessionHas('success');

        // Upload bukti tidak langsung membayar denda; menunggu verifikasi petugas.
        $this->assertDatabaseHas('pembayarans', [
            'penyewaan_id' => $penyewaan->id,
            'denda_id' => $denda->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('dendas', [
            'id' => $denda->id,
            'status' => 'pending',
        ]);
    }

    public function test_cannot_pay_other_users_pending_denda(): void
    {
        Storage::fake('cross');

        $owner = User::factory()->create();
        $other = User::factory()->create();
        $penyewaan = $this->makeRental($owner, 'selesai');
        $denda = Denda::factory()->create(['penyewaan_id' => $penyewaan->id, 'status' => 'pending']);

        $this->actingAs($other)
            ->post(route('payment.store'), [
                'penyewaan_id' => $penyewaan->id,
                'denda_id' => $denda->id,
                'metode_pembayaran' => 'transfer',
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg', 800, 600),
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('dendas', [
            'id' => $denda->id,
            'status' => 'pending',
        ]);
    }
}
