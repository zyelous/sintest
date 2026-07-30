<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: RoleMiddleware
 * 
 * Membatasi akses berdasarkan role user.
 * 
 * Penggunaan di route:
 *   middleware('role:admin')           → hanya admin
 *   middleware('role:admin,operator')  → admin dan operator
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  Daftar role yang diizinkan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'username' => 'Sesi Anda dihentikan karena akun telah dinonaktifkan.',
            ]);
        }

        if (!in_array($user->role, $roles)) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman operator.');
            }

            return redirect()->route('operator.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman administrator.');
        }

        return $next($request);
    }
}
