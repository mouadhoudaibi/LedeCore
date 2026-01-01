@extends('layouts.app')

@section('title', 'LedeCore - Premium LED & Lighting Solutions')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-gray-900 via-gray-900 to-gray-800 py-20 md:py-32 overflow-hidden">
    <!-- Background Glow Effect -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-600 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500 rounded-full opacity-20 blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Brand Name with Neon Effect -->
            <h1 class="text-6xl md:text-8xl font-bold mb-6 neon-text">
                Lede<span class="text-purple-400">Core</span>
            </h1>
            
            <!-- Tagline -->
            <p class="text-xl md:text-2xl text-gray-300 mb-4">
                {{ __('common.welcome') }}
            </p>
            <p class="text-lg md:text-xl text-gray-400 mb-12 max-w-2xl mx-auto">
                Premium LED & RGB Lighting Solutions for Modern Spaces
            </p>
            
            <!-- Call-to-Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('products.index') }}" class="group relative px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-all duration-300 shadow-lg shadow-purple-500/50 hover:shadow-xl hover:shadow-purple-500/70 hover:scale-105 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span>{{ __('common.shop') }}</span>
                </a>
                
                <a href="{{ route('products.index') }}" class="px-8 py-4 bg-gray-800 hover:bg-gray-700 border-2 border-purple-600 text-purple-400 font-semibold rounded-lg transition-all duration-300 hover:border-purple-500 hover:text-purple-300 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>{{ __('common.products') }}</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Decorative LED Lines -->
    <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-purple-600 to-transparent opacity-50"></div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-800 border-b border-gray-700">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600/20 rounded-full mb-4 border border-purple-600/30">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Premium Quality</h3>
                <p class="text-gray-400">High-end LED products built to last</p>
            </div>
            
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600/20 rounded-full mb-4 border border-purple-600/30">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">RGB Technology</h3>
                <p class="text-gray-400">Full spectrum color customization</p>
            </div>
            
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600/20 rounded-full mb-4 border border-purple-600/30">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Fast Delivery</h3>
                <p class="text-gray-400">Quick and reliable shipping</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="py-16 bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Featured <span class="text-purple-400">Products</span>
            </h2>
            <p class="text-gray-400 text-lg">Discover our latest LED lighting solutions</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
            @foreach($featuredProducts as $product)
                <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-purple-600 transition-all duration-200 flex flex-col group">
                    <!-- Product Image -->
                    <a href="{{ route('products.show', $product->slug) }}" class="block bg-gray-900 aspect-square flex items-center justify-center overflow-hidden relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        @endif
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-purple-600/0 group-hover:bg-purple-600/10 transition-colors duration-300"></div>
                    </a>

                    <!-- Product Info -->
                    <div class="p-5 flex-grow flex flex-col">
                        <h3 class="text-lg font-semibold text-white mb-3 line-clamp-2 min-h-[3rem]">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-purple-400 transition">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-400 text-sm mb-3">{{ $product->category->name }}</p>
                        
                        <p class="text-purple-400 text-xl font-bold mb-4">
                            {{ number_format($product->price, 2) }} MAD
                        </p>

                        <!-- Actions -->
                        <div class="mt-auto">
                            @if($product->is_active && $product->stock_quantity > 0)
                                <a href="{{ route('products.show', $product->slug) }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2.5 rounded-lg transition font-medium shadow-lg shadow-purple-600/20 hover:shadow-purple-600/30">
                                    {{ __('common.view_cart') }}
                                </a>
                            @elseif($product->is_active && $product->stock_quantity <= 0)
                                <div class="bg-red-600/90 text-white text-center py-2.5 rounded-lg font-medium">
                                    {{ __('cart.out_of_stock') }}
                                </div>
                            @else
                                <div class="bg-red-600/90 text-white text-center py-2.5 rounded-lg font-medium">
                                    {{ __('cart.product_not_available') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- View All Products Button -->
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-block px-8 py-4 bg-gray-800 hover:bg-gray-700 border-2 border-purple-600 text-purple-400 font-semibold rounded-lg transition-all duration-300 hover:border-purple-500 hover:text-purple-300">
                {{ __('common.view_all_products') }}
            </a>
        </div>
    </div>
</section>
@endif
@endsection

