@extends('admin.layout')

@section('title', 'Acceso administrador')

@section('content')
    <section class="surface auth-panel">
        <h1 class="h2 mb-4">Acceso administrador</h1>

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Correo electrónico</label>
                <input @error('email') aria-invalid="true" @enderror class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <input @error('password') aria-invalid="true" @enderror class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Iniciar sesión</button>
        </form>
    </section>
@endsection
