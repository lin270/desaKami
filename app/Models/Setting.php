<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Semua pengaturan sebagai array key => value (di-cache 1 jam). */
    public static function allCached(): array
    {
        return Cache::remember('settings.all', 3600, fn () => static::pluck('value', 'key')->all());
    }

    public static function read(string $key, $default = null)
    {
        return static::allCached()[$key] ?? $default;
    }

    public static function write(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }
}
