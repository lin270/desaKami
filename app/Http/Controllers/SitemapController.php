<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\LostItem;
use App\Models\Post;
use App\Models\Potential;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /** GET /sitemap.xml - di-cache 1 jam, dibersihkan otomatis saat admin menyimpan data. */
    public function __invoke(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $rows = [];

            // [path, prioritas, frekuensi]
            foreach ([
                ['/', '1.0', 'daily'],
                ['/berita', '0.9', 'daily'],
                ['/pengumuman', '0.8', 'daily'],
                ['/agenda', '0.7', 'weekly'],
                ['/peta-desa', '0.7', 'monthly'],
                ['/profil', '0.6', 'monthly'],
                ['/pemerintahan', '0.6', 'monthly'],
                ['/informasi', '0.6', 'monthly'],
                ['/galeri', '0.6', 'weekly'],
                ['/potensi', '0.7', 'monthly'],
                ['/barang-hilang', '0.6', 'daily'],
                ['/apbdes', '0.7', 'monthly'],
            ] as [$path, $priority, $freq]) {
                $rows[] = [url($path), null, $freq, $priority];
            }

            foreach (Category::get(['slug', 'updated_at']) as $c) {
                $rows[] = [$c->url, $c->updated_at, 'weekly', '0.5'];
            }

            foreach (Post::published()->latest('published_at')->limit(5000)->get(['id', 'slug', 'updated_at']) as $p) {
                $rows[] = [$p->url, $p->updated_at, 'monthly', '0.8'];
            }

            foreach (Announcement::published()->latest('published_at')->limit(1000)->get(['id', 'slug', 'updated_at']) as $a) {
                $rows[] = [$a->url, $a->updated_at, 'monthly', '0.6'];
            }

            foreach (Gallery::published()->latest('id')->limit(1000)->get(['id', 'slug', 'updated_at']) as $g) {
                $rows[] = [$g->url, $g->updated_at, 'monthly', '0.5'];
            }

            foreach (Potential::published()->latest('id')->limit(1000)->get(['id', 'slug', 'updated_at']) as $x) {
                $rows[] = [$x->url, $x->updated_at, 'monthly', '0.6'];
            }

            foreach (LostItem::published()->active()->latest('id')->limit(500)->get(['id', 'slug', 'updated_at']) as $x) {
                $rows[] = [$x->url, $x->updated_at, 'weekly', '0.4'];
            }

            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

            foreach ($rows as [$loc, $lastmod, $freq, $priority]) {
                $out .= "  <url>\n";
                $out .= '    <loc>'.htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
                if ($lastmod) {
                    $out .= '    <lastmod>'.$lastmod->toAtomString()."</lastmod>\n";
                }
                $out .= "    <changefreq>{$freq}</changefreq>\n";
                $out .= "    <priority>{$priority}</priority>\n";
                $out .= "  </url>\n";
            }

            return $out."</urlset>\n";
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
