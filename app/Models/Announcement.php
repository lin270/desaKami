<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category',
        'is_important',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_important' => 'boolean',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->image, null));
    }

    /** URL publik (satu tempat agar mudah diubah). */
    protected function url(): Attribute
    {
        return Attribute::get(fn () => url('/pengumuman/'.$this->slug));
    }
}
