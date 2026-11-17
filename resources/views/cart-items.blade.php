@foreach ($cart['notices'] as $notice)
    <p class="alert alert-warning" role="status">{{ $notice }}</p>
@endforeach
@forelse ($cart['items'] as $item)
    <div class="card cart-item mb-3">
        <div class="cart-item-grid">
            <div>
                <img src="{{ asset($item['plato']->imagen) }}" class="cart-item-image" alt="{{ $item['plato']->nombre }}">
            </div>
            <div>
                <div class="card-body">
                    <p class="card-title">{{ $item['plato']->nombre }}</p>
                    <p class="card-text">Precio: ${{ number_format($item['unit_cents'] / 100, 2, ',', '.') }}</p>
                    <div class="cart-quantity" role="group" aria-label="Cantidad de {{ $item['plato']->nombre }}">
                        <form method="POST" action="{{ route('cart.update', $item['plato']) }}" data-cart-form>
                            @csrf @method('PATCH')
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                            <button type="submit" class="btn btn-outline-secondary btn-sm" @disabled($item['quantity'] <= 1) aria-label="Disminuir {{ $item['plato']->nombre }}">−</button>
                        </form>
                        <span>Cantidad: {{ $item['quantity'] }}</span>
                        <form method="POST" action="{{ route('cart.update', $item['plato']) }}" data-cart-form>
                            @csrf @method('PATCH')
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                            <button type="submit" class="btn btn-outline-secondary btn-sm" @disabled($item['quantity'] >= $item['available_stock']) aria-label="Incrementar {{ $item['plato']->nombre }}">+</button>
                        </form>
                    </div>
                    <p class="card-text">Subtotal: ${{ number_format($item['subtotal_cents'] / 100, 2, ',', '.') }}</p>
                    <form method="POST" action="{{ route('cart.destroy', $item['plato']) }}" data-cart-form>
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Eliminar {{ $item['plato']->nombre }}">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@empty
    <p class="cart-empty">Tu carrito está vacío. Elegí un plato para empezar.</p>
@endforelse
<p class="cart-total">Total: ${{ number_format($cart['total_cents'] / 100, 2, ',', '.') }}</p>
@if ($cart['items'])
    <p class="small">Los precios y el stock se actualizan al consultar el carrito. Agregar un plato no reserva stock.</p>
    <a class="btn btn-primary cart-checkout" href="{{ route('checkout.create') }}">Finalizar la compra</a>
@endif
