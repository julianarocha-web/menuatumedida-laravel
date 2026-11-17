@extends('admin.layout')

@section('title', 'Nuevo plato')

@section('content')
    <h1 class="h2 mb-4">Nuevo plato</h1>
    <form method="POST" action="{{ route('admin.platos.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.platos.form', ['plato' => null])
    </form>
@endsection