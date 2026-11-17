@extends('site-layout')
@section('title', $plato->nombre)
@section('content')
    <article class="product-detail surface">
        <div class="product-detail-image"><img src="{{ asset($plato->imagen) }}" alt="{{ $plato->nombre }}"></div>
        <div class="product-detail-content">
            <p class="eyebrow">{{ $plato->categoria->nombre }}</p>
            <h1>{{ $plato->nombre }}</h1>
            <p>{{ $plato->descripcion }}</p>
            <p class="product-price">${{ number_format((float) $plato->precio, 0, '.', ',') }}</p>
            @if ($plato->stock > 0)
                <p class="status-badge status-available">Disponible: {{ $plato->stock }}</p>
            @else
                <p class="status-badge status-expired">Sin stock por el momento</p>
            @endif
            @include('cart-add', ['plato' => $plato])
        </div>
    </article>
@endsection
@section('cart-panel')
    @include('cart-offcanvas', ['cart' => $cart])
@endsection