<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        // Cek dulu apakah username ada dan aktif
        $user = \App\Models\User::where('username', $credentials['username'])->first();

        if ($user && !$user->is_active) {
            return back()->withErrors([
                'username' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Administrator.',
            ])->onlyInput('username');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user())
                ->with('success', 'Selamat datang, '.Auth::user()->name.'!');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil logout.');
    }

    public function requestResetPassword(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'alasan'   => ['nullable', 'string', 'max:255'],
        ], [
            'username.required' => 'Username wajib diisi.',
        ]);

        $user = \App\Models\User::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('reset_error', 'Username tidak ditemukan dalam sistem.');
        }

        if ($user->isAdmin()) {
            return back()->with('reset_error', 'Permintaan reset password via konfirmasi ini khusus untuk akun Operator. Akun Admin dapat direset langsung.');
        }

        $existing = \App\Models\PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('reset_info', 'Permintaan reset password Anda sudah ada dan sedang menunggu konfirmasi Admin.');
        }

        \App\Models\PasswordResetRequest::create([
            'user_id'  => $user->id,
            'username' => $user->username,
            'alasan'   => $request->alasan,
            'status'   => 'pending',
        ]);

        return back()->with('reset_success', 'Permintaan reset password berhasil dikirim ke Administrator. Silakan hubungi Admin untuk konfirmasi.');
    }

    private function redirectByRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('operator.dashboard');
    }
}