<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Header keamanan dasar untuk semua respons. */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        if ($request->isSecure()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        if (config('security.csp')) {
            // Leaflet (halaman Peta Desa) dimuat dari unpkg.com; tile peta dari OpenStreetMap.
            $isMap = $request->is('peta-desa');
            $headers->set('Content-Security-Policy', implode('; ', [
                "default-src 'self'",
                $isMap ? "script-src 'self' https://unpkg.com" : "script-src 'self'",
                $isMap ? "style-src 'self' 'unsafe-inline' https://unpkg.com" : "style-src 'self' 'unsafe-inline'", // beberapa style="" inline di Blade
                $isMap ? "img-src 'self' data: blob: https://tile.openstreetmap.org https://unpkg.com" : "img-src 'self' data: blob:",
                "font-src 'self'",
                "connect-src 'self'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "frame-ancestors 'self'",
            ]));
        }

        // Halaman admin tidak boleh tersimpan di cache/riwayat (tombol Back setelah logout)
        if ($request->is('admin', 'admin/*')) {
            $headers->set('Cache-Control', 'no-store, private');
        }

        return $response;
    }
}
