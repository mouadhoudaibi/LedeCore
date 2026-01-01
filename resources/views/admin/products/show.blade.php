@extends('layouts.admin')

@section('title', $product->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-4xl font-bold text-purple-400">{{ $product->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                {{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
                {{ __('admin.back') }}
            </a>
        </div>
    </div>
    
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        @if($product->image)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full max-w-md rounded-lg border border-gray-600">
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.product_category') }}</p>
                <p class="text-white font-semibold">{{ $product->category->name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.product_sku') }}</p>
                <p class="text-white font-semibold">{{ $product->sku }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.product_price') }}</p>
                <p class="text-purple-400 font-bold text-xl">{{ number_format($product->price, 2) }} MAD</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.product_stock') }}</p>
                <p class="text-white font-semibold">{{ $product->stock_quantity }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.product_slug') }}</p>
                <p class="text-white font-semibold">{{ $product->slug }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.product_active') }}</p>
                @if($product->is_active)
                    <span class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-sm">{{ __('admin.active') }}</span>
                @else
                    <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-sm">{{ __('admin.inactive') }}</span>
                @endif
            </div>
        </div>
        
        @if($product->description)
            <div class="mt-6 pt-6 border-t border-gray-700">
                <p class="text-gray-400 text-sm mb-2">{{ __('admin.product_description') }}</p>
                <p class="text-white">{{ $product->description }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
