<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Plato;
use Illuminate\Contracts\View\View;

class CatalogoController extends Controller
{
    public function show(Plato $plato, Carrito $carrito): View
    {
        $plato->load('categoria');

        abort_unless($plato->activo && $plato->categoria?->activo, 404);

        return view('platos.show', ['plato' => $plato, 'cart' => $carrito->summary()]);
    }
}
