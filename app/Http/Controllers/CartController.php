<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the cart page.
     * 
     * Cart is stored in Laravel session, which is automatically isolated per browser/device.
     * Each browser session gets a unique session ID cookie, ensuring cart isolation.
     */
    public function index(): View
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::with('category')->find($productId);
            if ($product && $product->is_active) {
                $itemTotal = $product->effective_price * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $itemTotal,
                ];
                $total += $itemTotal;
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if (!$product->is_active) {
            return redirect()->back()->with('error', __('cart.product_not_available'));
        }

        // Check if product is out of stock
        if ($product->stock_quantity <= 0) {
            return redirect()->back()->with('error', __('cart.out_of_stock'));
        }

        $cart = Session::get('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        // Check stock availability
        $currentCartQuantity = $cart[$product->id] ?? 0;
        if ($product->stock_quantity < $currentCartQuantity + $quantity) {
            return redirect()->back()->with('error', __('cart.insufficient_stock'));
        }

        // Add or update quantity
        if (isset($cart[$product->id])) {
            $cart[$product->id] += $quantity;
        } else {
            $cart[$product->id] = $quantity;
        }

        Session::put('cart', $cart);

        return redirect()->back()->with('success', __('cart.product_added'));
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Session::get('cart', []);
        $quantity = (int) $request->input('quantity');

        // Check if product exists in cart
        if (!isset($cart[$product->id])) {
            return redirect()->route('cart.index')->with('error', __('cart.product_not_in_cart'));
        }

        // Check if product is out of stock
        if ($product->stock_quantity <= 0) {
            return redirect()->route('cart.index')->with('error', __('cart.out_of_stock'));
        }

        // Check stock availability
        if ($product->stock_quantity < $quantity) {
            return redirect()->route('cart.index')->with('error', __('cart.insufficient_stock'));
        }

        $cart[$product->id] = $quantity;
        Session::put('cart', $cart);

        return redirect()->route('cart.index')->with('success', __('cart.quantity_updated'));
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Product $product): RedirectResponse
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', __('cart.product_removed'));
    }

    /**
     * Clear the entire cart.
     */
    public function clear(): RedirectResponse
    {
        Session::forget('cart');

        return redirect()->route('cart.index')->with('success', __('cart.cart_cleared'));
    }

    /**
     * Get cart item count (API endpoint).
     */
    public function count(): JsonResponse
    {
        $cart = Session::get('cart', []);
        $count = array_sum($cart);

        return response()->json(['count' => $count]);
    }
}
