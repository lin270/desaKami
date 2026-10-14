<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LostItem extends Model
{
    use HasSlug, SoftDeletes;

    public const TYPES = [
        'hilang' => 'Barang Hilang',
        'ditemukan' => 'Barang Ditemukan',
    ];

    protected $fillable = [
        'type', 'title', 'slug', 'description', 'location', 'event_date',
        'image', 'contact', 'status', 'is_resolved', 'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** Belum selesai (belum kembali ke pemilik / belum ketemu). */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_resolved', false);
    }

    protected function typeLabel(): Attribute
    {
        return Attribute::get(fn () => self::TYPES[$this->type] ?? $this->type);
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn () => url('/barang-hilang/'.$this->slug));
    }

    /** Foto opsional; null jika tidak ada (tampilan memakai ikon). */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->image, null));
    }
}
