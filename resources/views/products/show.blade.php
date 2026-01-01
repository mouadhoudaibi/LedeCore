@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-400">
        <a href="{{ route('products.index') }}" class="hover:text-purple-400 transition">{{ __('common.shop') }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-500">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="aspect-square flex items-center justify-center bg-gray-900">
                    <svg class="w-48 h-48 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="bg-gray-800 rounded-lg p-6 md:p-8 border border-gray-700">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-6">{{ $product->name }}</h1>
            
            <div class="mb-8 space-y-3">
                @if($product->hasPromoPrice())
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-gray-500 line-through text-xl md:text-2xl">
                            {{ number_format($product->price, 2) }} MAD
                        </span>
                        <span class="text-purple-400 text-3xl md:text-4xl font-bold">
                            {{ number_format($product->promo_price, 2) }} MAD
                        </span>
                        <span class="bg-red-600 text-white text-sm font-bold px-3 py-1 rounded">
                            -{{ $product->discount_percentage }}%
                        </span>
                    </div>
                @else
                    <p class="text-purple-400 text-3xl md:text-4xl font-bold">
                        {{ number_format($product->price, 2) }} MAD
                    </p>
                @endif
                <div class="flex flex-wrap gap-4 text-sm">
                    <p class="text-gray-400">
                        {{ __('common.categories') }}: <span class="text-purple-400 font-medium">{{ $product->category->name }}</span>
                    </p>
                    <p class="text-gray-400">
                        SKU: <span class="text-gray-300">{{ $product->sku }}</span>
                    </p>
                    @if($product->stock_quantity > 0)
                        <p class="text-gray-400">
                            {{ __('admin.product_stock') }}: <span class="text-green-400 font-medium">{{ $product->stock_quantity }}</span>
                        </p>
                    @endif
                </div>
            </div>

            @if($product->description)
                <div class="mb-8 pb-8 border-b border-gray-700">
                    <h2 class="text-xl font-semibold text-white mb-3">{{ __('admin.product_description') }}</h2>
                    <p class="text-gray-300 leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif

            <!-- Add to Cart -->
            @if($product->is_active && $product->stock_quantity > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex items-center gap-3">
                            <label class="text-gray-300 font-medium">{{ __('common.quantity') }}:</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-24 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white text-center focus:outline-none focus:border-purple-500 transition font-medium">
                        </div>
                        <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg transition flex items-center justify-center space-x-2 font-semibold shadow-lg shadow-purple-600/30 hover:shadow-purple-600/40 text-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>{{ __('common.add_to_cart') }}</span>
                        </button>
                    </div>
                </form>
            @elseif($product->is_active && $product->stock_quantity <= 0)
                <div class="bg-red-600/90 text-white py-4 px-6 rounded-lg text-center mb-6 font-medium">
                    {{ __('cart.out_of_stock') }}
                </div>
            @else
                <div class="bg-red-600/90 text-white py-4 px-6 rounded-lg text-center mb-6 font-medium">
                    {{ __('cart.product_not_available') }}
                </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-gray-700">
                <a href="{{ route('products.index') }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition text-center font-medium">
                    {{ __('common.continue_shopping') }}
                </a>
                <a href="{{ route('cart.index') }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition text-center flex items-center justify-center space-x-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>{{ __('common.view_cart') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
