@extends('layouts.admin')

@section('title', __('admin.create_category'))

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-400 mb-8">{{ __('admin.create_category') }}</h1>
    
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-gray-300 mb-2">{{ __('admin.category_name') }} *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">{{ __('admin.category_description') }}</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2">{{ __('admin.category_slug') }}</label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                @error('slug')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                        class="w-5 h-5 text-purple-600 bg-gray-700 border-gray-600 rounded focus:ring-purple-500">
                    <span class="text-gray-300">{{ __('admin.category_active') }}</span>
                </label>
            </div>
            
            <div class="flex gap-4 pt-4">
                <a href="{{ route('admin.categories.index') }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition text-center">
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
