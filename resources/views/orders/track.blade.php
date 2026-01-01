@extends('layouts.app')

@section('title', __('common.track_order_title'))

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-400 mb-8">{{ __('common.track_order_title') }}</h1>
    
    <!-- Search Form -->
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-8">
        <form action="{{ route('orders.search') }}" method="POST" class="flex gap-4">
            @csrf
            <div class="flex-1">
                <input type="text" name="search" value="{{ old('search', $search ?? '') }}" 
                    placeholder="{{ __('common.order_number_or_phone') }}" required
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                @error('search')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg transition font-semibold flex items-center space-x-2">
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
                    @elseif($order->status === 'refused')
                        {{ __('common.status_refused_message') }}
                    @endif
                </p>

                @if($order->status === 'refused')
                    <!-- Countdown Timer -->
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
                                <td colspan="3" class="px-4 py-3 text-right text-white font-semibold text-lg">{{ __('common.order_total') }}</td>
                                <td class="px-4 py-3 text-purple-400 font-bold text-xl">{{ number_format($order->total_amount, 2) }} MAD</td>
                            </tr>
                        </tfoot>
                    </table>
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

@if(isset($order) && $order->status === 'refused')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderCard = document.getElementById('orderCard');
    const countdownTimer = document.getElementById('countdownTimer');
    const countdownContainer = document.getElementById('countdownContainer');
    
    if (!orderCard || !countdownTimer) return;
    
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
});
</script>
@endif
@endsection
