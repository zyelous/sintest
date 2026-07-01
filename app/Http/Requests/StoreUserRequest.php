<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request: Store User
 * 
 * Validasi untuk pembuatan user baru.
 * Bidang wajib diisi jika role = operator.
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'string', 'max:255', 'unique:users'],
            'email'     => ['required', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'role'      => ['required', 'in:admin,operator'],
            'bidang_id' => ['required_if:role,operator', 'nullable', 'exists:bidang,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Nama wajib diisi.',
            'name.max'              => 'Nama maksimal 255 karakter.',
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah digunakan.',
            'username.max'          => 'Username maksimal 255 karakter.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah digunakan.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'role.required'         => 'Role wajib dipilih.',
            'role.in'               => 'Role tidak valid.',
            'bidang_id.required_if' => 'Bidang wajib dipilih untuk role operator.',
            'bidang_id.exists'      => 'Bidang tidak ditemukan.',
        ];
    }
}
