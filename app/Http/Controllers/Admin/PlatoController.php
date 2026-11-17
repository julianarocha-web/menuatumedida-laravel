<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Plato;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlatoController extends Controller
{
    public function index(): View
    {
        $platos = Plato::with('categoria')->orderBy('nombre')->paginate(15);

        return view('admin.platos.index', compact('platos'));
    }

    public function create(): View
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.platos.create', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'gt:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'activo' => ['required', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = 'storage/'.$request->file('imagen')->store('platos', 'public');
        }

        Plato::create($data);

        return redirect()->route('admin.platos.index')->with('success', 'Plato creado.');
    }

    public function edit(Plato $plato): View
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.platos.edit', compact('plato', 'categorias'));
    }

    public function update(Request $request, Plato $plato): RedirectResponse
    {
        $data = $request->validate([
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'gt:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'activo' => ['required', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = 'storage/'.$request->file('imagen')->store('platos', 'public');
        } else {
            unset($data['imagen']);
        }

        $plato->update($data);

        return redirect()->route('admin.platos.index')->with('success', 'Plato actualizado.');
    }

    public function destroy(Plato $plato): RedirectResponse
    {
        $plato->update(['activo' => false]);

        return redirect()->route('admin.platos.index')->with('success', 'Plato desactivado.');
    }
}
