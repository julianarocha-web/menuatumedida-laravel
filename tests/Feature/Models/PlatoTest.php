<?php

namespace Tests\Feature\Models;

use App\Models\Categoria;
use App\Models\Plato;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PlatoTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_new_platos_have_zero_stock_and_are_active(): void
    {
        $categoria = Categoria::create(['nombre' => 'Prueba']);
        $plato = Plato::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Plato de prueba',
            'precio' => 1234.50,
        ]);
        $plato->refresh();

        $this->assertSame(0, $plato->stock);
        $this->assertSame(true, $plato->activo);
    }
}
