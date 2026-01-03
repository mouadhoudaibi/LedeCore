<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders (Admin).
     */
    public function index(Request $request): View
    {
        // Get selected date from request, default to today
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        // Filter orders by selected date
        $orders = Order::with('orderItems')
            ->whereDate('created_at', $selectedDate)
            ->latest()
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d');
            });

        return view('admin.orders.index', compact('orders', 'selectedDate'));
    }

    /**
     * Display the specified order (Admin).
     */
    public function show(Order $order): View
    {
        $order->load('orderItems.product');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status (Admin).
     */
    public function updateStatus(Order $order, string $status): RedirectResponse
    {
        if (!in_array($status, ['validated', 'refused', 'delivered'])) {
            return redirect()->route('admin.orders.index')
                ->with('error', __('admin.invalid_status'));
        }

        // Only validated orders can be marked as delivered
        if ($status === 'delivered' && $order->status !== 'validated') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', __('admin.can_only_deliver_validated'));
        }

        return DB::transaction(function () use ($order, $status) {
            // If validating order, decrease stock quantities
            if ($status === 'validated' && $order->status === 'pending') {
                $order->load('orderItems.product');
                
                foreach ($order->orderItems as $orderItem) {
                    $product = $orderItem->product;
                    $newStock = max(0, $product->stock_quantity - $orderItem->quantity);
                    $product->update(['stock_quantity' => $newStock]);
                }
            }

            // Prepare update data
            $updateData = ['status' => $status];
            
            // If marking as delivered, set delivered_at timestamp
            if ($status === 'delivered') {
                $updateData['delivered_at'] = now();
            }

            $order->update($updateData);

            $message = match($status) {
                'validated' => __('admin.order_validated'),
                'refused' => __('admin.order_refused'),
                'delivered' => __('admin.order_delivered'),
                default => __('admin.status_updated'),
            };

            return redirect()->route('admin.orders.show', $order)
                ->with('success', $message);
        });
    }
    /**
     * Store a newly created order.
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            // Generate unique order number
            $orderNumber = 'ORD-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');

            // Calculate total amount and create order items
            $totalAmount = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $unitPrice = $product->effective_price;
                $quantity = $item['quantity'];
                $itemTotal = $unitPrice * $quantity;

                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal,
                ];
            }

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'shipping_address' => $validated['shipping_address'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            // Load relationships for response
            $order->load('orderItems.product');

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        });
    }
}
