<nav class="navbar menu site-navigation" aria-label="{{ $adminNavigation ? 'Administración' : 'Navegación principal' }}">
    <div class="container site-nav-inner">
        <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('img/logo.png') }}" alt="Menú a tu Medida" width="50" height="50"></a>
        <div class="site-nav-links">
            @if ($adminNavigation && auth()->user()?->es_admin)
                <a class="site-nav-link" href="{{ route('admin.platos.index') }}" @if(request()->routeIs('admin.platos.*')) aria-current="page" @endif>Platos</a>
                <a class="site-nav-link" href="{{ route('admin.categorias.index') }}" @if(request()->routeIs('admin.categorias.*')) aria-current="page" @endif>Categorías</a>
            @endif
            <a class="site-nav-link" href="{{ route('home') }}">Volver al menú</a>
            @unless ($adminNavigation)
                @auth
                    @if (auth()->user()->es_admin)
                        <a class="site-nav-link" href="{{ route('admin.platos.index') }}">Administración</a>
                    @else
                        <a class="site-nav-link" href="{{ route('orders.index') }}" @if(request()->routeIs('orders.*', 'checkout.*')) aria-current="page" @endif>Mis pedidos</a>
                        <a class="site-nav-link" href="{{ route('profile.edit') }}" @if(request()->routeIs('profile.*')) aria-current="page" @endif>Mi perfil</a>
                    @endif
                @else
                    <a class="site-nav-link" href="{{ route('buyer.login') }}" @if(request()->routeIs('buyer.login')) aria-current="page" @endif>Iniciar sesión</a>
                @endauth
            @endunless
            @if ($adminNavigation && auth()->user()?->es_admin)
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-outline-secondary" type="submit">Cerrar sesión</button></form>
            @endif
            @if ($showCart)
                <button class="btn cart-btn btn-outline-secondary" type="button" aria-label="Abrir carrito" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"><img src="{{ asset('img/cart2.svg') }}" alt="Carrito"></button>
            @endif
        </div>
    </div>
</nav>