<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Plato;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Carrito $carrito): View
    {
        $categorias = Categoria::query()
            ->where('activo', true)
            ->with(['platos' => fn ($query) => $query->where('activo', true)->orderBy('nombre')])
            ->orderBy('nombre')
            ->get();
        $destacados = Plato::query()
            ->where('activo', true)
            ->whereHas('categoria', fn ($query) => $query->where('activo', true))
            ->with('categoria')
            ->orderBy('id')
            ->limit(4)
            ->get();

        return view('home', ['categorias' => $categorias, 'destacados' => $destacados, 'cart' => $carrito->summary()]);
    }
}
