<div class="offcanvas offcanvas-end cart-panel" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" data-cart-url="{{ route('cart.index') }}" data-cart-open="{{ session('cart_open') || $errors->cart->any() ? 'true' : 'false' }}">
    <div class="offcanvas-header">
        <p class="fs-3 mt-0 mb-0" id="offcanvasWithBothOptionsLabel">Tu carrito</p>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar carrito"></button>
    </div>
    <section class="offcanvas-body">
        <div data-cart-feedback role="status" aria-live="polite">
            @if (session('cart_status')) <p class="alert alert-success">{{ session('cart_status') }}</p> @endif
            @foreach ($errors->cart->all() as $error) <p class="alert alert-danger">{{ $error }}</p> @endforeach
        </div>
        <div data-cart-items>@include('cart-items', ['cart' => $cart])</div>
    </section>
</div>
