<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Slug dibuat otomatis saat disimpan jika masih kosong.
     * Slug lama tidak berubah saat judul diedit (URL tetap stabil untuk SEO).
     */
    protected static function bootHasSlug(): void
    {
        static::saving(function (Model $model) {
            if (blank($model->slug)) {
                $model->slug = static::generateUniqueSlug(
                    (string) $model->{$model->slugSource()},
                    $model->getKey()
                );
            }
        });
    }

    /** Kolom sumber slug. Override di model jika bukan "title". */
    public function slugSource(): string
    {
        return 'title';
    }

    public static function generateUniqueSlug(string $text, $ignoreId = null): string
    {
        $base = Str::slug($text) ?: 'item';
        $slug = $base;
        $i = 2;

        while (static::slugExists($slug, $ignoreId)) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    protected static function slugExists(string $slug, $ignoreId = null): bool
    {
        $query = static::query();

        // Slug unik juga terhadap data yang di-soft-delete
        if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            $query->withTrashed();
        }

        return $query->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }
}
