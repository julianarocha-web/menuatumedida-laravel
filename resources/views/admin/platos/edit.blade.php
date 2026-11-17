@extends('admin.layout')

@section('title', 'Editar plato')

@section('content')
    <h1 class="h2 mb-4">Editar plato</h1>
    <form method="POST" action="{{ route('admin.platos.update', $plato) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.platos.form', ['plato' => $plato])
    </form>
@endsection