@extends('site-layout')
@section('title', 'Mi perfil')
@section('content')
<section class="surface auth-panel">
            <h1>Mi perfil</h1>
            <p><a class="text-link" href="{{ route('orders.index') }}">Mis pedidos</a></p>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
                <label class="form-label" for="name">Nombre</label>
                <input @error('name') aria-invalid="true" @enderror class="form-control mb-3" id="name" name="name" value="{{ old('name', $buyer->name) }}" maxlength="255" autocomplete="name" required>
                <label class="form-label" for="email">Email</label>
                <input @error('email') aria-invalid="true" @enderror class="form-control mb-3" id="email" name="email" type="email" value="{{ old('email', $buyer->email) }}" maxlength="255" autocomplete="email" required>
                <label class="form-label" for="telefono">Teléfono</label>
                <input @error('telefono') aria-invalid="true" @enderror class="form-control mb-3" id="telefono" name="telefono" type="tel" value="{{ old('telefono', $buyer->telefono) }}" maxlength="30" autocomplete="tel" required>
                <label class="form-label" for="modalidad_entrega">Modalidad de entrega</label>
                <select @error('modalidad_entrega') aria-invalid="true" @enderror class="form-select mb-3" id="modalidad_entrega" name="modalidad_entrega" required>
                    <option value="pickup" @selected(old('modalidad_entrega', $buyer->modalidad_entrega) === 'pickup')>Pick-up</option>
                    <option value="delivery" @selected(old('modalidad_entrega', $buyer->modalidad_entrega) === 'delivery')>Delivery</option>
                </select>
                <button class="btn btn-primary w-100" type="submit">Guardar perfil</button>
            </form>
            <form method="POST" action="{{ route('buyer.logout') }}" class="mt-3">@csrf<button class="btn btn-outline-secondary w-100" type="submit">Cerrar sesión</button></form>
        </section>
@endsection
