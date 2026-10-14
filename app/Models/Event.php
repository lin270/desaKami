<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** Kegiatan hari ini dan seterusnya. */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('date', '>=', today());
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->image, null));
    }
}
