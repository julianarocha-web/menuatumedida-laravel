@extends('admin.layout')

@section('title', 'Editar categoría')

@section('content')
    <h1 class="h2 mb-4">Editar categoría</h1>
    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
        @csrf
        @method('PUT')
        @include('admin.categorias.form', ['categoria' => $categoria])
    </form>
@endsection