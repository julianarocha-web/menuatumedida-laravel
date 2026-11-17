<?php

namespace Tests\Feature\Seeders;

use App\Models\Categoria;
use App\Models\Plato;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_repeated_seeding_preserves_custom_platos_and_does_not_duplicate_menu(): void
    {
        $categoria = Categoria::create(['nombre' => 'Desayunos']);
        $plato = Plato::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Pancakes',
            'descripcion' => 'Edicion manual',
            'precio' => 9876.50,
            'imagen' => 'img/custom.webp',
            'stock' => 4,
            'activo' => false,
        ]);

        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $plato->refresh();

        $this->assertSame('Edicion manual', $plato->descripcion);
        $this->assertSame('9876.50', $plato->precio);
        $this->assertSame('img/custom.webp', $plato->imagen);
        $this->assertSame(4, $plato->stock);
        $this->assertSame(false, $plato->activo);
        $this->assertDatabaseCount('categorias', 4);
        $this->assertDatabaseCount('platos', 16);
        $this->assertDatabaseCount('users', 1);
    }
}
