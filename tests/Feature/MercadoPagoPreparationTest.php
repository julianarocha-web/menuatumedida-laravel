<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class MercadoPagoPreparationTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_payment_start_is_disabled_even_with_credentials_configured(): void
    {
        $this->freezeTime();
        Http::preventStrayRequests();
        config(['services.mercadopago.access_token' => 'test-only-private-sentinel', 'services.mercadopago.public_key' => 'test-only-public-sentinel']);
        $buyer = User::factory()->create();
        $order = Order::factory()->for($buyer)->create();
        $item = OrderItem::factory()->for($order)->create(['quantity' => 2]);
        $before = $order->fresh()->getRawOriginal();
        $stock = $item->plato->stock;
        $available = $item->plato->availableStock();

        $this->actingAs($buyer)->withSession(['cart' => [$item->plato_id => 1]])->postJson(route('payments.store', $order), [
            'status' => 'paid', 'total_cents' => 1, 'checkout_url' => 'https://example.invalid',
        ])->assertStatus(501)->assertJsonPath('message', 'La integración con Mercado Pago todavía no está implementada. El pedido continúa sin cambios.')
            ->assertDontSee('test-only-private-sentinel')->assertDontSee('test-only-public-sentinel')
            ->assertSessionHas('cart.'.$item->plato_id, 1);

        $this->assertSame($before, $order->fresh()->getRawOriginal());
        $this->assertSame($stock, $item->plato->fresh()->stock);
        $this->assertSame($available, $item->plato->availableStock());
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        Http::assertNothingSent();
    }

    #[TestWith(['pending_payment'])]
    #[TestWith(['paid'])]
    #[TestWith(['rejected'])]
    #[TestWith(['cancelled'])]
    #[TestWith(['expired'])]
    public function test_return_only_redirects_to_saved_order_without_trusting_query_parameters(string $status): void
    {
        Http::preventStrayRequests();
        $buyer = User::factory()->create();
        $order = Order::factory()->for($buyer)->create(['status' => $status]);
        $before = $order->fresh()->getRawOriginal();

        $this->actingAs($buyer)->get(route('payments.result', [
            'order' => $order, 'status' => 'approved', 'collection_status' => 'approved', 'payment_id' => 'unverified',
        ]))->assertRedirect(route('orders.show', $order));

        $this->assertSame($before, $order->fresh()->getRawOriginal());
        Http::assertNothingSent();
    }

    public function test_guest_is_redirected_to_buyer_login(): void
    {
        $this->post(route('payments.store', 123))->assertRedirect(route('buyer.login'));
        $this->get(route('payments.result', 123))->assertRedirect(route('buyer.login'));
    }

    public function test_another_buyer_cannot_start_or_view_payment_result(): void
    {
        $order = Order::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->post(route('payments.store', $order))->assertNotFound();
        $this->get(route('payments.result', $order))->assertNotFound();
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => Order::PendingPayment]);
    }

    public function test_admin_cannot_access_buyer_payment_routes(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['es_admin' => true])->save();
        $this->actingAs($admin);

        $this->post(route('payments.store', 123))->assertForbidden();
        $this->get(route('payments.result', 123))->assertForbidden();
    }

    public function test_webhook_placeholder_does_not_accept_or_apply_payment_notifications(): void
    {
        $this->freezeTime();
        Http::preventStrayRequests();
        $item = OrderItem::factory()->create();
        $before = $item->order->getRawOriginal();
        $available = $item->plato->availableStock();

        $this->postJson(route('mercadopago.webhook'), [
            'type' => 'order', 'data' => ['id' => 'unverified'],
            'external_reference' => 'pedido-'.$item->order_id, 'status' => 'approved',
        ])->assertStatus(501)->assertJsonPath('message', 'La recepción de notificaciones de Mercado Pago todavía no está implementada.');

        $this->assertSame($before, $item->order->fresh()->getRawOriginal());
        $this->assertSame($available, $item->plato->availableStock());
        Http::assertNothingSent();
    }

    public function test_csrf_protection_remains_active_until_verified_webhooks_are_implemented(): void
    {
        $buyer = User::factory()->create();
        $this->app->instance('env', 'local');

        $this->actingAs($buyer)->post(route('payments.store', 123))->assertStatus(419);
        $this->postJson(route('mercadopago.webhook'), ['status' => 'approved'])->assertStatus(419);
        $this->assertDatabaseCount('orders', 0);
    }
}
