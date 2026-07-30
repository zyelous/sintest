<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request: Update Profile
 *
 * Validasi untuk pembaruan data profil pengguna mandiri.
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($userId)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'name.max'            => 'Nama lengkap maksimal 255 karakter.',
            'username.required'   => 'Username wajib diisi.',
            'username.max'        => 'Username maksimal 50 karakter.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'username.unique'     => 'Username ini sudah digunakan oleh pengguna lain.',
            'email.required'      => 'Alamat email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Alamat email ini sudah digunakan oleh pengguna lain.',
        ];
    }
}
