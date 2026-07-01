<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Store Bidang
 * 
 * Validasi untuk pembuatan bidang baru.
 */
class StoreBidangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_bidang' => ['required', 'string', 'max:100', 'unique:bidang'],
            'kode_bidang' => ['required', 'string', 'max:20', 'unique:bidang'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
            'nama_bidang.max'      => 'Nama bidang maksimal 100 karakter.',
            'nama_bidang.unique'   => 'Nama bidang sudah digunakan.',
            'kode_bidang.required' => 'Kode bidang wajib diisi.',
            'kode_bidang.max'      => 'Kode bidang maksimal 20 karakter.',
            'kode_bidang.unique'   => 'Kode bidang sudah digunakan.',
        ];
    }
}
