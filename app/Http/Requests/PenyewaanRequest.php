<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenyewaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'user';
    }

    public function rules(): array
    {
        return [
            'alat_id' => 'required|exists:alat_proyeks,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'alat_id.required' => 'Pilih alat yang akan disewa.',
            'alat_id.exists' => 'Alat tidak ditemukan.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
        ];
    }
}
