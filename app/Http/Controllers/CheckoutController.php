<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function show(): View|RedirectResponse
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('checkout.cart_empty'));
        }

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

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', __('checkout.cart_empty'));
        }

        // Get delivery fees
        $casablancaFee = (float) Setting::get('delivery_fee_casablanca', '30.00');
        $outsideFee = (float) Setting::get('delivery_fee_outside', '50.00');

        return view('checkout.show', compact('cartItems', 'total', 'casablancaFee', 'outsideFee'));
    }

    /**
     * Process the checkout and create order.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('checkout.cart_empty'));
        }

        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $cart) {
            // Generate unique order number
            $orderNumber = 'ORD-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');

            // Calculate total amount and create order items
            $totalAmount = 0;
            $orderItems = [];

            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                if (!$product || !$product->is_active) {
                    continue;
                }

                $unitPrice = $product->effective_price;
                $itemTotal = $unitPrice * $quantity;

                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal,
                ];
            }

            if (empty($orderItems)) {
                return redirect()->route('cart.index')->with('error', __('checkout.cart_empty'));
            }

            // Calculate delivery fee based on city
            $city = strtolower(trim($validated['city']));
            $isCasablanca = in_array($city, ['casablanca', 'casa', 'الدار البيضاء']);
            $deliveryFee = $isCasablanca 
                ? (float) Setting::get('delivery_fee_casablanca', '30.00')
                : (float) Setting::get('delivery_fee_outside', '50.00');

            // Combine city and address for shipping_address
            $shippingAddress = $validated['city'] . ', ' . $validated['address'];

            // Create order (total_amount excludes delivery fee, delivery_fee is separate)
            $order = Order::create([
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $totalAmount, // Product total only
                'delivery_fee' => $deliveryFee,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $shippingAddress,
                'notes' => __('checkout.payment_method_cod'),
            ]);

            // Create order items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            // Clear cart
            Session::forget('cart');

            // Load relationships for response
            $order->load('orderItems.product');

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', __('checkout.order_created'));
        });
    }

    /**
     * Display order confirmation page.
     */
    public function success(string $orderNumber): View
    {
        $order = Order::with('orderItems.product')->where('order_number', $orderNumber)->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
