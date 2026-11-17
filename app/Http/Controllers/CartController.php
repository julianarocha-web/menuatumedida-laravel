<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Plato;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private Carrito $carrito) {}

    public function index(Request $request): JsonResponse|RedirectResponse
    {
        return $this->respond($request, '');
    }

    public function store(Request $request, Plato $plato): JsonResponse|RedirectResponse
    {
        $this->carrito->add($plato, $this->quantity($request));

        return $this->respond($request, 'Plato agregado al carrito.');
    }

    public function update(Request $request, Plato $plato): JsonResponse|RedirectResponse
    {
        abort_unless(array_key_exists($plato->id, $request->session()->get('cart', [])), 404);
        $this->carrito->update($plato, $this->quantity($request));

        return $this->respond($request, 'Cantidad actualizada.');
    }

    public function destroy(Request $request, int $plato): JsonResponse|RedirectResponse
    {
        $this->carrito->remove($plato);

        return $this->respond($request, 'Plato eliminado del carrito.');
    }

    private function quantity(Request $request): int
    {
        $validated = $request->validateWithBag('cart', ['quantity' => ['required', 'integer', 'min:1', 'max:2147483647']], [
            'quantity.required' => 'Indicá una cantidad.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'quantity.max' => 'La cantidad supera el máximo permitido.',
        ]);

        return (int) $validated['quantity'];
    }

    private function respond(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            $cart = $this->carrito->summary();

            return response()->json([
                'html' => view('cart-items', compact('cart'))->render(),
                'total_cents' => $cart['total_cents'],
                'count' => $cart['count'],
                'message' => $message,
            ]);
        }

        return redirect()->to(url()->previous() === url()->current() ? route('home') : url()->previous())
            ->with('cart_open', true)->with('cart_status', $message);
    }
}
