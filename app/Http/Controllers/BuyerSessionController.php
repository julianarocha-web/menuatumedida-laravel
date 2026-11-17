<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class BuyerSessionController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return redirect()->route($request->user()->es_admin ? 'admin.platos.index' : 'profile.edit');
        }

        return view('buyer-login');
    }

    public function register(Request $request): RedirectResponse
    {
        abort_if($request->user() !== null, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'telefono' => ['required', 'string', 'max:30', 'regex:/^[0-9+() .-]{6,30}$/'],
            'modalidad_entrega' => ['required', 'in:pickup,delivery'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'terms' => ['accepted'],
            'es_admin' => ['prohibited'],
        ]);

        $buyer = new User;
        $buyer->fill(collect($validated)->except('terms')->all());
        $buyer->es_admin = false;
        $buyer->save();
        Auth::login($buyer);
        $request->session()->regenerate();

        return redirect()->intended(route('profile.edit'))->with('status', 'Tu cuenta está lista. ¡Bienvenido!');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if($request->user() !== null, 403);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials + ['es_admin' => false])) {
            return back()->withErrors(['email' => 'El email o la contraseña no corresponden a una cuenta compradora.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
