@extends('order-layout')
@section('title', 'Detalle del pedido')
@section('content')
    <div class="page-heading"><p class="eyebrow">Detalle de tu compra</p><h1>Pedido #{{ $order->id }}</h1><p>Creado: {{ $order->created_at->format('d/m/Y H:i') }}</p>@include('order-status', ['order' => $order])</div>
    <div class="checkout-grid">
        <section class="order-items" aria-label="Platos del pedido">
            @foreach ($order->items as $item)
                <article class="surface purchase-item">
                    <h2>{{ $item->nombre }}</h2>
                    <p>{{ $item->quantity }} × ${{ number_format($item->unit_cents / 100, 2, ',', '.') }}</p>
                    <p class="item-subtotal">Subtotal: ${{ number_format($item->quantity * $item->unit_cents / 100, 2, ',', '.') }}</p>
                </article>
            @endforeach
        </section>
        <aside class="surface order-summary" aria-label="Resumen del pedido">
            <h2>Resumen del pedido</h2>
            <p class="order-total">Total: ${{ number_format($order->total_cents / 100, 2, ',', '.') }} {{ $order->currency }}</p>
            @if ($order->currentStatus() === \App\Models\Order::PendingPayment)
                <p>Reserva hasta {{ $order->reserved_until->format('d/m/Y H:i') }} (hora del servidor). Al vencer se libera la disponibilidad. Este pedido todavía no está pagado.</p>
                <p class="alert alert-info">El pago en línea estará disponible en una próxima etapa. No se realizó ningún cobro ni se inició la preparación.</p>
                <form method="POST" action="{{ route('orders.destroy', $order) }}">@csrf @method('DELETE')<button class="btn btn-outline-danger w-100" type="submit">Cancelar pedido y liberar reserva</button></form>
            @elseif ($order->currentStatus() === \App\Models\Order::Expired)
                <p class="alert alert-warning">La reserva venció y la disponibilidad se liberó. Podés armar un nuevo carrito con precios y stock actuales.</p>
            @elseif ($order->currentStatus() === \App\Models\Order::Cancelled)
                <p>El pedido fue cancelado y su reserva se liberó.</p>
            @endif
            <a class="text-link" href="{{ route('orders.index') }}">Volver a mis pedidos</a>
        </aside>
    </div>
@endsection