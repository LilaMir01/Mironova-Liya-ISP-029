<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn ($item) => $item['qty'] * $item['price']);
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Material $material)
    {
        $data = $request->validate([
            'qty' => 'required|integer|min:1|max:9999',
        ]);
        $qty = (int) $data['qty'];
        $cart = session('cart', []);

        if (isset($cart[$material->id])) {
            $cart[$material->id]['qty'] = min(9999, $cart[$material->id]['qty'] + $qty);
        } else {
            $cart[$material->id] = [
                'id' => $material->id,
                'name' => $material->product_name,
                'price' => (float) $material->price,
                'qty' => $qty,
                'image_path' => $material->image_path,
            ];
        }

        session(['cart' => $cart]);
        return back()->with('success', 'Товар добавлен в корзину.');
    }

    public function update(Request $request, Material $material)
    {
        $data = $request->validate([
            'qty' => 'required|integer|min:1|max:9999',
        ]);
        $cart = session('cart', []);
        if (isset($cart[$material->id])) {
            $cart[$material->id]['qty'] = (int) $data['qty'];
            session(['cart' => $cart]);
        }

        $cart = session('cart', []);
        $grandTotal = (float) collect($cart)->sum(fn ($item) => $item['qty'] * $item['price']);
        $line = $cart[$material->id] ?? null;
        $lineTotal = $line !== null ? (float) ($line['qty'] * $line['price']) : 0.0;

        if ($request->expectsJson()) {
            return response()->json([
                'lineTotal' => $lineTotal,
                'grandTotal' => $grandTotal,
                'qty' => $line !== null ? (int) $line['qty'] : (int) $data['qty'],
            ]);
        }

        return back()->with('success', 'Количество обновлено.');
    }

    public function remove(Material $material)
    {
        $cart = session('cart', []);
        unset($cart[$material->id]);
        session(['cart' => $cart]);
        return back()->with('success', 'Товар удалён из корзины.');
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Корзина пуста.']);
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|digits_between:1,20',
        ]);

        $total = collect($cart)->sum(fn ($item) => $item['qty'] * $item['price']);

        Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'status' => 'new',
            'total' => $total,
            'items' => array_values($cart),
        ]);

        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Заказ успешно оформлен.');
    }
}
