<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plato extends Model
{
    use HasFactory;

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function availableStock(bool $lock = false): int
    {
        $query = OrderItem::query()->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('plato_id', $this->id)->where('orders.status', Order::PendingPayment)
            ->where('orders.reserved_until', '>', now());
        $reserved = $lock
            ? $query->lockForUpdate()->get(['order_items.quantity'])->sum('quantity')
            : $query->sum('quantity');

        return max(0, $this->stock - (int) $reserved);
    }

    protected $fillable = ['categoria_id', 'nombre', 'descripcion', 'precio', 'imagen', 'stock', 'activo'];

    protected $attributes = [
        'stock' => 0,
        'activo' => true,
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }
}
