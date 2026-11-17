@extends('admin.layout')

@section('title', 'Administrar categorías')

@section('content')
    <div class="page-heading-row">
        <h1 class="h2 mb-0">Administrar categorías</h1>
        <a class="btn btn-primary" href="{{ route('admin.categorias.create') }}">Nueva categoría</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle admin-table">
            <thead>
                <tr>
                    <th scope="col">Categoría</th>
                    <th scope="col">Platos asociados</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                    <tr>
                        <td data-label="Categoría">{{ $categoria->nombre }}</td>
                        <td data-label="Platos asociados">{{ $categoria->platos_count }}</td>
                        <td data-label="Estado"><span class="status-badge {{ $categoria->activo ? 'status-available' : 'status-expired' }}">{{ $categoria->activo ? 'Activa' : 'Inactiva' }}</span></td>
                        <td data-label="Acciones"><div class="table-actions">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categorias.edit', $categoria) }}">Editar</a>
                            @if ($categoria->activo)
                                <form class="d-inline" method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Desactivar</button>
                                </form>
                            @endif
                        </div></td>
                    </tr>
                @empty
                    <tr><td class="table-empty" colspan="4">Todavía no hay categorías cargadas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categorias->links('site-pagination') }}
@endsection