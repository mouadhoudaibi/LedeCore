@extends('layouts.admin')

@section('title', __('admin.edit_product'))

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-400 mb-8">{{ __('admin.edit_product') }}</h1>
    
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-300 mb-2">{{ __('admin.product_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                    @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">{{ __('admin.product_category') }} *</label>
                    <select name="category_id" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                        <option value="">{{ __('admin.product_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">{{ __('admin.product_description') }}</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-gray-300 mb-2">{{ __('admin.product_sku') }} *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                    @error('sku')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">{{ __('admin.product_price') }} *</label>
                    <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                    @error('price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-gray-300 mb-2">{{ __('admin.product_stock') }}</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                    @error('stock_quantity')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">{{ __('admin.product_promo_price') }}</label>
                <input type="number" name="promo_price" step="0.01" value="{{ old('promo_price', $product->promo_price) }}"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition"
                    placeholder="{{ __('admin.product_promo_price_placeholder') }}">
                <p class="text-gray-400 text-sm mt-1">{{ __('admin.product_promo_price_help') }}</p>
                @error('promo_price')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">{{ __('admin.product_slug') }}</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                @error('slug')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">Image</label>
                @if($product->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg border border-gray-600">
                        <p class="text-gray-400 text-sm mt-2">Current image</p>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                @error('image')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                        class="w-5 h-5 text-purple-600 bg-gray-700 border-gray-600 rounded focus:ring-purple-500">
                    <span class="text-gray-300">{{ __('admin.product_active') }}</span>
                </label>
            </div>
            
            <div class="flex gap-4 pt-4">
                <a href="{{ route('admin.products.index') }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition text-center">
                    {{ __('admin.cancel') }}
                </a>
                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg transition font-semibold">
                    {{ __('admin.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
