<?php

namespace Tests\Feature\Admin;

use App\Models\Categoria;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CategoriaCrudTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_creates_and_updates_a_categoria(): void
    {
        $administrator = $this->createAdmin();

        $this->actingAs($administrator)->post(route('admin.categorias.store'), [
            'nombre' => 'Opciones veganas',
            'activo' => 1,
        ])->assertRedirect(route('admin.categorias.index'));

        $categoria = Categoria::where('nombre', 'Opciones veganas')->firstOrFail();

        $this->actingAs($administrator)->put(route('admin.categorias.update', $categoria), [
            'nombre' => 'Menú vegetal',
            'activo' => 1,
        ])->assertRedirect(route('admin.categorias.index'));

        $this->assertDatabaseHas('categorias', [
            'id' => $categoria->id,
            'nombre' => 'Menú vegetal',
            'activo' => 1,
        ]);
    }

    public function test_destroy_deactivates_categoria_and_preserves_its_platos(): void
    {
        $administrator = $this->createAdmin();
        $categoria = Categoria::create(['nombre' => 'Cenas']);
        $plato = Plato::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Plato relacionado',
            'precio' => '9800.00',
            'stock' => 2,
        ]);

        $this->actingAs($administrator)
            ->delete(route('admin.categorias.destroy', $categoria))
            ->assertRedirect(route('admin.categorias.index'));

        $this->assertModelExists($categoria);
        $this->assertModelExists($plato);
        $this->assertDatabaseHas('categorias', ['id' => $categoria->id, 'activo' => 0]);
    }

    private function createAdmin(): User
    {
        $administrator = User::factory()->create();
        $administrator->es_admin = true;
        $administrator->save();

        return $administrator;
    }
}
