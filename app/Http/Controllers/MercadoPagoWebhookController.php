<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class MercadoPagoWebhookController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'message' => 'La recepción de notificaciones de Mercado Pago todavía no está implementada.',
        ], 501);
    }
}
