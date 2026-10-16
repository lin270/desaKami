<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Budget extends Model
{
    protected $fillable = ['year', 'title', 'notes', 'file', 'file_size', 'status'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('file');
    }

    /** Alamat unduhan publik (lewat controller, nama file rapi: APBDes-Desa-Klepu-2026.pdf). */
    protected function downloadUrl(): Attribute
    {
        return Attribute::get(fn () => url('/apbdes/'.$this->year.'/unduh'));
    }

    protected function downloadName(): Attribute
    {
        return Attribute::get(fn () => 'APBDes-Desa-Klepu-'.$this->year.'.pdf');
    }

    /** "1,2 MB" / "340 KB" */
    protected function fileSizeLabel(): Attribute
    {
        return Attribute::get(function () {
            $b = (int) $this->file_size;
            if ($b <= 0) {
                return null;
            }

            return $b >= 1048576
                ? number_format($b / 1048576, 1, ',', '.').' MB'
                : number_format(max(1, round($b / 1024)), 0, ',', '.').' KB';
        });
    }

    public function fileExists(): bool
    {
        return filled($this->file) && Storage::disk('public')->exists($this->file);
    }
}
