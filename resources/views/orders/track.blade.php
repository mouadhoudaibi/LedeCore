@extends('layouts.app')

@section('title', __('common.track_order_title'))

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-400 mb-8">{{ __('common.track_order_title') }}</h1>
    
    <!-- Search Form -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-8 max-w-2xl mx-auto">
        <form action="{{ route('orders.search') }}" method="POST" class="flex flex-col md:flex-row gap-4">
            @csrf
            <div class="w-full md:flex-[0_0_65%]">
                <input type="text" name="search" value="{{ old('search', $search ?? '') }}" 
                    placeholder="{{ __('common.order_number_or_phone') }}" required
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                @error('search')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full md:flex-[0_0_30%] md:w-auto bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg transition font-semibold flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>{{ __('common.search_order') }}</span>
            </button>
        </form>
    </div>
    
    @if(isset($order) && $order)
        <div id="orderCard" class="bg-gray-800 rounded-lg border border-gray-700 p-6" 
             @if($order->status === 'refused')
             data-refused-at="{{ $order->updated_at->timestamp }}"
             @elseif($order->status === 'delivered' && $order->delivered_at)
             data-delivered-at="{{ $order->delivered_at->timestamp }}"
             @endif>
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">{{ __('common.order_found') }}</h2>
            </div>
            
            <!-- Order Status Badge -->
            <div class="mb-6">
                @if($order->status === 'pending')
                    <span class="inline-block bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold">
                        {{ __('common.pending') }}
                    </span>
                @elseif($order->status === 'validated')
                    <span class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
                        {{ __('common.validated') }}
                    </span>
                @elseif($order->status === 'delivered')
                    <span class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('common.delivered') }}
                    </span>
                @elseif($order->status === 'refused')
                    <span class="inline-block bg-red-600 text-white px-4 py-2 rounded-lg font-semibold">
                        {{ __('common.refused') }}
                    </span>
                @endif
                
                <p class="text-gray-400 mt-2">
                    @if($order->status === 'pending')
                        {{ __('common.status_pending_message') }}
                    @elseif($order->status === 'validated')
                        {{ __('common.status_validated_message') }}
                    @elseif($order->status === 'delivered')
                        {{ __('common.status_delivered_message') }}
                    @elseif($order->status === 'refused')
                        {{ __('common.status_refused_message') }}
                    @endif
                </p>

                @if($order->status === 'refused')
                    <!-- Countdown Timer for Refused Orders -->
                    <div id="countdownContainer" class="mt-4 bg-red-900/30 border border-red-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-red-300 text-sm font-medium">{{ __('common.order_will_disappear') }}</p>
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div id="countdownTimer" class="text-2xl font-bold text-red-400 font-mono">
                            {{ __('common.calculating') }}...
                        </div>
                        <p class="text-red-300/70 text-xs mt-2">{{ __('common.countdown_description') }}</p>
                    </div>
                @elseif($order->status === 'delivered' && $order->delivered_at)
                    <!-- Countdown Timer for Delivered Orders -->
                    <div id="deliveredCountdownContainer" class="mt-4 bg-blue-900/30 border border-blue-600 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-blue-300 text-sm font-medium">{{ __('common.order_will_disappear') }}</p>
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div id="deliveredCountdownTimer" class="text-2xl font-bold text-blue-400 font-mono">
                            {{ __('common.calculating') }}...
                        </div>
                        <p class="text-blue-300/70 text-xs mt-2">{{ __('common.countdown_description') }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-gray-400 text-sm mb-1">{{ __('common.order_number') }}</p>
                    <p class="text-white font-semibold">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">{{ __('common.order_date') }}</p>
                    <p class="text-white font-semibold">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="border-t border-gray-700 pt-6 mb-6">
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
                    @if($order->customer_phone)
                        <div>
                            <p class="text-gray-400">{{ __('common.customer_phone') }}</p>
                            <p class="text-white">{{ $order->customer_phone }}</p>
                        </div>
                    @endif
                    @if($order->shipping_address)
                        <div class="md:col-span-2">
                            <p class="text-gray-400">{{ __('common.shipping_address') }}</p>
                            <p class="text-white">{{ $order->shipping_address }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="border-t border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('common.order_items') }}</h3>
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
                                @php
                                    $hasPromo = $item->unit_price < $item->product->price;
                                    $discountPercent = $hasPromo ? round((($item->product->price - $item->unit_price) / $item->product->price) * 100) : 0;
                                @endphp
                                <tr class="border-b border-gray-700">
                                    <td class="px-4 py-3 text-white">{{ $item->product->name }}</td>
                                    <td class="px-4 py-3">
                                        @if($hasPromo)
                                            <div class="flex flex-col">
                                                <span class="text-gray-500 line-through text-sm">{{ number_format($item->product->price, 2) }} MAD</span>
                                                <span class="text-purple-400 font-semibold">{{ number_format($item->unit_price, 2) }} MAD</span>
                                                <span class="text-green-400 text-xs font-medium">-{{ $discountPercent }}%</span>
                                            </div>
                                        @else
                                            <span class="text-purple-400 font-semibold">{{ number_format($item->unit_price, 2) }} MAD</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-300">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-purple-400 font-semibold">{{ number_format($item->total_price, 2) }} MAD</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-900">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-gray-300">{{ __('checkout.subtotal') }}:</td>
                                <td class="px-4 py-3 text-purple-400 font-semibold">{{ number_format($order->total_amount, 2) }} MAD</td>
                            </tr>
                            @if($order->delivery_fee > 0)
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right text-gray-300">{{ __('checkout.delivery_fee') }}:</td>
                                    <td class="px-4 py-3 text-purple-400 font-semibold">{{ number_format($order->delivery_fee, 2) }} MAD</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-white font-semibold text-lg">{{ __('checkout.total') }}:</td>
                                <td class="px-4 py-3 text-purple-400 font-bold text-xl">{{ number_format($order->total_amount + $order->delivery_fee, 2) }} MAD</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Receipt Section -->
            <div class="border-t border-gray-700 pt-6 mt-6">
                <h3 class="text-lg font-semibold text-white mb-4">{{ __('checkout.receipt') }}</h3>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('orders.receipt.view', $order->order_number) }}" target="_blank" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition font-semibold flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>{{ __('checkout.view_receipt') }}</span>
                    </a>
                    <a href="{{ route('orders.receipt.download', $order->order_number) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition font-semibold flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>{{ __('checkout.download_receipt') }}</span>
                    </a>
                </div>
            </div>
        </div>
    @elseif(isset($search) && $search)
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-12 text-center">
            <svg class="w-24 h-24 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-400 text-lg">{{ __('common.order_not_found_message') }}</p>
        </div>
    @endif
    
    <div class="mt-8 text-center">
        <a href="{{ route('products.index') }}" class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
            {{ __('common.continue_shopping') }}
        </a>
    </div>
