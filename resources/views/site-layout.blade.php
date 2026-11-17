<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Menú a tu Medida</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/interface.css') }}">
</head>
<body class="site-page">
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    @include('site-navigation', ['adminNavigation' => $adminNavigation ?? false, 'showCart' => View::hasSection('cart-panel')])
    <main id="contenido" class="container site-main @yield('main-class')">
        @include('site-messages')
        @yield('content')
    </main>
    <footer class="site-footer">
        <div class="container"><span>Menú a tu Medida</span><a href="{{ route('home') }}">Volver al menú</a></div>
    </footer>
    @yield('cart-panel')
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    @hasSection('cart-panel')<script src="{{ asset('js/cart.js') }}" defer></script>@endif
</body>
</html>