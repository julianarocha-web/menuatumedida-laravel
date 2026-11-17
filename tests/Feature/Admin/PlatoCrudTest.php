<?php

namespace Tests\Feature\Admin;

use App\Models\Categoria;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlatoCrudTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_creates_a_plato_with_managed_stock(): void
    {
        $administrator = $this->createAdmin();
        $categoria = Categoria::create(['nombre' => 'Almuerzos']);

        $response = $this->actingAs($administrator)->post(route('admin.platos.store'), [
            'categoria_id' => $categoria->id,
            'nombre' => 'Plato administrado',
            'descripcion' => 'Descripción de prueba',
            'precio' => '12500.50',
            'stock' => 8,
            'activo' => 1,
        ]);

        $response->assertRedirect(route('admin.platos.index'));
        $this->assertDatabaseHas('platos', [
            'categoria_id' => $categoria->id,
            'nombre' => 'Plato administrado',
            'precio' => '12500.50',
            'stock' => 8,
            'activo' => 1,
        ]);
    }

    public function test_admin_cannot_save_negative_stock(): void
    {
        $administrator = $this->createAdmin();
        $categoria = Categoria::create(['nombre' => 'Almuerzos']);

        $response = $this->actingAs($administrator)->from('/admin/platos/create')->post(route('admin.platos.store'), [
            'categoria_id' => $categoria->id,
            'nombre' => 'Stock inválido',
            'precio' => '12500.00',
            'stock' => -1,
            'activo' => 1,
        ]);

        $response->assertSessionHasErrors('stock');
        $this->assertDatabaseCount('platos', 0);
    }

    public function test_admin_uploads_a_validated_plato_image(): void
    {
        Storage::fake('public');
        $administrator = $this->createAdmin();
        $categoria = Categoria::create(['nombre' => 'Almuerzos']);

        $this->actingAs($administrator)->post(route('admin.platos.store'), [
            'categoria_id' => $categoria->id,
            'nombre' => 'Plato con imagen',
            'precio' => '12500.00',
            'stock' => 1,
            'activo' => 1,
            'imagen' => UploadedFile::fake()->image('plato.png'),
        ])->assertRedirect(route('admin.platos.index'));

        $plato = Plato::where('nombre', 'Plato con imagen')->firstOrFail();
        $storagePath = substr($plato->imagen, strlen('storage/'));

        $this->assertTrue(Storage::disk('public')->exists($storagePath));
    }

    public function test_destroy_deactivates_plato_without_deleting_it(): void
    {
        $administrator = $this->createAdmin();
        $categoria = Categoria::create(['nombre' => 'Almuerzos']);
        $plato = Plato::create([
            'categoria_id' => $categoria->id,
            'nombre' => 'Plato conservado',
            'precio' => '12500.00',
            'stock' => 3,
        ]);

        $this->actingAs($administrator)
            ->delete(route('admin.platos.destroy', $plato))
            ->assertRedirect(route('admin.platos.index'));

        $this->assertModelExists($plato);
        $this->assertDatabaseHas('platos', ['id' => $plato->id, 'activo' => 0]);
    }

    private function createAdmin(): User
    {
        $administrator = User::factory()->create();
        $administrator->es_admin = true;
        $administrator->save();

        return $administrator;
    }
}
