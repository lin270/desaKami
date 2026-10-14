<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Official extends Model
{
    public const GROUPS = [
        'kepala_desa' => 'Kepala Desa',
        'sekretaris' => 'Sekretaris Desa',
        'kaur' => 'Kepala Urusan',
        'kasi' => 'Kepala Seksi',
        'kadus' => 'Kepala Dusun',
    ];

    protected $fillable = [
        'name',
        'position',
        'group',
        'photo',
        'phone',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Foto pejabat; null jika belum ada (tampilan memakai inisial nama). */
    protected function photoUrl(): Attribute
    {
        return Attribute::get(fn () => Media::url($this->photo, null));
    }

    /** Inisial nama (maks. 2 huruf) untuk avatar jika belum ada foto. */
    protected function initials(): Attribute
    {
        return Attribute::get(fn () => Str::upper(
            Str::of($this->name)->explode(' ')->filter()->take(2)
                ->map(fn ($w) => mb_substr($w, 0, 1))->implode('')
        ));
    }
}
