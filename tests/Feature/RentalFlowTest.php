<?php

namespace Tests\Feature;

use App\Models\AlatProyek;
use App\Models\DetailPenyewaan;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_rental_and_status_is_pending(): void
    {
        $user = User::factory()->create();
        $alat = AlatProyek::factory()->create(['stok' => 5, 'stok_tersedia' => 5]);

        $this->actingAs($user)
            ->post(route('rental.store'), [
                'alat_id' => $alat->id,
                'tanggal_mulai' => now()->addDay()->toDateString(),
                'tanggal_selesai' => now()->addDays(3)->toDateString(),
                'jumlah' => 2,
            ])
            ->assertRedirect();

        $penyewaan = Penyewaan::firstWhere('user_id', $user->id);

        $this->assertNotNull($penyewaan);
        $this->assertSame('pending', $penyewaan->status);

        $this->assertDatabaseHas('alat_proyeks', [
            'id' => $alat->id,
            'stok_tersedia' => 3,
        ]);

        // Saat status pending, tidak boleh ada tombol pembayaran di detail rental.
        $this->actingAs($user)
            ->get(route('customer.rental-detail', $penyewaan->id))
            ->assertOk()
            ->assertDontSee('Lakukan Pembayaran');
    }

    public function test_cannot_rent_more_than_available_stock(): void
    {
        $user = User::factory()->create();
        $alat = AlatProyek::factory()->create(['stok' => 2, 'stok_tersedia' => 1]);

        $this->actingAs($user)
            ->post(route('rental.store'), [
                'alat_id' => $alat->id,
                'tanggal_mulai' => now()->addDay()->toDateString(),
                'tanggal_selesai' => now()->addDays(3)->toDateString(),
                'jumlah' => 5,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseCount('penyewaans', 0);
    }

    public function test_customer_can_cancel_pending_rental_and_restore_stock(): void
    {
        $user = User::factory()->create();
        $alat = AlatProyek::factory()->create(['stok' => 5, 'stok_tersedia' => 3]);
        $penyewaan = Penyewaan::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        DetailPenyewaan::create([
            'penyewaan_id' => $penyewaan->id,
            'alat_id' => $alat->id,
            'jumlah' => 2,
            'harga_sewa' => $alat->harga_sewa_harian,
            'subtotal' => $penyewaan->subtotal,
        ]);

        $this->actingAs($user)
            ->post(route('rental.batal', $penyewaan->id))
            ->assertRedirect(route('customer.rental-detail', $penyewaan->id));

        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'dibatalkan',
        ]);

        $this->assertDatabaseHas('alat_proyeks', [
            'id' => $alat->id,
            'stok_tersedia' => 5,
        ]);
    }

    public function test_customer_cannot_access_other_users_rental(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $alat = AlatProyek::factory()->create(['stok' => 5, 'stok_tersedia' => 3]);
        $penyewaan = Penyewaan::factory()->create(['user_id' => $owner->id, 'status' => 'pending']);

        DetailPenyewaan::create([
            'penyewaan_id' => $penyewaan->id,
            'alat_id' => $alat->id,
            'jumlah' => 2,
            'harga_sewa' => $alat->harga_sewa_harian,
            'subtotal' => $penyewaan->subtotal,
        ]);

        $this->actingAs($other)
            ->get(route('customer.rental-detail', $penyewaan->id))
            ->assertForbidden();

        $this->actingAs($other)
            ->post(route('rental.batal', $penyewaan->id))
            ->assertForbidden();

        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'pending',
        ]);
    }
}
