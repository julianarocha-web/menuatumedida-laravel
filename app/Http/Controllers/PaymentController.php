<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, int $order): JsonResponse
    {
        $request->user()->orders()->findOrFail($order);

        return response()->json([
            'message' => 'La integración con Mercado Pago todavía no está implementada. El pedido continúa sin cambios.',
        ], 501);
    }

    public function result(Request $request, int $order): RedirectResponse
    {
        $order = $request->user()->orders()->findOrFail($order);

        return redirect()->route('orders.show', $order);
    }
}
