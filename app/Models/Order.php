<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const PendingPayment = 'pending_payment';

    public const Paid = 'paid';

    public const Rejected = 'rejected';

    public const Cancelled = 'cancelled';

    public const Expired = 'expired';

    protected $fillable = ['checkout_token', 'status', 'total_cents', 'reserved_until'];

    protected function casts(): array
    {
        return ['reserved_until' => 'datetime', 'total_cents' => 'integer'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function currentStatus(): string
    {
        return $this->status === self::PendingPayment && $this->reserved_until->lte(now()) ? self::Expired : $this->status;
    }

    public function statusLabel(): string
    {
        return match ($this->currentStatus()) {
            self::PendingPayment => 'Pendiente de pago',
            self::Paid => 'Pagado',
            self::Rejected => 'Pago rechazado',
            self::Cancelled => 'Cancelado',
            self::Expired => 'Reserva vencida',
            default => 'En revisión',
        };
    }
}
