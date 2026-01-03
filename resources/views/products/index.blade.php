@extends('layouts.app')

@section('title', __('common.products'))

@section('content')
<div class="mb-10">
    <h1 class="text-4xl md:text-5xl font-bold text-purple-400 mb-3">{{ __('common.shop') }}</h1>
    <p class="text-gray-400 text-lg">{{ __('common.categories') }}</p>
</div>

<!-- Search Form -->
<div class="mb-6">
    <form action="{{ route('products.index') }}" method="GET" class="max-w-md">
        <div class="flex gap-2">
            <input type="text" 
                   name="search" 
                   value="{{ request()->search }}" 
                   placeholder="{{ __('common.search_products') }}"
                   class="flex-1 px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition">
            @if(request()->has('category'))
                <input type="hidden" name="category" value="{{ request()->category }}">
            @endif
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-lg transition font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span>{{ __('common.search') }}</span>
            </button>
            @if(request()->has('search') && request()->search)
                <a href="{{ route('products.index', request()->except('search')) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2.5 rounded-lg transition font-medium flex items-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Categories Filter -->
<div class="mb-10 flex flex-wrap gap-3">
    <a href="{{ route('products.index', request()->except('category')) }}" class="px-4 py-2 rounded-lg {{ !request()->has('category') ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }} transition">
        {{ __('common.all_categories') }}
    </a>
    @foreach($categories as $category)
        <a href="{{ route('products.index', array_merge(request()->except('page'), ['category' => $category->id])) }}" class="px-4 py-2 rounded-lg {{ request()->category == $category->id ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }} transition">
            {{ $category->name }}
        </a>
    @endforeach
</div>

<!-- Products Grid -->
@if($products->count() > 0)
    @if(request()->has('search') && request()->search)
        <div class="mb-6">
            <p class="text-gray-400">
                {{ __('common.search_results', ['count' => $products->total(), 'query' => request()->search]) }}
            </p>
        </div>
    @endif
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($products as $product)
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700 hover:border-purple-600 transition-all duration-200 flex flex-col">
                <!-- Product Image -->
                <a href="{{ route('products.show', $product->slug) }}" class="block bg-gray-900 aspect-square flex items-center justify-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    @else
                        <svg class="w-24 h-24 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    @endif
                </a>

                <!-- Product Info -->
                <div class="p-5 flex-grow flex flex-col">
                    <h3 class="text-lg font-semibold text-white mb-3 line-clamp-2 min-h-[3rem]">
                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-purple-400 transition">
                            {{ $product->name }}
                        </a>
                    </h3>
                    
                    <div class="mb-3">
                        @if($product->hasPromoPrice())
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-gray-500 line-through text-sm">
                                    {{ number_format($product->price, 2) }} MAD
                                </span>
                                <span class="text-purple-400 text-xl font-bold">
                                    {{ number_format($product->promo_price, 2) }} MAD
                                </span>
                                <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            </div>
                        @else
                            <p class="text-purple-400 text-xl font-bold">
                                {{ number_format($product->price, 2) }} MAD
                            </p>
                        @endif
                    </div>

                    @if($product->description)
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2 flex-grow leading-relaxed">
                            {{ strlen($product->description) > 80 ? substr($product->description, 0, 80) . '...' : $product->description }}
                        </p>
                    @endif

                    <!-- Actions -->
                    <div class="mt-auto space-y-3 pt-2">
                        @if($product->is_active && $product->stock_quantity > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-20 px-3 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white text-center focus:outline-none focus:border-purple-500 transition">
                                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2.5 px-4 rounded-lg transition flex items-center justify-center space-x-2 font-medium shadow-lg shadow-purple-600/20 hover:shadow-purple-600/30">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span>{{ __('common.add_to_cart') }}</span>
                                    </button>
                                </div>
                            </form>
                            <a href="{{ route('products.show', $product->slug) }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white text-center py-2.5 rounded-lg transition font-medium">
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

    <!-- Pagination -->
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@else
    <div class="text-center py-12">
        <p class="text-gray-400 text-lg">{{ __('admin.no_items') }}</p>
    </div>
@endif
@endsection
