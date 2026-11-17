@extends('admin.layout')

@section('title', 'Nueva categoría')

@section('content')
    <h1 class="h2 mb-4">Nueva categoría</h1>
    <form method="POST" action="{{ route('admin.categorias.store') }}">
        @csrf
        @include('admin.categorias.form', ['categoria' => null])
    </form>
@endsection