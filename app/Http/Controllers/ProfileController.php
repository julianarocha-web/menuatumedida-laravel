<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile', ['buyer' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($request->user()->id)],
            'telefono' => ['required', 'string', 'max:30', 'regex:/^[0-9+() .-]{6,30}$/'],
            'modalidad_entrega' => ['required', 'in:pickup,delivery'],
            'es_admin' => ['prohibited'],
        ]);

        $request->user()->update($validated);

        return redirect()->route('profile.edit')->with('status', 'Perfil actualizado.');
    }
}
