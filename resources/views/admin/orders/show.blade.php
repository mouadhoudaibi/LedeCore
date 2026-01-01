@extends('layouts.admin')

@section('title', __('admin.order_details'))

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-4xl font-bold text-purple-400">{{ __('admin.order_details') }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
            {{ __('admin.back') }}
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <h2 class="text-2xl font-bold text-white mb-6">{{ __('admin.order_information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">{{ __('admin.order_number') }}</p>
                        <p class="text-white font-semibold">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">{{ __('admin.order_date') }}</p>
                        <p class="text-white font-semibold">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">{{ __('admin.order_status') }}</p>
                        @if($order->status === 'pending')
                            <span class="inline-block bg-yellow-600 text-white px-3 py-1 rounded-full text-sm">{{ __('common.pending') }}</span>
                        @elseif($order->status === 'validated')
                            <span class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-sm">{{ __('common.validated') }}</span>
                        @else
                            <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-sm">{{ __('common.refused') }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">{{ __('common.order_total') }}</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-400">{{ __('checkout.subtotal') }}:</span>
                                <span class="text-purple-400 font-semibold">{{ number_format($order->total_amount, 2) }} MAD</span>
                            </div>
                            @if($order->delivery_fee > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">{{ __('checkout.delivery_fee') }}:</span>
                                    <span class="text-purple-400 font-semibold">{{ number_format($order->delivery_fee, 2) }} MAD</span>
                                </div>
                            @endif
                            <div class="flex justify-between pt-2 border-t border-gray-700">
                                <span class="text-white font-bold">{{ __('checkout.total') }}:</span>
                                <span class="text-purple-400 font-bold text-xl">{{ number_format($order->total_amount + $order->delivery_fee, 2) }} MAD</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <h2 class="text-2xl font-bold text-white mb-6">{{ __('admin.customer_information') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">{{ __('common.customer_name') }}</p>
                        <p class="text-white font-semibold">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">{{ __('common.customer_email') }}</p>
                        <p class="text-white font-semibold">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm mb-1">{{ __('common.customer_phone') }}</p>
                        <p class="text-white font-semibold">{{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-400 text-sm mb-1">{{ __('checkout.shipping_address') }}</p>
                        <p class="text-white font-semibold">{{ $order->shipping_address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
                <h2 class="text-2xl font-bold text-white mb-6">{{ __('admin.order_items') }}</h2>
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
        </div>
        
        <!-- Actions Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 sticky top-4 space-y-4">
                <h3 class="text-xl font-bold text-white mb-4">{{ __('admin.actions') }}</h3>
                
                @if($order->customer_phone && $order->getWhatsAppUrl())
                    <a href="{{ $order->getWhatsAppUrl() }}" target="_blank" class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg transition font-semibold flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <span>WhatsApp</span>
                    </a>
                @endif
                
                @if($order->status === 'pending')
                    <div class="space-y-3">
                        <form action="{{ route('admin.orders.updateStatus', [$order, 'validated']) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg transition font-semibold flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ __('admin.validate_order') }}</span>
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.orders.updateStatus', [$order, 'refused']) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-lg transition font-semibold flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>{{ __('admin.refuse_order') }}</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