</div>

@if(isset($order) && ($order->status === 'refused' || ($order->status === 'delivered' && $order->delivered_at)))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderCard = document.getElementById('orderCard');
    
    @if($order->status === 'refused')
    // Countdown for refused orders
    const countdownTimer = document.getElementById('countdownTimer');
    const countdownContainer = document.getElementById('countdownContainer');
    
    if (orderCard && countdownTimer) {
        const refusedAt = parseInt(orderCard.getAttribute('data-refused-at'));
        const refusedDate = new Date(refusedAt * 1000);
        const expirationTime = refusedDate.getTime() + (24 * 60 * 60 * 1000); // 24 hours in milliseconds
        
        function updateCountdown() {
            const now = new Date().getTime();
            const timeRemaining = expirationTime - now;
            
            if (timeRemaining <= 0) {
                // Timer reached zero - hide the order
                countdownTimer.textContent = '00:00:00';
                orderCard.style.transition = 'opacity 0.5s ease-out';
                orderCard.style.opacity = '0';
                
                setTimeout(() => {
                    orderCard.style.display = 'none';
                    // Show message that order has been removed
                const messageDiv = document.createElement('div');
                messageDiv.className = 'bg-gray-800 rounded-lg border border-gray-700 p-12 text-center';
                messageDiv.innerHTML = `
                    <svg class="w-24 h-24 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-400 text-lg">{{ __('common.order_no_longer_visible') }}</p>
                `;
                orderCard.parentNode.insertBefore(messageDiv, orderCard);
            }, 500);
            
            return;
        }
        
        // Calculate hours, minutes, seconds
        const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
        const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
        
        // Format with leading zeros
        const formattedTime = 
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');
        
        countdownTimer.textContent = formattedTime;
        
        // Add visual warning when less than 1 hour remaining
        if (hours === 0 && minutes < 60) {
            countdownContainer.classList.add('animate-pulse');
            countdownTimer.classList.add('text-red-500');
        } else {
            countdownContainer.classList.remove('animate-pulse');
            countdownTimer.classList.remove('text-red-500');
        }
    }
    
    // Update countdown immediately
    updateCountdown();
    
    // Update every second
    let countdownInterval = setInterval(updateCountdown, 1000);
    
        // Clean up and restart interval when page visibility changes
        document.addEventListener('visibilitychange', function() {
            clearInterval(countdownInterval);
            if (!document.hidden) {
                // Restart interval when page becomes visible again
                updateCountdown(); // Update immediately
                countdownInterval = setInterval(updateCountdown, 1000);
            }
        });
    }
    @endif
    
    @if($order->status === 'delivered' && $order->delivered_at)
    // Countdown for delivered orders
    const deliveredCountdownTimer = document.getElementById('deliveredCountdownTimer');
    const deliveredCountdownContainer = document.getElementById('deliveredCountdownContainer');
    
    if (orderCard && deliveredCountdownTimer) {
        const deliveredAt = parseInt(orderCard.getAttribute('data-delivered-at'));
        const deliveredDate = new Date(deliveredAt * 1000);
        const expirationTime = deliveredDate.getTime() + (24 * 60 * 60 * 1000); // 24 hours in milliseconds
        
        function updateDeliveredCountdown() {
            const now = new Date().getTime();
            const timeRemaining = expirationTime - now;
            
            if (timeRemaining <= 0) {
                // Timer reached zero - hide the order
                deliveredCountdownTimer.textContent = '00:00:00';
                orderCard.style.transition = 'opacity 0.5s ease-out';
                orderCard.style.opacity = '0';
                
                setTimeout(() => {
                    orderCard.style.display = 'none';
                    // Show message that order has been removed
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'bg-gray-800 rounded-lg border border-gray-700 p-12 text-center';
                    messageDiv.innerHTML = `
                        <svg class="w-24 h-24 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-400 text-lg">{{ __('common.order_no_longer_visible') }}</p>
                    `;
                    orderCard.parentNode.insertBefore(messageDiv, orderCard);
                }, 500);
                
                return;
            }
            
            // Calculate hours, minutes, seconds
            const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
            
            // Format with leading zeros
            const formattedTime = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
            
            deliveredCountdownTimer.textContent = formattedTime;
            
            // Add visual warning when less than 1 hour remaining
            if (hours === 0 && minutes < 60) {
                deliveredCountdownContainer.classList.add('animate-pulse');
                deliveredCountdownTimer.classList.add('text-blue-500');
            } else {
                deliveredCountdownContainer.classList.remove('animate-pulse');
                deliveredCountdownTimer.classList.remove('text-blue-500');
            }
        }
        
        // Update countdown immediately
        updateDeliveredCountdown();
        
        // Update every second
        let deliveredCountdownInterval = setInterval(updateDeliveredCountdown, 1000);
        
        // Clean up and restart interval when page visibility changes
        document.addEventListener('visibilitychange', function() {
            clearInterval(deliveredCountdownInterval);
            if (!document.hidden) {
                // Restart interval when page becomes visible again
                updateDeliveredCountdown(); // Update immediately
                deliveredCountdownInterval = setInterval(updateDeliveredCountdown, 1000);
            }
        });
    }
    @endif
});
</script>
@endif
@endsection
