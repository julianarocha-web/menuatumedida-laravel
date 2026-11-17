<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Olvidate de planificar tus comidas. En Menú a tu Medida cocinamos viandas caseras y las llevamos a tu casa. Elegí tu plan semanal hoy.">
    <meta name="keywords" content="viandas, comida casera, plan semanal, menú saludable, delivery de comida">
    <meta name="author" content="Juliana Rocha"> 
    <meta name="robots" content="index, follow">
    <title>Viandas - Menú a tu medida</title>
    <!-- Css BS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/interface.css') }}">
    <!-- css aminate -->
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
</head>
<body class="store-home">
    <div class="fondo-forma">
        <!-- start nav -->
        <nav class="navbar navbar-expand-lg menu">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
            <img src="{{ asset('img/logo.png') }}" alt="Logo de Menú a tu medida">
            </a>
            <!-- Botones SIEMPRE visibles -->
            <div class="botones align-items-center ms-auto order-lg-2">
            <!-- Buscar -->
            <button class="btn search-btn btn-outline-secondary" type="button">
                <img src="{{ asset('img/search.svg') }}" alt="Buscar">
            </button>
            <!-- Carrito -->
            <button class="btn cart-btn btn-outline-secondary" type="button"
                    aria-label="Abrir carrito" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions">
                <img src="{{ asset('img/cart2.svg') }}" alt="Carrito">
            </button>
            <!-- Iniciar sesión -->
            @auth
            @if (auth()->user()->es_admin)
                <a class="inicioSesion" href="{{ route('admin.platos.index') }}" aria-label="Administración" title="Administración">Administración</a>
            @else
                <a class="inicioSesion" href="{{ route('profile.edit') }}" aria-label="Mi perfil" title="Mi perfil">Mi perfil</a>
            @endif
            @else
            <a class="inicioSesion d-inline-flex align-items-center justify-content-center" href="{{ route('buyer.login') }}" aria-label="Iniciar sesión" title="Iniciar sesión">Iniciar sesión</a>
            @endauth
            </div>
            <!-- Botón hamburguesa -->
            <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
            <img src="{{ asset('img/list-stars.svg') }}" alt="Menú">
            </button>
            <!-- Menú colapsable -->
            <div class="collapse navbar-collapse order-lg-1" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#destacados">Destacados</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#categorias">Categorías</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#momentos">Momentos</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#contacto">Contacto</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#faqs">FAQ</a>
                </li>
            </ul>
            </div>
        </div>
        </nav>
        <!-- end nav -->
        <!-- start header -->
        <header class="plato">
        <div class="titulos">
            <h1>Olvidate de <span class="palabraDestacada">planificar</span> las <span class="palabraDestacada">comidas</span> de tu semana</h1>
            <p class="headerp">Te armamos el menú, lo cocinamos y te lo llevamos. Vos solo elegís el plan que más se adapta a tu rutina.</p>
            <button class="conocenos">Conocenos</button>
        </div>
        <img src="{{ asset('img/plato-pollo.webp') }}" alt="Plato de pollo asado con vegetales asados">
        </header>
    </div>
    <!-- start main content  -->
    <main>
        <h2 id="destacados" class="fs-2 text-center w-100">Nuestros platos <span class="destacados">DESTACADOS</span></h2>
        <section class="products">
            @php
                $clasesDestacados = ['figure', 'figure figure-naranja', 'figure figure-violeta', 'figure figure-rosa'];
            @endphp
            @forelse ($destacados as $destacado)
                <figure class="{{ $clasesDestacados[$loop->index % count($clasesDestacados)] }}">
                    <a class="figure-img" href="{{ route('platos.show', $destacado) }}">
                        <img src="{{ asset($destacado->imagen) }}" alt="{{ $destacado->nombre }}">
                    </a>
                    <figcaption><a href="{{ route('platos.show', $destacado) }}">{{ $destacado->nombre }}</a></figcaption>
                    <p>{{ $destacado->descripcion }}</p>
                    <div class="figure-footer">
                        <span class="price">${{ number_format((float) $destacado->precio, 0, '.', ',') }}</span>
                        @if ($destacado->stock > 0)
                            <span>Stock: {{ $destacado->stock }}</span>
                        @else
                            <span>Sin stock</span>
                        @endif
                    </div>
                    @include('cart-add', ['plato' => $destacado])
                </figure>
            @empty
                <p class="text-center w-100">No hay platos destacados disponibles.</p>
            @endforelse
        </section>
        <h2 id="categorias" class="fs-2 text-center w-100">Explorá las <span class="destacados">CATEGORÍAS</span></h2>
        <section class="fondo-categorias">
            <div class="contenedor">
                <div class="flex">
                    @php
                        $imagenesCategoria = [
                            'Desayunos' => 'img/desayunos.webp',
                            'Almuerzos' => 'img/almuerzos.webp',
                            'Meriendas' => 'img/meriendas.webp',
                            'Cenas' => 'img/cenas.webp',
                        ];
                    @endphp
                    @foreach ($categorias as $categoria)
                        @php
                            $claseCategoria = strtolower($categoria->nombre);
                        @endphp
                        <figure class="categoria-{{ $claseCategoria }} categorias">
                            <figcaption>{{ $categoria->nombre }}</figcaption>
                            <img src="{{ asset($imagenesCategoria[$categoria->nombre] ?? 'img/categorias.png') }}" alt="Opciones de {{ strtolower($categoria->nombre) }}">
                            <a href="#categoria-{{ $categoria->id }}">Explorar</a>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
        <section class="buscador">
            <h4 class="fs-2 text-center w-100">¿Qué se te antoja hoy?</h4>
            <div class="filters">
                <div class="filters-search">
                    <input type="search" placeholder="Buscar">
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-search-heart" viewBox="0 0 16 16">
                        <path d="M6.5 4.482c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.69 0-5.018"/>
                        <path d="M13 6.5a6.47 6.47 0 0 1-1.258 3.844q.06.044.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11"/>
                        </svg>
                    </button>
                </div><!-- end search -->              
            </div><!-- end filter -->
        </section>
        <section>
            @foreach ($categorias as $categoria)
            @php $clase = strtolower($categoria->nombre); @endphp
            @if ($loop->first)
                <span id="momentos"></span>
            @endif
            <h3 id="categoria-{{ $categoria->id }}" class="{{ $clase }}">{{ strtoupper($categoria->nombre) }}</h3>
            <div class="d-flex momentos-grid">
                @foreach ($categoria->platos as $plato)
                <figure class="figura-{{ $clase }} figura">
                    <figcaption><a href="{{ route('platos.show', $plato) }}">{{ $plato->nombre }}</a></figcaption>
                    <a href="{{ route('platos.show', $plato) }}"><img class="figura-img" src="{{ asset($plato->imagen) }}" alt="{{ $plato->nombre }}"></a>
                    <p>{{ $plato->descripcion }}</p>
                    <div class="figura-footer">
                        <span class="precio">${{ number_format($plato->precio, 0, '.', ',') }}</span>
                    <span>{{ $plato->stock > 0 ? 'Stock: '.$plato->stock : 'Sin stock' }}</span>
                    </div>
                    @include('cart-add', ['plato' => $plato])
                </figure>
                @endforeach
            </div>
            @endforeach
        </section>
        <h2 id="contacto" class="fs-2 text-center w-100">¿Querés ser parte? <span class="destacados">REGISTRATE</span></h2>
        <section class="registration-section">
            <div class="flexbox">
            <!-- form -->
              <div class="card-form registration-panel">
                <p>Creá tu cuenta</p>
                    <form method="POST" action="{{ route('buyer.register') }}">
                        @csrf
                        @foreach ($errors->all() as $error)<p class="alert alert-danger" role="alert">{{ $error }}</p>@endforeach
                        <div class="mb-3">
                            <input name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" aria-label="Nombre" type="text" class="form-control" placeholder="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <input name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" aria-label="Email" type="email" class="form-control" placeholder="E-mail" required>
                        </div>
                        <div class="mb-3">
                            <input name="telefono" value="{{ old('telefono') }}" autocomplete="tel" maxlength="30" aria-label="Teléfono" type="tel" class="form-control" placeholder="Número de telefono" required>
                        </div>
                        <div class="mb-3">
                            <input name="password" autocomplete="new-password" aria-label="password" minlength="8" type="password" class="form-control" placeholder="Contraseña" required>
                              <div class="form-text">
                                La contraseña debe contener al menos 8 caracteres, una mayúscula y un número.
                              </div>
                        </div>
                        <div class="mb-3">
                            <input name="password_confirmation" autocomplete="new-password" aria-label="password_confirmation" minlength="8" type="password" class="form-control" placeholder="Confirmar contraseña" required>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">Prefiero..</label>
                            <select name="modalidad_entrega" id="state" class="form-select" required>
                                <option value="" disabled @selected(! old('modalidad_entrega'))>Forma de envío</option>
                                <option value="pickup" @selected(old('modalidad_entrega') === 'pickup')>Pick-up</option>
                                <option value="delivery" @selected(old('modalidad_entrega') === 'delivery')>Delivery</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input name="terms" value="1" @checked(old('terms')) type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto términos y condiciones
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2">Registrarse</button>
                        <a href="{{ route('buyer.login') }}" class="btn btn-outline-primary w-100">Ya tienes cuenta? Inicia sesión</a>
                    </form>
                </div>
            <!--end form -->
                <div class="formaImagen">
                    <img src="{{ asset('img/fotoconforma.png') }}" alt="forma violeta">
                </div>
            </div>    
        </section>
        <h2 id="faqs" class="fs-2 text-center w-100">Preguntas frecuentes</h2>
        <section class="faq">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <p class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne">
                            ¿Cómo funciona Menú a tu medida?
                        </button>
                    </p>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Ofrecemos platos listos para desayuno, almuerzo, merienda y cena.</div>
                    </div>
                </div>

                <div class="accordion-item">
                    <p class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo">
                            ¿Tengo que pedir con anticipación?
                        </button>
                    </p>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Sí. Los pedidos se realizan con al menos 24 horas de anticipación.</div>
                    </div>
                </div>

                <div class="accordion-item">
                    <p class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree">
                            ¿Hay opciones para diferentes preferencias alimenticias?
                        </button>
                    </p>
                    <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Claro. Contamos con platos que incluyen carnes, pescados y opciones vegetarianas.</div>
                    </div>
                </div>

                <div class="accordion-item">
                    <p class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour">
                            ¿Ofrecen entrega a domicilio?
                        </button>
                    </p>
                    <div id="flush-collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Sí. Realizamos entregas a domicilio en la zona especificada.</div>
                    </div>
                </div>

                <div class="accordion-item">
                    <p class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive">
                            ¿Puedo armar un pack semanal personalizado?
                        </button>
                    </p>
                    <div id="flush-collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">Por supuesto. Podés elegir entre los packs prearmados o armar uno a medida.</div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- end main content -->
    <footer>
        <div class="flex-footer">
            <div class="footer-logo">
                <a href="#"><img src="{{ asset('img/logo.png') }}" alt="Logo de Menú a tu medida"></a>
            </div>
            <div class="footer-links">
                <a href="#destacados">Destacados</a>
                <a href="#categorias">Categorías</a>
                <a href="#momentos">Momentos</a>
                <a href="#contacto">Contacto</a>
                <a href="#faqs">FAQ</a>
            </div>
            <div class="footer-redes">
                <a href="#">
                    <img src="{{ asset('img/instagram.svg') }}" alt="logo instagram">
                </a>
                <a href="#">
                    <img src="{{ asset('img/facebook.svg') }}" alt="logo facebook">
                </a>
                <a href="#">
                    <img src="{{ asset('img/envelope.svg') }}" alt="logo sobre mail">
                </a>
            </div>
        </div>
    </footer>
    <!-- burbuja de whatsapp -->
    <a href="https://wa.me/54111234567" class="wa" target="_blank">
        <img src="{{ asset('img/whatsapp.svg') }}" alt="burbuja de whatsapp">
    </a>
    @include('cart-offcanvas', ['cart' => $cart])
    <!-- script BS -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}" defer></script>
</body>
</html>
