<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['plato_id', 'nombre', 'quantity', 'unit_cents'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'unit_cents' => 'integer'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function plato(): BelongsTo
    {
        return $this->belongsTo(Plato::class);
    }
}
