<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ExpireOrders extends Command
{
    protected $signature = 'orders:expire';

    protected $description = 'Mark unpaid orders whose logical stock reservation has expired';

    public function handle(): int
    {
        $count = Order::query()->where('status', Order::PendingPayment)
            ->where('reserved_until', '<=', now())->update(['status' => Order::Expired]);
        $this->info('Reservas vencidas: '.$count);

        return self::SUCCESS;
    }
}
