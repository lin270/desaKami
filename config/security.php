<?php

return [
    /*
    | Content-Security-Policy. Default: aktif saat APP_DEBUG=false (production),
    | nonaktif saat debug agar halaman error Laravel tetap terbaca.
    | Paksa dengan CSP_ENABLED=true / false di .env.
    */
    'csp' => env('CSP_ENABLED', ! env('APP_DEBUG', false)),
];
