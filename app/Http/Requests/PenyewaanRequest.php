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
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alat_id' => 'required|array|min:1',
            'alat_id.*' => 'exists:alat_proyeks,id',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'alat_id.required' => 'Pilih minimal satu alat.',
            'alat_id.min' => 'Pilih minimal satu alat.',
            'jumlah.*.required' => 'Jumlah wajib diisi.',
            'jumlah.*.min' => 'Jumlah minimal 1.',
        ];
    }
}
