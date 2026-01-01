@extends('layouts.app')

@section('title', __('checkout.order_confirmation'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-600 rounded-full mb-4">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-purple-400 mb-2">{{ __('checkout.thank_you') }}</h1>
        <p class="text-gray-400">{{ __('checkout.order_created') }}</p>
    </div>
    
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
        <h2 class="text-2xl font-bold text-white mb-6">{{ __('checkout.order_confirmation') }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('checkout.order_number') }}</p>
                <p class="text-white font-semibold">{{ $order->order_number }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('checkout.order_date') }}</p>
                <p class="text-white font-semibold">{{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('checkout.order_status') }}</p>
                <p class="text-purple-400 font-semibold">{{ __('common.' . $order->status) }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('checkout.payment_method') }}</p>
                <p class="text-white font-semibold">{{ __('checkout.payment_method_cod') }}</p>
            </div>
        </div>
        
        <div class="border-t border-gray-700 pt-6">
            <h3 class="text-lg font-semibold text-white mb-4">{{ __('common.customer_information') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">{{ __('common.customer_name') }}</p>
                    <p class="text-white">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">{{ __('common.customer_email') }}</p>
                    <p class="text-white">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-gray-400">{{ __('common.customer_phone') }}</p>
                    <p class="text-white">{{ $order->customer_phone }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-400">{{ __('checkout.shipping_address') }}</p>
                    <p class="text-white">{{ $order->shipping_address }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
        <h3 class="text-xl font-bold text-white mb-4">{{ __('common.order_items') }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">{{ __('common.product') }}</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">{{ __('common.price') }}</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">{{ __('common.quantity') }}</th>
                        <th class="px-4 py-3 text-left text-gray-300 font-semibold">{{ __('common.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3 text-white">{{ $item->product->name }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ number_format($item->unit_price, 2) }} MAD</td>
                            <td class="px-4 py-3 text-gray-300">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-purple-400 font-semibold">{{ number_format($item->total_price, 2) }} MAD</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-900">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-300">{{ __('checkout.subtotal') }}</td>
                        <td class="px-4 py-3 text-purple-400 font-semibold">{{ number_format($order->total_amount, 2) }} MAD</td>
                    </tr>
                    @if($order->delivery_fee > 0)
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right text-gray-300">{{ __('checkout.delivery_fee') }}</td>
                            <td class="px-4 py-3 text-purple-400 font-semibold">{{ number_format($order->delivery_fee, 2) }} MAD</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-white font-semibold text-lg">{{ __('checkout.total') }}</td>
                        <td class="px-4 py-3 text-purple-400 font-bold text-xl">{{ number_format($order->total_amount + $order->delivery_fee, 2) }} MAD</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="text-center">
        <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg transition font-semibold">
            {{ __('common.continue_shopping') }}
        </a>
    </div>
</div>
@endsection
