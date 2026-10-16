<?php

namespace App\View\Composers;

use App\Models\Setting;
use App\Support\Media;
use Illuminate\View\View;

/**
 * Data identitas situs ($site, $logoUrl) untuk semua view.
 * Hasil disimpan per-request agar pengaturan tidak dibaca berulang kali.
 */
class SiteComposer
{
    private static ?array $data = null;

    public function compose(View $view): void
    {
        if (self::$data === null) {
            $defaults = [
                'site_name' => 'DESA KLEPU',
                'site_tagline' => 'Website Resmi Desa Klepu',
            ];

            // Pengaturan yang kosong diabaikan agar nilai default tetap dipakai
            $site = array_merge($defaults, array_filter(Setting::allCached(), fn ($v) => filled($v)));

            self::$data = [
                'site' => $site,
                'logoUrl' => Media::url($site['logo'] ?? null, null),
            ];
        }

        $view->with(self::$data);
    }
}
