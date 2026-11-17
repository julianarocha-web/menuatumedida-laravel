<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class ViewRenderingTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_can_open_catalog_detail_and_both_login_screens(): void
    {
        $plato = Plato::factory()->create();

        $this->get(route('home'))->assertOk()->assertSee(route('platos.show', $plato));
        $this->get(route('platos.show', $plato))->assertOk()->assertSee($plato->nombre)->assertSee(route('cart.store', $plato));
        $this->get(route('buyer.login'))->assertOk()->assertSee(route('buyer.login.store'))->assertSee('Crear cuenta');
        $this->get(route('login'))->assertOk()->assertSee(route('admin.login.store'));
    }

    public function test_administrator_can_open_all_crud_screens_and_paginate(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['es_admin' => true])->save();
        $category = Categoria::factory()->create();
        $platos = Plato::factory()->count(16)->for($category)->create();
        Categoria::factory()->count(15)->create();
        $this->actingAs($admin);

        $this->get(route('admin.platos.index'))->assertOk()->assertSee('Siguiente');
        $this->get(route('admin.platos.index', ['page' => 2]))->assertOk()->assertSee('Anterior');
        $this->get(route('admin.platos.create'))->assertOk()->assertSee(route('admin.platos.store'));
        $this->get(route('admin.platos.edit', $platos->first()))->assertOk()->assertSee(route('admin.platos.update', $platos->first()));
        $this->get(route('admin.categorias.index'))->assertOk()->assertSee('Siguiente');
        $this->get(route('admin.categorias.index', ['page' => 2]))->assertOk()->assertSee('Anterior');
        $this->get(route('admin.categorias.create'))->assertOk()->assertSee(route('admin.categorias.store'));
        $this->get(route('admin.categorias.edit', $category))->assertOk()->assertSee(route('admin.categorias.update', $category));
    }

    public function test_buyer_can_navigate_profile_empty_history_review_and_paginated_history(): void
    {
        $buyer = User::factory()->create();
        $plato = Plato::factory()->create();
        $this->actingAs($buyer);

        $this->get(route('profile.edit'))->assertOk()->assertSee(route('orders.index'))->assertSee(route('profile.update'));
        $this->get(route('orders.index'))->assertOk()->assertSee('Todavía no tenés pedidos.');
        $this->get(route('checkout.create'))->assertOk()->assertSee('Tu carrito está vacío.');
        $this->withSession(['cart' => [$plato->id => 2]])->get(route('checkout.create'))->assertOk()->assertSee('2.010,50')->assertSee(route('checkout.store'));
        Order::factory()->count(11)->for($buyer)->create();

        $this->get(route('orders.index'))->assertOk()->assertSee('Siguiente');
        $this->get(route('orders.index', ['page' => 2]))->assertOk()->assertSee('Anterior');
    }

    #[TestWith(['pending_payment', 'Pendiente de pago'])]
    #[TestWith(['cancelled', 'Cancelado'])]
    #[TestWith(['expired', 'Reserva vencida'])]
    #[TestWith(['rejected', 'Pago rechazado'])]
    #[TestWith(['paid', 'Pagado'])]
    public function test_order_detail_renders_each_saved_status_without_changing_it(string $status, string $label): void
    {
        $this->freezeTime();
        $buyer = User::factory()->create();
        $order = Order::factory()->for($buyer)->create(['status' => $status]);
        $item = OrderItem::factory()->for($order)->create();

        $this->actingAs($buyer)->get(route('orders.show', $order))->assertOk()->assertSee($label)->assertSee($item->nombre)->assertSee(route('orders.index'));
        $this->assertSame($status, $order->fresh()->status);
    }

    public function test_admin_login_validation_message_is_rendered_once(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['es_admin' => true])->save();
        $this->get(route('login'))->assertOk();
        $this->from(route('login'))->post(route('admin.login.store'), ['email' => $admin->email, 'password' => 'incorrect'])
            ->assertRedirect(route('login'));

        $response = $this->get(route('login'))->assertOk()->assertSee('Las credenciales no corresponden a una cuenta administradora.');

        $this->assertSame(1, substr_count($response->getContent(), 'Las credenciales no corresponden a una cuenta administradora.'));
    }
}
