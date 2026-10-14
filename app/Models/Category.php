<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'description'];

    public function slugSource(): string
    {
        return 'name';
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /** URL publik (satu tempat agar mudah diubah). */
    protected function url(): Attribute
    {
        return Attribute::get(fn () => url('/kategori/'.$this->slug));
    }
}
