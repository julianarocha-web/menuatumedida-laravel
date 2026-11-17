@extends('order-layout')
@section('title', 'Mis pedidos')
@section('content')
    <div class="page-heading"><p class="eyebrow">Tu cuenta</p><h1>Mis pedidos</h1><p>Consultá los detalles y el estado de tus compras.</p></div>
    <div class="order-history">
        @forelse ($orders as $order)
            <article class="surface order-history-item">
                <div><h2><a class="text-link" href="{{ route('orders.show', $order) }}">Pedido #{{ $order->id }}</a></h2><p>{{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                @include('order-status', ['order' => $order])
                <p class="order-total">Total: ${{ number_format($order->total_cents / 100, 2, ',', '.') }} ARS</p>
                <a class="btn btn-outline-secondary" href="{{ route('orders.show', $order) }}">Ver pedido</a>
            </article>
        @empty
            <div class="surface empty-state"><h2>Todavía no tenés pedidos.</h2><p>Elegí tus platos favoritos para empezar.</p><a class="btn btn-primary" href="{{ route('home') }}">Explorar el menú</a></div>
        @endforelse
    </div>
    {{ $orders->links('site-pagination') }}
@endsection