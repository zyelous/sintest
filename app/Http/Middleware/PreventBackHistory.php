<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: PreventBackHistory
 *
 * Mencegah browser menyimpan cache halaman yang sudah diakses.
 * Mengatasi masalah menekan tombol Back di browser setelah logout/ganti akun
 * yang memperlihatkan kembali halaman akun sebelumnya dari cache browser.
 */
class PreventBackHistory
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
