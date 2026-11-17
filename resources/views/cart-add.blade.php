<form method="POST" action="{{ route('cart.store', $plato) }}" data-cart-form class="cart-add">
    @csrf
    <input type="hidden" name="quantity" value="1">
    <button class="btn btn-outline-secondary btn-sm" type="submit" @disabled($plato->stock < 1) aria-label="Agregar {{ $plato->nombre }} al carrito">
        {{ $plato->stock > 0 ? 'Agregar al carrito' : 'Agotado' }}
    </button>
</form>
