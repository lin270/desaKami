<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image_id',
        'taken_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'taken_at' => 'date',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(GalleryImage::class, 'cover_image_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** Cover album; jika belum diatur, pakai foto pertama; jika kosong, placeholder. */
    protected function coverUrl(): Attribute
    {
        return Attribute::get(function () {
            $path = $this->cover?->image ?? $this->images->first()?->image;

            return Media::url($path);
        });
    }

    /** URL publik (satu tempat agar mudah diubah). */
    protected function url(): Attribute
    {
        return Attribute::get(fn () => url('/galeri/'.$this->slug));
    }
}
