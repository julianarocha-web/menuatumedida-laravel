@extends('admin.layout')

@section('title', 'Administrar platos')

@section('content')
    <div class="page-heading-row">
        <h1 class="h2 mb-0">Administrar platos</h1>
        <a class="btn btn-primary" href="{{ route('admin.platos.create') }}">Nuevo plato</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle admin-table">
            <thead>
                <tr>
                    <th scope="col">Plato</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($platos as $plato)
                    <tr>
                        <td data-label="Plato">{{ $plato->nombre }}</td>
                        <td data-label="Categoría">{{ $plato->categoria?->nombre }}</td>
                        <td data-label="Precio">${{ number_format((float) $plato->precio, 2, ',', '.') }}</td>
                        <td data-label="Stock">{{ $plato->stock }}</td>
                        <td data-label="Estado"><span class="status-badge {{ $plato->activo ? 'status-available' : 'status-expired' }}">{{ $plato->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td data-label="Acciones"><div class="table-actions">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.platos.edit', $plato) }}">Editar</a>
                            @if ($plato->activo)
                                <form class="d-inline" method="POST" action="{{ route('admin.platos.destroy', $plato) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Desactivar</button>
                                </form>
                            @endif
                        </div></td>
                    </tr>
                @empty
                    <tr>
                        <td class="table-empty" colspan="6">Todavía no hay platos cargados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $platos->links('site-pagination') }}
@endsection