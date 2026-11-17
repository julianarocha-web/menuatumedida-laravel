<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\ValidationException;

class Carrito
{
    public function __construct(private Session $session) {}

    public function add(Plato $plato, int $quantity): void
    {
        $items = $this->session->get('cart', []);
        $this->update($plato, ($items[$plato->id] ?? 0) + $quantity);
    }

    public function update(Plato $plato, int $quantity): void
    {
        abort_unless($plato->activo && $plato->categoria?->activo, 404);

        $available = $plato->availableStock();
        if ($quantity < 1 || $quantity > $available) {
            throw ValidationException::withMessages([
                'quantity' => $available < 1 ? 'Este plato está agotado.' : 'La cantidad debe estar entre 1 y '.$available.'.',
            ])->errorBag('cart');
        }

        $items = $this->session->get('cart', []);
        $items[$plato->id] = $quantity;
        $this->session->put('cart', $items);
    }

    public function remove(int $plato): void
    {
        $items = $this->session->get('cart', []);
        unset($items[$plato]);
        $this->session->put('cart', $items);
    }

    /**
     * @return array{items: list<array{plato: Plato, quantity: int, unit_cents: int, subtotal_cents: int, available_stock: int}>, total_cents: int, count: int, notices: list<string>}
     */
    public function summary(): array
    {
        $selection = $this->session->get('cart', []);
        $platos = Plato::query()->with('categoria')->whereIn('id', array_keys($selection))->get()->keyBy('id');
        $items = [];
        $validSelection = [];
        $notices = [];
        $total = 0;
        $count = 0;

        foreach ($selection as $id => $quantity) {
            $plato = $platos->get($id);
            $available = $plato?->availableStock() ?? 0;
            if (! $plato || ! $plato->activo || ! $plato->categoria?->activo || $available < 1) {
                $notices[] = 'Se retiró un plato agotado o que ya no está disponible.';

                continue;
            }

            $availableQuantity = min((int) $quantity, $available);
            if ($availableQuantity < 1) {
                continue;
            }
            if ($availableQuantity !== $quantity) {
                $notices[] = 'Se ajustó la cantidad de '.$plato->nombre.' al stock disponible.';
            }

            [$pesos, $centavos] = explode('.', $plato->precio);
            $unitCents = (int) $pesos * 100 + (int) $centavos;
            $subtotal = $unitCents * $availableQuantity;
            $items[] = ['plato' => $plato, 'quantity' => $availableQuantity, 'unit_cents' => $unitCents, 'subtotal_cents' => $subtotal, 'available_stock' => $available];
            $validSelection[$id] = $availableQuantity;
            $total += $subtotal;
            $count += $availableQuantity;
        }

        $this->session->put('cart', $validSelection);

        return ['items' => $items, 'total_cents' => $total, 'count' => $count, 'notices' => $notices];
    }
}
