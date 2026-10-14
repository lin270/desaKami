<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class SiteCache
{
    /** Hapus cache publik (sidebar, statistik, pengaturan, sitemap) setelah admin mengubah data. */
    public static function flush(): void
    {
        foreach (['sidebar.data', 'home.stats', 'settings.all', 'sitemap.xml'] as $key) {
            Cache::forget($key);
        }
    }
}
