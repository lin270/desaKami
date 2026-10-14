<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class VillageProfile extends Model
{
    protected $fillable = [
        'about',
        'history',
        'vision',
        'mission',
        'area_description',
        'general_info',
        'hero_image',
    ];

    /** Profil desa hanya satu baris. */
    public static function current(): self
    {
        return static::first() ?? new static();
    }

    /** Misi disimpan satu poin per baris. */
    public function missionList(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->mission))));
    }

    protected function heroImageUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->hero_image));
    }
}
