@extends('layouts.app')

@section('title', __('cart.my_cart'))

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-400 mb-8">{{ __('cart.my_cart') }}</h1>
    
    @if(count($cartItems) > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold">{{ __('common.product') }}</th>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold">{{ __('common.price') }}</th>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold">{{ __('common.quantity') }}</th>
                            <th class="px-6 py-4 text-left text-gray-300 font-semibold">{{ __('common.total') }}</th>
                            <th class="px-6 py-4 text-center text-gray-300 font-semibold">{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr class="border-b border-gray-700 hover:bg-gray-750 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        @if($item['product']->image)
                                            <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-16 h-16 object-cover rounded">
                                        @endif
                                        <div>
                                            <a href="{{ route('products.show', $item['product']->slug) }}" class="text-white hover:text-purple-400 transition font-medium">
                                                {{ $item['product']->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item['product']->hasPromoPrice())
                                        <div class="flex flex-col">
                                            <span class="text-gray-500 line-through text-sm">
                                                {{ number_format($item['product']->price, 2) }} MAD
                                            </span>
                                            <span class="text-purple-400 font-semibold">
                                                {{ number_format($item['product']->effective_price, 2) }} MAD
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-purple-400 font-semibold">
                                            {{ number_format($item['product']->price, 2) }} MAD
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock_quantity }}" class="w-20 px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white text-center focus:outline-none focus:border-purple-500 transition">
                                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition font-medium">
                                            {{ __('cart.update') }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-white font-semibold">
                                    {{ number_format($item['total'], 2) }} MAD
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition font-medium">
                                            {{ __('cart.remove') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-900">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right text-gray-300 font-semibold text-lg">
                                {{ __('cart.cart_total') }}
                            </td>
                            <td class="px-6 py-4 text-white font-bold text-xl">
                                {{ number_format($total, 2) }} MAD
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @foreach($cartItems as $item)
                <div class="bg-gray-800 rounded-lg border border-gray-700 p-4">
                    <div class="flex items-start space-x-4 mb-4">
                        @if($item['product']->image)
                            <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="w-20 h-20 object-cover rounded flex-shrink-0">
                        @endif
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('products.show', $item['product']->slug) }}" class="text-white hover:text-purple-400 transition font-medium block mb-2">
                                {{ $item['product']->name }}
                            </a>
                            <p class="text-purple-400 font-semibold mb-1">{{ number_format($item['product']->price, 2) }} MAD</p>
                            <p class="text-gray-400 text-sm">{{ __('common.total') }}: <span class="text-white font-semibold">{{ number_format($item['total'], 2) }} MAD</span></p>
                        </div>
                    </div>
                    <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" class="flex items-center gap-2 mb-3">
                        @csrf
                        @method('PUT')
                        <label class="text-gray-300 text-sm flex-shrink-0">{{ __('common.quantity') }}:</label>
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock_quantity }}" class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white text-center focus:outline-none focus:border-purple-500 transition">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition font-medium text-sm">
                            {{ __('cart.update') }}
                        </button>
                    </form>
                    <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition font-medium text-sm">
                            {{ __('cart.remove') }}
                        </button>
                    </form>
                </div>
            @endforeach
            <div class="bg-gray-900 rounded-lg border border-gray-700 p-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-300">{{ __('cart.cart_total') }}</span>
                    <span class="text-2xl font-bold text-purple-400">{{ number_format($total, 2) }} MAD</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between">
            <form action="{{ route('cart.clear') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition font-medium">
                    {{ __('cart.clear_cart') }}
                </button>
            </form>
            
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                <a href="{{ route('products.index') }}" class="flex-1 sm:flex-none bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition text-center font-medium">
                    {{ __('common.continue_shopping') }}
                </a>
                <a href="{{ route('checkout.show') }}" class="flex-1 sm:flex-none bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition font-semibold text-center shadow-lg shadow-purple-600/30 hover:shadow-purple-600/40">
                    {{ __('common.proceed_to_checkout') }}
                </a>
            </div>
        </div>
    @else
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-12 text-center">
            <svg class="w-24 h-24 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-400 text-lg mb-6">{{ __('common.cart_empty') }}</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition">
                {{ __('common.continue_shopping') }}
            </a>
        </div>
    @endif
</div>
@endsection
