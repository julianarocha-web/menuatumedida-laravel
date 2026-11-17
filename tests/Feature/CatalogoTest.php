<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Plato;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CatalogoTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_home_shows_active_catalog_data_and_detail_links(): void
    {
        $categoria = Categoria::create(['nombre' => 'Desayunos']);
        $plato = Plato::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Panqueques caseros',
            'descripcion' => 'Con frutas frescas',
            'precio' => '9000.00',
            'stock' => 0,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Panqueques caseros')
            ->assertSee(route('platos.show', $plato));

        $this->get(route('platos.show', $plato))
            ->assertOk()
            ->assertSee('Sin stock por el momento');
    }

    public function test_inactive_platos_and_categories_are_hidden_from_public_catalog(): void
    {
        $categoria = Categoria::create(['nombre' => 'Desayunos']);
        $plato = Plato::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Plato fuera de catálogo',
            'precio' => '9000.00',
            'stock' => 5,
            'activo' => false,
        ]);

        $this->get('/')->assertDontSee('Plato fuera de catálogo');
        $this->get(route('platos.show', $plato))->assertNotFound();

        $categoria->update(['activo' => false]);
        $this->get('/')->assertDontSee('Desayunos');
    }
}
