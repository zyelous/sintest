<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Controller: ProfileController
 *
 * Mengelola pembaruan profil & ganti password mandiri untuk pengguna terautentikasi.
 */
class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil & ganti password.
     */
    public function edit(): View
    {
        $user = auth()->user()->load('bidang');
        return view('profile.edit', compact('user'));
    }

    /**
     * Perbarui data profil (nama, username, email).
     */
    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->update($request->validated());

        return redirect()->route('profile.edit')
            ->with('success_profile', 'Data profil berhasil diperbarui.');
    }

    /**
     * Perbarui password pengguna.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return redirect()->route('profile.edit')
            ->with('success_password', 'Password Anda berhasil diperbarui.');
    }
}
