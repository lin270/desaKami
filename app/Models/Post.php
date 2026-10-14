<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'thumbnail',
        'excerpt',
        'content',
        'status',
        'is_featured',
        'published_at',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** Hanya berita yang sudah terbit dan waktunya sudah tiba. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->thumbnail));
    }

    /** URL publik (satu tempat agar mudah diubah). */
    protected function url(): Attribute
    {
        return Attribute::get(fn () => url('/berita/'.$this->slug));
    }

    /** Ringkasan; jika kosong diambil dari isi berita. */
    protected function excerptText(): Attribute
    {
        return Attribute::get(function () {
            if (filled($this->excerpt)) {
                return $this->excerpt;
            }

            return Str::limit(trim(strip_tags(Str::markdown($this->content, ['html_input' => 'strip']))), 160);
        });
    }
}
