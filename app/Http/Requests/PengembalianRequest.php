<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengembalianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'user';
    }

    public function rules(): array
    {
        return [
            'tanggal_pengembalian' => 'required|date',
            'foto' => 'required|image|max:5120',
            'kondisi_alat' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi.',
            'foto.required' => 'Foto kondisi alat wajib diunggah.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 5MB.',
        ];
    }
}
