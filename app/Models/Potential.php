<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Potential extends Model
{
    use HasSlug, SoftDeletes;

    public const CATEGORIES = [
        'umkm' => 'UMKM',
        'pertanian' => 'Pertanian',
        'wisata' => 'Wisata',
        'produk_unggulan' => 'Produk Unggulan',
    ];

    protected $fillable = [
        'title', 'slug', 'category', 'summary', 'content', 'image',
        'location', 'contact', 'status', 'sort_order',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    protected function categoryLabel(): Attribute
    {
        return Attribute::get(fn () => self::CATEGORIES[$this->category] ?? $this->category);
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn () => url('/potensi/'.$this->slug));
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->image));
    }

    /** Ringkasan; jika kosong diambil dari isi. */
    protected function summaryText(): Attribute
    {
        return Attribute::get(function () {
            if (filled($this->summary)) {
                return $this->summary;
            }

            return Str::limit(trim(strip_tags(Str::markdown($this->content, ['html_input' => 'strip']))), 160);
        });
    }
}
