@extends('order-layout')
@section('title', 'Revisar pedido')
@section('content')
    <div class="page-heading"><p class="eyebrow">Tu compra</p><h1>Revisar pedido</h1><p>Comprador: {{ auth()->user()->name }}</p></div>
    @foreach ($cart['notices'] as $notice)<p class="alert alert-warning" role="status">{{ $notice }}</p>@endforeach
    <div class="checkout-grid">
        <section aria-label="Platos de tu pedido" class="order-items">
            @forelse ($cart['items'] as $item)
                <article class="surface purchase-item">
                    <h2>{{ $item['plato']->nombre }}</h2>
                    <p>{{ $item['quantity'] }} × ${{ number_format($item['unit_cents'] / 100, 2, ',', '.') }}</p>
                    <p class="item-subtotal">Subtotal: ${{ number_format($item['subtotal_cents'] / 100, 2, ',', '.') }}</p>
                </article>
            @empty
                <div class="surface empty-state"><h2>Tu carrito está vacío.</h2><p>Elegí un plato para empezar.</p><a class="btn btn-primary" href="{{ route('home') }}">Explorar el menú</a></div>
            @endforelse
        </section>
        <aside class="surface order-summary" aria-label="Resumen de compra">
            <h2>Resumen de compra</h2>
            <p class="order-total">Total: ${{ number_format($cart['total_cents'] / 100, 2, ',', '.') }} ARS</p>
            <p>Confirmar registra un pedido pendiente de pago y reserva disponibilidad durante 15 minutos. Si cancelás o vence el plazo, la reserva se libera automáticamente.</p>
            <p class="alert alert-info">Los pagos todavía no están habilitados. No se realizará ningún cobro ni comenzará la preparación.</p>
            @if ($cart['items'])
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <input type="hidden" name="checkout_token" value="{{ $token }}">
                    <button class="btn btn-primary w-100" type="submit">Confirmar pedido pendiente de pago</button>
                </form>
            @endif
            <a class="text-link" href="{{ route('home') }}">Volver al menú y editar carrito</a>
        </aside>
    </div>
@endsection