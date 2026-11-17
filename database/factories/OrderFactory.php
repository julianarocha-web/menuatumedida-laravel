<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return ['user_id' => User::factory(), 'checkout_token' => (string) Str::uuid(), 'status' => Order::PendingPayment, 'total_cents' => 100525, 'reserved_until' => now()->addMinutes(15)];
    }
}
