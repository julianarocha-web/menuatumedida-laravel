<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_sent_to_login_and_returns_to_review_with_cart(): void
    {
        $plato = Plato::factory()->create();
        $buyer = User::factory()->create();
        $this->withSession(['cart' => [$plato->id => 2]])->get(route('checkout.create'))
            ->assertRedirect(route('buyer.login'))->assertSessionHas('cart.'.$plato->id, 2);
        $this->post(route('buyer.login.store'), ['email' => $buyer->email, 'password' => 'password'])
            ->assertRedirect(route('checkout.create'))->assertSessionHas('cart.'.$plato->id, 2);
        $this->get(route('checkout.create'))->assertOk()->assertSee('2.010,50');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_confirm_creates_pending_order_from_database_and_ignores_forged_prices_and_status(): void
    {
        $this->freezeTime();
        $plato = Plato::factory()->create();
        $second = Plato::factory()->create(['precio' => '500.10']);
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 2, $second->id => 3]]);
        $token = $this->review();
        $this->post(route('checkout.store'), ['checkout_token' => $token, 'total_cents' => 1, 'unit_cents' => 1, 'status' => 'paid', 'user_id' => 999])
            ->assertRedirect(route('orders.show', Order::first()));
        $order = Order::firstOrFail();
        $this->assertSame(351080, $order->total_cents);
        $this->assertSame(Order::PendingPayment, $order->status);
        $this->assertSame(auth()->id(), $order->user_id);
        $this->assertSame(now()->addMinutes(15)->format('Y-m-d H:i:s'), $order->reserved_until->format('Y-m-d H:i:s'));
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'plato_id' => $plato->id, 'quantity' => 2, 'unit_cents' => 100525]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'plato_id' => $second->id, 'quantity' => 3, 'unit_cents' => 50010]);
        $this->assertDatabaseHas('platos', ['id' => $plato->id, 'stock' => 5]);
        $this->assertSame(3, $plato->availableStock());
        $this->assertNull(session('cart'));
        $this->get(route('orders.show', $order))->assertOk()->assertSee('Pendiente de pago')->assertSee('3.510,80');
        $this->get(route('orders.index'))->assertOk()->assertSee('Pedido #'.$order->id);
    }

    public function test_repeated_confirmation_returns_same_order_even_after_cart_was_cleared(): void
    {
        $plato = Plato::factory()->create();
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 2]]);
        $token = $this->review();
        $this->post(route('checkout.store'), ['checkout_token' => $token])->assertRedirect();
        $this->withSession(['cart' => [$plato->id => 1]]);
        $this->post(route('checkout.store'), ['checkout_token' => $token])->assertRedirect(route('orders.show', Order::first()));
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertSame(3, $plato->availableStock());
        $this->assertSame([$plato->id => 1], session('cart'));
    }

    public function test_changed_database_price_requires_a_new_review(): void
    {
        $plato = Plato::factory()->create();
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 2]]);
        $token = $this->review();
        $plato->update(['precio' => '2000.10']);
        $this->post(route('checkout.store'), ['checkout_token' => $token])
            ->assertRedirect(route('checkout.create'))->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders', 0);
        $newToken = $this->review();
        $this->assertNotSame($token, $newToken);
        $this->post(route('checkout.store'), ['checkout_token' => $newToken])->assertRedirect();
        $this->assertDatabaseHas('orders', ['total_cents' => 400020]);
    }

    #[TestWith(['stock'])]
    #[TestWith(['plato'])]
    #[TestWith(['categoria'])]
    #[TestWith(['deleted'])]
    public function test_unavailable_product_after_review_creates_no_order(string $change): void
    {
        $plato = Plato::factory()->create();
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 2]]);
        $token = $this->review();
        match ($change) {
            'stock' => $plato->update(['stock' => 1]),
            'plato' => $plato->update(['activo' => false]),
            'categoria' => $plato->categoria->update(['activo' => false]),
            'deleted' => $plato->delete(),
        };
        $this->post(route('checkout.store'), ['checkout_token' => $token])->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame([$plato->id => 2], session('cart'));
    }

    public function test_empty_cart_cannot_be_confirmed(): void
    {
        $this->actingAs(User::factory()->create());
        $token = $this->review();
        $this->get(route('checkout.create'))->assertOk()->assertSee('Tu carrito está vacío')->assertDontSee('name="checkout_token"', false);
        $this->post(route('checkout.store'), ['checkout_token' => $token])
            ->assertSessionHasErrors(['checkout' => 'Tu carrito está vacío.']);
        $this->assertDatabaseCount('orders', 0);
    }

    #[TestWith([0])]
    #[TestWith([-1])]
    #[TestWith([1.5])]
    #[TestWith(['2'])]
    #[TestWith([6])]
    public function test_invalid_session_quantity_cannot_create_order(int|float|string $quantity): void
    {
        $plato = Plato::factory()->create();
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 1]]);
        $token = $this->review();
        $this->withSession(['cart' => [$plato->id => $quantity]])->post(route('checkout.store'), ['checkout_token' => $token])
            ->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_other_buyer_cannot_view_cancel_or_replay_an_order(): void
    {
        $order = Order::factory()->create();
        $this->actingAs(User::factory()->create());
        $this->get(route('orders.show', $order))->assertNotFound();
        $this->delete(route('orders.destroy', $order))->assertNotFound();
        $this->get(route('orders.index'))->assertOk()->assertDontSee('Pedido #'.$order->id);
        $this->post(route('checkout.store'), ['checkout_token' => $order->checkout_token])->assertSessionHasErrors('checkout');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::PendingPayment]);
    }

    public function test_admin_and_guest_cannot_confirm_orders(): void
    {
        $this->post(route('checkout.store'), ['checkout_token' => (string) Str::uuid()])->assertRedirect(route('buyer.login'));
        $admin = User::factory()->create();
        $admin->forceFill(['es_admin' => true])->save();
        $this->actingAs($admin)->get(route('checkout.create'))->assertForbidden();
        $this->post(route('checkout.store'), ['checkout_token' => (string) Str::uuid()])->assertForbidden();
        $this->get(route('orders.index'))->assertForbidden();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_active_reservations_block_another_buyer_until_expiry_without_changing_physical_stock(): void
    {
        $this->freezeTime();
        $plato = Plato::factory()->create(['stock' => 2]);
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 2]]);
        $token = $this->review();
        OrderItem::factory()->for(Order::factory())->for($plato)->create(['quantity' => 2]);
        $this->post(route('checkout.store'), ['checkout_token' => $token])->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(0, $plato->availableStock());
        $this->travel(15)->minutes();
        $this->assertSame(2, $plato->availableStock());
        $this->post(route('checkout.store'), ['checkout_token' => $token])->assertRedirect();
        $this->assertDatabaseCount('orders', 2);
        $this->assertDatabaseHas('platos', ['id' => $plato->id, 'stock' => 2]);
        $this->artisan('orders:expire')->assertSuccessful();
        $this->assertDatabaseCount('orders', 2);
        $this->assertSame(1, Order::where('status', Order::Expired)->count());
    }

    public function test_cancel_releases_reservation_and_cannot_cancel_paid_order(): void
    {
        $this->freezeTime();
        $buyer = User::factory()->create();
        $plato = Plato::factory()->create();
        $order = Order::factory()->for($buyer)->create();
        OrderItem::factory()->for($order)->for($plato)->create(['quantity' => 2]);
        $this->assertSame(3, $plato->availableStock());
        $this->actingAs($buyer)->delete(route('orders.destroy', $order))->assertRedirect(route('orders.show', $order));
        $this->delete(route('orders.destroy', $order))->assertRedirect();
        $this->assertSame(5, $plato->availableStock());
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::Cancelled]);
        $paid = Order::factory()->for($buyer)->create(['status' => Order::Paid]);
        $this->delete(route('orders.destroy', $paid))->assertRedirect();
        $this->assertDatabaseHas('orders', ['id' => $paid->id, 'status' => Order::Paid]);
    }

    public function test_failure_during_item_insert_rolls_back_order_and_preserves_cart(): void
    {
        $plato = Plato::factory()->create();
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 2]]);
        $token = $this->review();
        OrderItem::created(function (OrderItem $item): void {
            throw new \RuntimeException('Fallo de persistencia provocado por la prueba.');
        });
        try {
            $this->post(route('checkout.store'), ['checkout_token' => $token])->assertStatus(500);
            $this->assertDatabaseCount('orders', 0);
            $this->assertDatabaseCount('order_items', 0);
            $this->assertSame([$plato->id => 2], session('cart'));
            $this->assertSame(5, $plato->availableStock());
        } finally {
            OrderItem::flushEventListeners();
        }
    }

    public function test_invalid_token_and_missing_token_create_no_order(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post(route('checkout.store'))->assertSessionHasErrors('checkout_token');
        $this->post(route('checkout.store'), ['checkout_token' => 'invalid'])->assertSessionHasErrors('checkout_token');
        $this->post(route('checkout.store'), ['checkout_token' => (string) Str::uuid()])->assertSessionHasErrors('checkout');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_historical_name_price_and_escaped_output_survive_catalog_changes(): void
    {
        $plato = Plato::factory()->create(['nombre' => '<script>alert(1)</script>']);
        $this->actingAs(User::factory()->create())->withSession(['cart' => [$plato->id => 1]]);
        $token = $this->review();
        $this->post(route('checkout.store'), ['checkout_token' => $token])->assertRedirect();
        $plato->update(['nombre' => 'Nombre nuevo', 'precio' => '9999.00', 'activo' => false]);
        $this->get(route('orders.show', Order::first()))->assertOk()->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)->assertSee('1.005,25')->assertDontSee('Nombre nuevo');
    }

    public function test_checkout_requires_csrf(): void
    {
        $buyer = User::factory()->create();
        $this->app->instance('env', 'local');
        $this->actingAs($buyer)->post(route('checkout.store'), ['checkout_token' => (string) Str::uuid()])->assertStatus(419);
        $this->assertDatabaseCount('orders', 0);
    }

    private function review(): string
    {
        $this->get(route('checkout.create'))->assertOk();

        return session('checkout.token');
    }
}
