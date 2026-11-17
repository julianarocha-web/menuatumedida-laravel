<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class CartTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_add_from_catalog_and_detail_and_keep_selection(): void
    {
        $plato = $this->plato();

        $this->get(route('home'))->assertOk()->assertSee(route('cart.store', $plato));
        $this->post(route('cart.store', $plato), ['quantity' => 1])
            ->assertRedirect()->assertSessionHas('cart.'.$plato->id, 1);
        $this->get(route('platos.show', $plato))->assertOk()
            ->assertSee('Cantidad: 1')->assertSee(route('cart.store', $plato));
        $this->postJson(route('cart.store', $plato), ['quantity' => 1])
            ->assertOk()->assertJsonPath('count', 2)->assertJsonPath('total_cents', 201050);
        $this->get(route('home'))->assertOk()->assertSee('Cantidad: 2')->assertSee('2.010,50');

        $this->assertGuest();
        $this->assertDatabaseHas('platos', ['id' => $plato->id, 'stock' => 5, 'precio' => '1005.25']);
    }

    public function test_price_manipulation_is_ignored_and_totals_use_current_database_prices(): void
    {
        $plato = $this->plato();

        $this->postJson(route('cart.store', $plato), ['quantity' => 2, 'precio' => '0.01', 'price' => 1, 'total' => 0])
            ->assertOk()->assertJsonPath('total_cents', 201050)
            ->assertSessionHas('cart', [$plato->id => 2]);

        $plato->update(['precio' => '2000.10']);
        $this->getJson(route('cart.index'))->assertOk()->assertJsonPath('total_cents', 400020);
    }

    public function test_quantities_can_increase_decrease_and_items_can_be_removed(): void
    {
        $plato = $this->plato();

        $this->withSession(['cart' => [$plato->id => 1]])
            ->patchJson(route('cart.update', $plato), ['quantity' => 3])
            ->assertOk()->assertJsonPath('count', 3)->assertJsonPath('total_cents', 301575);
        $this->patchJson(route('cart.update', $plato), ['quantity' => 1])
            ->assertOk()->assertJsonPath('count', 1)->assertJsonPath('total_cents', 100525);
        $this->deleteJson(route('cart.destroy', $plato))
            ->assertOk()->assertJsonPath('total_cents', 0)->assertSessionHas('cart', []);
    }

    public function test_multiple_items_have_correct_subtotals_and_total(): void
    {
        $first = $this->plato();
        $second = $this->plato(['nombre' => 'Segundo plato', 'precio' => '500.10']);

        $this->withSession(['cart' => [$first->id => 2, $second->id => 3]])
            ->getJson(route('cart.index'))->assertOk()->assertJsonPath('count', 5)
            ->assertJsonPath('total_cents', 351080)
            ->assertSee('2.010,50')->assertSee('1.500,30');
    }

    #[TestWith([0])]
    #[TestWith([-1])]
    #[TestWith([6])]
    #[TestWith([1.5])]
    #[TestWith(['abc'])]
    #[TestWith([null])]
    #[TestWith([2147483648])]
    public function test_invalid_quantities_cannot_be_added_or_updated(int|float|string|null $quantity): void
    {
        $plato = $this->plato();

        $this->postJson(route('cart.store', $plato), ['quantity' => $quantity])
            ->assertUnprocessable()->assertJsonValidationErrors('quantity');
        $this->withSession(['cart' => [$plato->id => 2]])
            ->patchJson(route('cart.update', $plato), ['quantity' => $quantity])
            ->assertUnprocessable()->assertJsonValidationErrors('quantity')->assertSessionHas('cart.'.$plato->id, 2);
    }

    public function test_repeated_addition_cannot_exceed_available_stock(): void
    {
        $plato = $this->plato();

        $this->withSession(['cart' => [$plato->id => 4]])
            ->postJson(route('cart.store', $plato), ['quantity' => 2])
            ->assertUnprocessable()->assertJsonValidationErrors('quantity')->assertSessionHas('cart.'.$plato->id, 4);
    }

    public function test_out_of_stock_item_is_displayed_but_cannot_be_added(): void
    {
        $plato = $this->plato(['stock' => 0]);

        $this->get(route('home'))->assertOk()->assertSee('Agotado');
        $this->get(route('platos.show', $plato))->assertOk()->assertSee('Sin stock por el momento')->assertSee('Agotado');
        $this->postJson(route('cart.store', $plato), ['quantity' => 1])
            ->assertUnprocessable()->assertJsonValidationErrors(['quantity' => 'Este plato está agotado.']);
    }

    public function test_cart_adjusts_to_reduced_stock_and_removes_unavailable_items(): void
    {
        $plato = $this->plato(['stock' => 2]);

        $this->withSession(['cart' => [$plato->id => 4]])->getJson(route('cart.index'))
            ->assertOk()->assertJsonPath('count', 2)->assertSee('stock disponible')
            ->assertSessionHas('cart.'.$plato->id, 2);

        $plato->update(['stock' => 0]);
        $this->getJson(route('cart.index'))->assertOk()->assertJsonPath('count', 0)
            ->assertSee('agotado')->assertSessionHas('cart', []);
    }

    #[TestWith(['plato'])]
    #[TestWith(['categoria'])]
    public function test_inactive_items_cannot_be_added_and_are_removed_from_cart(string $inactive): void
    {
        $plato = $this->plato();
        ($inactive === 'plato' ? $plato : $plato->categoria)->update(['activo' => false]);

        $this->postJson(route('cart.store', $plato), ['quantity' => 1])->assertNotFound();
        $this->withSession(['cart' => [$plato->id => 1]])->getJson(route('cart.index'))
            ->assertOk()->assertJsonPath('count', 0)->assertSessionHas('cart', []);
        $this->get(route('home'))->assertOk()->assertDontSee($plato->nombre);
    }

    public function test_deleted_item_can_be_removed_without_route_binding(): void
    {
        $plato = $this->plato();
        $id = $plato->id;
        $plato->delete();

        $this->withSession(['cart' => [$id => 1]])->deleteJson(route('cart.destroy', $id))
            ->assertOk()->assertSessionHas('cart', []);
    }

    public function test_missing_product_and_updating_an_item_outside_cart_return_404(): void
    {
        $plato = $this->plato();

        $this->postJson(route('cart.store', 999999), ['quantity' => 1])->assertNotFound();
        $this->patchJson(route('cart.update', $plato), ['quantity' => 1])->assertNotFound();
    }

    public function test_empty_cart_has_zero_total_and_empty_message(): void
    {
        $response = $this->getJson(route('cart.index'))->assertOk()->assertJsonPath('total_cents', 0)
            ->assertJsonPath('count', 0);
        $this->assertStringContainsString('Tu carrito está vacío', $response->json('html'));
        $this->get(route('home'))->assertOk()->assertSee('Total: $0,00')->assertDontSee('Total: $39.000');
    }

    public function test_guest_selection_survives_buyer_login(): void
    {
        $plato = $this->plato();
        $buyer = User::factory()->create();

        $this->withSession(['cart' => [$plato->id => 2]])->post(route('buyer.login.store'), [
            'email' => $buyer->email, 'password' => 'password',
        ])->assertRedirect(route('home'))->assertSessionHas('cart.'.$plato->id, 2);

        $this->assertAuthenticatedAs($buyer);
    }

    public function test_cart_escapes_product_names(): void
    {
        $plato = $this->plato(['nombre' => '<script>alert(1)</script>']);

        $response = $this->withSession(['cart' => [$plato->id => 1]])->getJson(route('cart.index'))->assertOk();

        $this->assertStringContainsString('&lt;script&gt;', $response->json('html'));
        $this->assertStringNotContainsString('<script>alert(1)</script>', $response->json('html'));
    }

    public function test_cart_forms_work_without_javascript_and_reopen_offcanvas(): void
    {
        $plato = $this->plato();

        $this->from(route('platos.show', $plato))->post(route('cart.store', $plato), ['quantity' => 2])
            ->assertRedirect(route('platos.show', $plato))->assertSessionHas('cart_open', true);
        $this->get(route('platos.show', $plato))->assertOk()->assertSee('Cantidad: 2')
            ->assertSee('data-cart-open="true"', false)->assertSee(asset($plato->imagen));
        $this->from(route('home'))->patch(route('cart.update', $plato), ['quantity' => 0])
            ->assertRedirect(route('home'))->assertSessionHasErrorsIn('cart', 'quantity')
            ->assertSessionHas('cart.'.$plato->id, 2);
    }

    public function test_separate_visitor_session_starts_with_empty_cart(): void
    {
        $plato = $this->plato();

        $this->withSession(['cart' => [$plato->id => 2]])->getJson(route('cart.index'))
            ->assertOk()->assertJsonPath('count', 2);
        $this->flushSession();
        $this->getJson(route('cart.index'))->assertOk()->assertJsonPath('count', 0)->assertJsonPath('total_cents', 0);
    }

    public function test_cart_writes_require_csrf_token(): void
    {
        $plato = $this->plato();
        $this->app->instance('env', 'local');

        $this->post(route('cart.store', $plato), ['quantity' => 1])->assertStatus(419);
        $this->patch(route('cart.update', $plato), ['quantity' => 1])->assertStatus(419);
        $this->delete(route('cart.destroy', $plato))->assertStatus(419);
    }

    /** @param array<string, mixed> $attributes */
    private function plato(array $attributes = []): Plato
    {
        $categoria = Categoria::create(['nombre' => 'Categoría de prueba']);

        return Plato::create(array_replace([
            'categoria_id' => $categoria->id, 'nombre' => 'Plato de prueba', 'descripcion' => 'Descripción de prueba',
            'precio' => '1005.25', 'stock' => 5, 'imagen' => 'img/comidas/burger.webp',
        ], $attributes));
    }
}
