<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class VillageLocation extends Model
{
    protected $fillable = ['name', 'category', 'description', 'address', 'image', 'latitude', 'longitude', 'is_published'];

    protected function casts(): array
    {
        return ['latitude' => 'float', 'longitude' => 'float', 'is_published' => 'boolean'];
    }

    /** URL foto lokasi, atau null bila belum ada (tampilan memakai ilustrasi pengganti). */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => filled($this->image) ? Media::url($this->image, null) : null);
    }
}
