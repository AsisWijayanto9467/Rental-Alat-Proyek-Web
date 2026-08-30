<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'user';
    }

    public function rules(): array
    {
        return [
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'denda_id' => 'nullable|exists:dendas,id',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'bukti_pembayaran' => 'required|image|max:2048',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'penyewaan_id.required' => 'Penyewaan wajib dipilih.',
            'penyewaan_id.exists' => 'Penyewaan tidak ditemukan.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
