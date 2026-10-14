<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageStatistic extends Model
{
    protected $fillable = [
        'key',
        'label',
        'value',
        'unit',
        'group',
        'sort_order',
    ];
}
