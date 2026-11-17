@extends('site-layout')
@section('title', 'Ingresar')
@section('content')
<section class="surface auth-panel">
            <h1>Iniciar sesión</h1>
            <form method="POST" action="{{ route('buyer.login.store') }}">
                @csrf
                <label class="form-label" for="email">Email</label>
                <input @error('email') aria-invalid="true" @enderror class="form-control mb-3" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required>
                <label class="form-label" for="password">Contraseña</label>
                <input @error('password') aria-invalid="true" @enderror class="form-control mb-3" id="password" name="password" type="password" autocomplete="current-password" required>
                <button class="btn btn-primary w-100" type="submit">Ingresar</button>
            </form>
            <a class="btn btn-outline-primary w-100 mt-3" href="{{ route('home') }}#contacto">Crear cuenta</a>
        </section>
@endsection
