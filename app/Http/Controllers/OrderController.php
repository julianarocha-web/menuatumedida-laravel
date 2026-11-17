<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        return view('orders', ['orders' => $request->user()->orders()->orderByDesc('id')->paginate(10)]);
    }

    public function show(Request $request, int $order): View
    {
        $order = $request->user()->orders()->with('items')->findOrFail($order);

        return view('order-detail', compact('order'));
    }

    public function destroy(Request $request, int $order): RedirectResponse
    {
        DB::transaction(function () use ($request, $order): void {
            $pending = $request->user()->orders()->lockForUpdate()->findOrFail($order);
            if ($pending->currentStatus() === Order::PendingPayment) {
                $pending->update(['status' => Order::Cancelled]);
            }
        }, 3);

        return redirect()->route('orders.show', $order);
    }
}
