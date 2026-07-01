<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request: Update Bidang
 * 
 * Validasi untuk pembaruan data bidang.
 * Unique constraint mengecualikan record yang sedang diedit.
 */
class UpdateBidangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bidangId = $this->route('bidang')->id ?? $this->route('bidang');

        return [
            'nama_bidang' => ['required', 'string', 'max:100', Rule::unique('bidang')->ignore($bidangId)],
            'kode_bidang' => ['required', 'string', 'max:20', Rule::unique('bidang')->ignore($bidangId)],
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
