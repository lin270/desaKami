<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageLocation extends Model
{
    protected $fillable = ['name', 'category', 'description', 'address', 'latitude', 'longitude', 'is_published'];
    protected function casts(): array { return ['latitude' => 'float', 'longitude' => 'float', 'is_published' => 'boolean']; }
}
