<?php

use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use App\Http\Controllers\Admin\PlatoController;
use App\Http\Controllers\AdminSessionController;
use App\Http\Controllers\BuyerSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureBuyer;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/platos/{plato}', [CatalogoController::class, 'show'])->name('platos.show');
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/{plato}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/{plato}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{plato}', [CartController::class, 'destroy'])->whereNumber('plato')->name('cart.destroy');

Route::get('/ingresar', [BuyerSessionController::class, 'create'])->name('buyer.login');
Route::post('/ingresar', [BuyerSessionController::class, 'store'])->middleware('throttle:5,1')->name('buyer.login.store');
Route::post('/registro', [BuyerSessionController::class, 'register'])->middleware('throttle:5,1')->name('buyer.register');

/** Punto reservado: no procesa notificaciones y conserva CSRF hasta implementar la verificación del proveedor. */
Route::post('/mercadopago/webhook', MercadoPagoWebhookController::class)->name('mercadopago.webhook');

Route::middleware(EnsureBuyer::class)->group(function () {
    Route::post('/pedidos/{order}/pago', [PaymentController::class, 'store'])->whereNumber('order')->name('payments.store');
    Route::get('/pedidos/{order}/pago/resultado', [PaymentController::class, 'result'])->whereNumber('order')->name('payments.result');
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [OrderController::class, 'show'])->whereNumber('order')->name('orders.show');
    Route::delete('/pedidos/{order}', [OrderController::class, 'destroy'])->whereNumber('order')->name('orders.destroy');
    Route::post('/salir', [BuyerSessionController::class, 'destroy'])->name('buyer.logout');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/admin/login', [AdminSessionController::class, 'create'])->name('login');
Route::post('/admin/login', [AdminSessionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('admin.login.store');

Route::prefix('admin')->name('admin.')->middleware(EnsureAdmin::class)->group(function () {
    Route::post('/logout', [AdminSessionController::class, 'destroy'])->name('logout');
    Route::resource('platos', PlatoController::class)->except(['show']);
    Route::resource('categorias', AdminCategoriaController::class)->except(['show']);
});
