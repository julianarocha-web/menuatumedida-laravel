<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Order;
use App\Models\Plato;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request, Carrito $carrito): View
    {
        $cart = $carrito->summary();
        $snapshot = [];
        foreach ($cart['items'] as $item) {
            $snapshot[$item['plato']->id] = ['quantity' => $item['quantity'], 'unit_cents' => $item['unit_cents']];
        }
        $review = $request->session()->get('checkout');
        if (! $review || $review['snapshot'] !== $snapshot || $review['user_id'] !== $request->user()->id) {
            $review = ['token' => (string) Str::uuid(), 'snapshot' => $snapshot, 'user_id' => $request->user()->id];
            $request->session()->put('checkout', $review);
        }

        return view('checkout', ['cart' => $cart, 'token' => $review['token']]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['checkout_token' => ['required', 'uuid']]);
        $token = $validated['checkout_token'];
        $order = DB::transaction(function () use ($request, $token): Order {
            $buyer = User::query()->lockForUpdate()->findOrFail($request->user()->id);
            $existing = $buyer->orders()->where('checkout_token', $token)->lockForUpdate()->first();
            if ($existing) {
                return $existing;
            }

            $review = $request->session()->get('checkout');
            if (! $review || $review['token'] !== $token || $review['user_id'] !== $buyer->id) {
                $this->invalid('La revisión venció. Volvé a revisar tu pedido.');
            }
            $selection = $request->session()->get('cart', []);
            if (! $selection) {
                $this->invalid('Tu carrito está vacío.');
            }
            $platos = Plato::query()->whereIn('id', array_keys($selection))->orderBy('id')->lockForUpdate()->get();
            $categories = Categoria::query()->whereIn('id', $platos->pluck('categoria_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
            $snapshot = [];
            $items = [];
            $total = 0;
            foreach ($selection as $id => $quantity) {
                $plato = $platos->firstWhere('id', $id);
                if (! is_int($quantity) || $quantity < 1 || ! $plato || ! $plato->activo || ! $categories->get($plato->categoria_id)?->activo) {
                    $this->invalid('Un plato o su cantidad ya no es válido. Revisá el carrito.');
                }
                if ($quantity > $plato->availableStock(true)) {
                    $this->invalid('No hay stock suficiente de '.$plato->nombre.'. Revisá el carrito.');
                }
                [$pesos, $centavos] = explode('.', $plato->precio);
                $unitCents = (int) $pesos * 100 + (int) $centavos;
                $snapshot[$id] = ['quantity' => $quantity, 'unit_cents' => $unitCents];
                $items[] = ['plato_id' => $plato->id, 'nombre' => $plato->nombre, 'quantity' => $quantity, 'unit_cents' => $unitCents];
                $total += $quantity * $unitCents;
            }
            if ($snapshot !== $review['snapshot']) {
                $this->invalid('El carrito o los precios cambiaron. Revisá el nuevo total antes de confirmar.');
            }

            $order = $buyer->orders()->create([
                'checkout_token' => $token, 'status' => Order::PendingPayment,
                'total_cents' => $total, 'reserved_until' => now()->addMinutes(15),
            ]);
            $order->items()->createMany($items);

            return $order;
        }, 3);

        if ($request->session()->get('checkout.token') === $token) {
            $request->session()->forget(['cart', 'checkout']);
        }

        return redirect()->route('orders.show', $order);
    }

    private function invalid(string $message): never
    {
        throw ValidationException::withMessages(['checkout' => $message])->redirectTo(route('checkout.create'));
    }
}
