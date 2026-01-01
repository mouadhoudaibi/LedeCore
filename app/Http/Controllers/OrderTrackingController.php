<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    /**
     * Display the order tracking search form.
     */
    public function show(): View
    {
        return view('orders.track');
    }

    /**
     * Search for order by order number or phone number.
     */
    public function search(Request $request): View
    {
        $request->validate([
            'search' => ['required', 'string'],
        ]);

        $search = $request->input('search');
        $order = null;

        // Try to find by order number first
        $order = Order::where('order_number', $search)
            ->with('orderItems.product')
            ->first();

        // If not found, try to find by phone number
        if (!$order) {
            $order = Order::where('customer_phone', $search)
                ->with('orderItems.product')
                ->latest()
                ->first();
        }

        // Filter out refused orders that are older than 24 hours (hidden from clients)
        if ($order && !$order->isVisibleToClient()) {
            $order = null;
        }

        return view('orders.track', compact('order', 'search'));
    }
}
