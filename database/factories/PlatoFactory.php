<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatoFactory extends Factory
{
    public function definition(): array
    {
        return ['categoria_id' => Categoria::factory(), 'nombre' => fake()->words(3, true), 'descripcion' => 'Plato de prueba', 'precio' => '1005.25', 'stock' => 5, 'activo' => true, 'imagen' => 'img/comidas/burger.webp'];
    }
}
