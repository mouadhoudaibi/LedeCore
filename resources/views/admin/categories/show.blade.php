@extends('layouts.admin')

@section('title', $category->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-4xl font-bold text-purple-400">{{ $category->name }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                {{ __('admin.edit') }}
            </a>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">
                {{ __('admin.back') }}
            </a>
        </div>
    </div>
    
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <div class="space-y-4">
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.category_slug') }}</p>
                <p class="text-white font-semibold">{{ $category->slug }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.category_description') }}</p>
                <p class="text-white">{{ $category->description ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">{{ __('admin.category_active') }}</p>
                @if($category->is_active)
                    <span class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-sm">{{ __('admin.active') }}</span>
                @else
                    <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-sm">{{ __('admin.inactive') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
