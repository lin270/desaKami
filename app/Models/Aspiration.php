<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aspiration extends Model
{
    use SoftDeletes;

    public const COLORS = 6;

    protected $fillable = ['name', 'message', 'color', 'reply', 'replied_at', 'replied_by', 'ip_hash'];

    protected function casts(): array
    {
        return ['replied_at' => 'datetime'];
    }

    public function replier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopeReplied(Builder $query): Builder
    {
        return $query->whereNotNull('reply');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('reply');
    }
}
