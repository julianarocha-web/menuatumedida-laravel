<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Plato;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return ['order_id' => Order::factory(), 'plato_id' => Plato::factory(), 'nombre' => 'Plato de prueba', 'quantity' => 1, 'unit_cents' => 100525];
    }
}
