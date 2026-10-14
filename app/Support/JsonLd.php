<?php

namespace App\Support;

use App\Models\Post;

/** Structured data (schema.org) untuk mesin pencari. Dibuat di PHP agar aman dari injeksi </script>. */
class JsonLd
{
    public static function encode(array $data): string
    {
        $json = json_encode(
            self::clean($data),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        return '<script type="application/ld+json">'.$json.'</script>';
    }

    /** Identitas desa + kotak pencarian situs (dipakai di beranda). */
    public static function organization(array $site, ?string $logoUrl): string
    {
        $links = array_values(array_filter([
            $site['facebook'] ?? null,
            $site['instagram'] ?? null,
            $site['youtube'] ?? null,
        ]));

        $org = [
            '@context' => 'https://schema.org',
            '@type' => 'GovernmentOrganization',
            'name' => $site['site_name'] ?? 'Desa Mondokan',
            'url' => url('/'),
            'logo' => $logoUrl,
            'address' => $site['address'] ?? null,
            'telephone' => $site['phone'] ?? null,
            'email' => $site['email'] ?? null,
            'sameAs' => $links,
        ];

        $website = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $site['site_name'] ?? 'Desa Mondokan',
            'url' => url('/'),
            'inLanguage' => 'id',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/search').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];

        return self::encode($org).self::encode($website);
    }

    /** Artikel berita (dipakai di halaman detail berita). */
    public static function article(Post $post, array $site, ?string $logoUrl): string
    {
        return self::encode([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'mainEntityOfPage' => $post->url,
            'headline' => mb_substr($post->title, 0, 110),
            'description' => $post->excerpt_text,
            'image' => [$post->thumbnail_url],
            'datePublished' => $post->published_at?->toAtomString(),
            'dateModified' => $post->updated_at?->toAtomString(),
            'articleSection' => $post->category->name,
            'inLanguage' => 'id',
            'author' => ['@type' => 'Person', 'name' => $post->author->name],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $site['site_name'] ?? 'Desa Mondokan',
                'logo' => $logoUrl ? ['@type' => 'ImageObject', 'url' => $logoUrl] : null,
            ],
        ]);
    }

    /** Buang nilai kosong secara rekursif. */
    private static function clean(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::clean($value);
            }

            if ($data[$key] === null || $data[$key] === '' || $data[$key] === []) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
