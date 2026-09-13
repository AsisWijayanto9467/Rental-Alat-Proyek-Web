<?php

namespace Tests\Feature;

use App\Models\AlatProyek;
use App\Models\Denda;
use App\Models\DetailPenyewaan;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VerificationFlowTest extends TestCase
{
    use RefreshDatabase;

    private function signIn(array $attrs = []): User
    {
        return User::factory()->create($attrs);
    }

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

    public function test_approving_rental_moves_to_menunggu_pembayaran(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'pending');

        $this->from(route('verifikasi.dashboard'))
            ->actingAs($admin)
            ->post(route('verifikasi.penyewaan.setujui', $penyewaan->id))
            ->assertRedirect(route('verifikasi.dashboard'));

        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'menunggu_pembayaran',
        ]);
    }

    public function test_rejecting_rental_saves_alasan_and_destroys_itself(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'pending');

        $this->from(route('verifikasi.dashboard'))
            ->actingAs($admin)
            ->post(route('verifikasi.penyewaan.tolak', $penyewaan->id), [
                'alasan_penolakan' => 'Data tidak lengkap',
            ])
            ->assertRedirect(route('verifikasi.dashboard'));

        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'ditolak',
            'alasan_penolakan' => 'Data tidak lengkap',
        ]);
    }

    public function test_user_cannot_upload_payment_without_menunggu_pembayaran_status(): void
    {
        Storage::fake('cross');
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'disetujui');

        $this->actingAs($user)
            ->post(route('payment.store', $penyewaan->id), [
                'penyewaan_id' => $penyewaan->id,
                'metode_pembayaran' => 'transfer',
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg', 800, 600),
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('pembayarans', 0);
    }

    public function test_uploading_payment_keeps_penyewaan_on_menunggu_pembayaran_with_pending_payment(): void
    {
        Storage::fake('cross');
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'menunggu_pembayaran');

        $this->actingAs($user)
            ->post(route('payment.store', $penyewaan->id), [
                'penyewaan_id' => $penyewaan->id,
                'metode_pembayaran' => 'transfer',
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti.jpg', 800, 600),
            ])
            ->assertRedirect(route('customer.rental-detail', $penyewaan->id));

        $this->assertDatabaseHas('pembayarans', [
            'penyewaan_id' => $penyewaan->id,
            'metode_pembayaran' => 'transfer',
            'status' => 'pending',
        ]);

        // Penyewaan tetap menunggu_pembayaran, bukan otomatis dibayar.
        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'menunggu_pembayaran',
        ]);
    }

    public function test_user_cannot_upload_second_payment_while_one_is_pending(): void
    {
        Storage::fake('cross');
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'menunggu_pembayaran');

        Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => 'TRX-TEST-001',
            'tanggal_pembayaran' => now()->toDateString(),
            'jumlah' => $penyewaan->total,
            'metode_pembayaran' => 'transfer',
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->post(route('payment.store', $penyewaan->id), [
                'penyewaan_id' => $penyewaan->id,
                'metode_pembayaran' => 'transfer',
                'bukti_pembayaran' => UploadedFile::fake()->image('bukti2.jpg', 800, 600),
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('pembayarans', 1);
    }

    public function test_verifying_payment_sets_pembayaran_verified_and_penyewaan_dibayar(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'menunggu_pembayaran');

        $pembayaran = Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => 'TRX-TEST-001',
            'tanggal_pembayaran' => now()->toDateString(),
            'jumlah' => $penyewaan->total,
            'metode_pembayaran' => 'transfer',
            'status' => 'pending',
        ]);

        $this->from(route('verifikasi.dashboard'))
            ->actingAs($admin)
            ->post(route('verifikasi.pembayaran.verifikasi', $pembayaran->id))
            ->assertRedirect(route('verifikasi.dashboard'));

        $this->assertDatabaseHas('pembayarans', [
            'id' => $pembayaran->id,
            'status' => 'diverifikasi',
        ]);

        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'dibayar',
        ]);
    }

    public function test_verification_is_idempotent_running_twice_does_not_duplicate(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'menunggu_pembayaran');

        $pembayaran = Pembayaran::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_pembayaran' => 'TRX-TEST-001',
            'tanggal_pembayaran' => now()->toDateString(),
            'jumlah' => $penyewaan->total,
            'metode_pembayaran' => 'transfer',
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->post(route('verifikasi.pembayaran.verifikasi', $pembayaran->id));
        $this->actingAs($admin)
            ->post(route('verifikasi.pembayaran.verifikasi', $pembayaran->id));

        $this->assertDatabaseCount('pembayarans', 1);
        $this->assertSame('diverifikasi', $pembayaran->fresh()->status);
    }

    public function test_accepting_return_marks_completed_without_fine(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'dibayar');

        $pengembalian = Pengembalian::create([
            'penyewaan_id' => $penyewaan->id,
            'tanggal_pengembalian' => now(),
            'terlambat_hari' => 0,
            'status' => 'menunggu_inspeksi',
        ]);

        $this->from(route('verifikasi.dashboard'))
            ->actingAs($admin)
            ->post(route('verifikasi.pengembalian.terima', $pengembalian->id))
            ->assertRedirect(route('verifikasi.dashboard'));

        $this->assertDatabaseHas('pengembalians', [
            'id' => $pengembalian->id,
            'status' => 'diterima',
            'diterima_oleh' => $admin->id,
        ]);

        $this->assertDatabaseHas('penyewaans', [
            'id' => $penyewaan->id,
            'status' => 'selesai',
        ]);

        $this->assertDatabaseCount('dendas', 0);
    }

    public function test_rejecting_return_creates_fine_automatically_without_duplicate(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $penyewaan = $this->makeRental($user, 'dibayar');

        $pengembalian = Pengembalian::create([
            'penyewaan_id' => $penyewaan->id,
            'tanggal_pengembalian' => now(),
            'terlambat_hari' => 2,
            'status' => 'menunggu_inspeksi',
        ]);

        // Jalankan dua kali untuk memastikan tidak duplikat.
        foreach ([1, 2] as $_) {
            $this->actingAs($admin)
                ->post(route('verifikasi.pengembalian.tolak', $pengembalian->id), [
                    'jenis_denda' => 'terlambat',
                    'jumlah' => 50000,
                    'alasan' => 'Terlambat 2 hari',
                ]);
        }

        $this->assertDatabaseHas('pengembalians', [
            'id' => $pengembalian->id,
            'status' => 'ditolak',
        ]);

        $this->assertDatabaseCount('dendas', 1);

        $denda = Denda::first();
        $this->assertSame('pending', $denda->status);
        $this->assertSame('terlambat', $denda->jenis_denda);
        $this->assertSame('50000.00', $denda->jumlah);
        $this->assertSame($penyewaan->id, $denda->penyewaan_id);
    }
}
