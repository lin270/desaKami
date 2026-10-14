<?php

namespace App\Models;

use App\Support\Rupiah;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItem extends Model
{
    public const TYPES = [
        'pendapatan' => 'Pendapatan',
        'belanja' => 'Belanja',
        'pembiayaan_penerimaan' => 'Pembiayaan - Penerimaan',
        'pembiayaan_pengeluaran' => 'Pembiayaan - Pengeluaran',
    ];

    protected $fillable = [
        'budget_id', 'type', 'category', 'name',
        'amount_plan', 'amount_realized', 'sort_order',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    protected function percent(): Attribute
    {
        return Attribute::get(fn () => Rupiah::percent($this->amount_realized, $this->amount_plan));
    }
}
