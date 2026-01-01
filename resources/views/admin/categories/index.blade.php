@extends('layouts.admin')

@section('title', __('admin.categories'))

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-4xl font-bold text-purple-400">{{ __('admin.categories') }}</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition font-semibold flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span>{{ __('admin.create') }} {{ __('admin.category') }}</span>
    </a>
</div>

<div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-4 text-left text-gray-300 font-semibold">ID</th>
                    <th class="px-6 py-4 text-left text-gray-300 font-semibold">{{ __('admin.category_name') }}</th>
                    <th class="px-6 py-4 text-left text-gray-300 font-semibold">{{ __('admin.category_slug') }}</th>
                    <th class="px-6 py-4 text-left text-gray-300 font-semibold">{{ __('admin.category_active') }}</th>
                    <th class="px-6 py-4 text-center text-gray-300 font-semibold">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="border-b border-gray-700 hover:bg-gray-750 transition">
                        <td class="px-6 py-4 text-gray-300">{{ $category->id }}</td>
                        <td class="px-6 py-4 text-white font-medium">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            @if($category->is_active)
                                <span class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-sm">{{ __('admin.active') }}</span>
                            @else
                                <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-sm">{{ __('admin.inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.categories.show', $category) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded transition text-sm">
                                    View
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition text-sm">
                                    {{ __('admin.edit') }}
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('admin.confirm_delete_category', ['name' => addslashes($category->name)]) }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition text-sm">
                                        {{ __('admin.delete') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">{{ __('admin.no_items') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $categories->links() }}
</div>
@endsection
