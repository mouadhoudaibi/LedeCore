@extends('layouts.app')

@section('title', __('checkout.checkout_title'))

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-400 mb-8">{{ __('checkout.checkout_title') }}</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 sticky top-4">
                <h2 class="text-2xl font-bold text-white mb-6">{{ __('checkout.order_summary') }}</h2>
                
                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $item)
                        <div class="flex items-center justify-between pb-4 border-b border-gray-700">
                            <div class="flex-1">
                                <p class="text-white font-medium">{{ $item['product']->name }}</p>
                                <p class="text-gray-400 text-sm">{{ __('common.quantity') }}: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="text-purple-400 font-semibold">{{ number_format($item['total'], 2) }} MAD</p>
                        </div>
                    @endforeach
                </div>
                
                <div class="pt-4 border-t border-gray-700 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-300">{{ __('checkout.subtotal') }}</span>
                        <span class="text-purple-400 font-semibold">{{ number_format($total, 2) }} MAD</span>
                    </div>
                    <div class="flex justify-between items-center" id="deliveryFeeRow" style="display: none;">
                        <span class="text-gray-300">{{ __('checkout.delivery_fee') }}</span>
                        <span class="text-purple-400 font-semibold" id="deliveryFeeAmount">0.00 MAD</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-700">
                        <span class="text-xl font-semibold text-white">{{ __('checkout.total') }}</span>
                        <span class="text-2xl font-bold text-purple-400" id="totalAmount">{{ number_format($total, 2) }} MAD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 md:p-8">
                <h2 class="text-2xl font-bold text-white mb-8">{{ __('checkout.shipping_information') }}</h2>
                
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-300 mb-2 font-medium">{{ __('checkout.full_name') }} *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition placeholder-gray-500"
                                placeholder="{{ __('checkout.full_name') }}">
                            @error('customer_name')
                                <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 mb-2 font-medium">{{ __('checkout.email') }} *</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" required
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition placeholder-gray-500"
                                placeholder="email@example.com">
                            @error('customer_email')
                                <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('checkout.phone_number') }} *</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition placeholder-gray-500"
                            placeholder="+212 6XX XXX XXX">
                        @error('customer_phone')
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-300 mb-2 font-medium">{{ __('checkout.city') }} *</label>
                            <select name="city" id="citySelect" required
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition"
                                onchange="updateDeliveryFee()">
                                <option value="">{{ __('checkout.select_city') }}</option>
                                <option value="Casablanca" {{ old('city') === 'Casablanca' ? 'selected' : '' }}>Casablanca</option>
                                <option value="Other" {{ old('city') === 'Other' ? 'selected' : '' }}>{{ __('checkout.other_city') }}</option>
                            </select>
                            @error('city')
                                <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 mb-2 font-medium">{{ __('checkout.address') }} *</label>
                            <textarea name="address" required rows="4"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition placeholder-gray-500 resize-none"
                                placeholder="{{ __('checkout.address') }}">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="bg-gray-900 rounded-lg p-5 border border-gray-700">
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('checkout.payment_method') }}</label>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="text-purple-400 font-semibold">{{ __('checkout.payment_method_cod') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-700">
                        <a href="{{ route('cart.index') }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition text-center font-medium">
                            {{ __('admin.back') }}
                        </a>
                        <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg transition font-semibold shadow-lg shadow-purple-600/30 hover:shadow-purple-600/40 text-lg">
                            {{ __('checkout.place_order') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const casablancaFee = {{ $casablancaFee }};
    const outsideFee = {{ $outsideFee }};
    const subtotal = {{ $total }};

    function updateDeliveryFee() {
        const citySelect = document.getElementById('citySelect');
        const deliveryFeeRow = document.getElementById('deliveryFeeRow');
        const deliveryFeeAmount = document.getElementById('deliveryFeeAmount');
        const totalAmount = document.getElementById('totalAmount');
        
        const selectedCity = citySelect.value;
        let fee = 0;
        
        if (selectedCity === 'Casablanca') {
            fee = casablancaFee;
        } else if (selectedCity === 'Other') {
            fee = outsideFee;
        }
        
        if (fee > 0) {
            deliveryFeeRow.style.display = 'flex';
            deliveryFeeAmount.textContent = fee.toFixed(2) + ' MAD';
            const total = subtotal + fee;
            totalAmount.textContent = total.toFixed(2) + ' MAD';
        } else {
            deliveryFeeRow.style.display = 'none';
            totalAmount.textContent = subtotal.toFixed(2) + ' MAD';
        }
    }

    // Initialize on page load if city is already selected
    @if(old('city'))
        updateDeliveryFee();
    @endif
</script>
@endsection
