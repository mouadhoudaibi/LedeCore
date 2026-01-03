@extends('layouts.admin')

@section('title', __('admin.settings'))

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-bold text-purple-400 mb-8">{{ __('admin.settings') }}</h1>
    
    <div class="space-y-6">
        <!-- Delivery Fees Section -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-white mb-6">{{ __('admin.delivery_fees') }}</h2>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('admin.delivery_fee_casablanca') }} *</label>
                        <input type="number" name="delivery_fee_casablanca" step="0.01" value="{{ old('delivery_fee_casablanca', $casablancaFee) }}" required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                        <p class="text-gray-400 text-sm mt-1">{{ __('admin.delivery_fee_casablanca_help') }}</p>
                        @error('delivery_fee_casablanca')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('admin.delivery_fee_outside') }} *</label>
                        <input type="number" name="delivery_fee_outside" step="0.01" value="{{ old('delivery_fee_outside', $outsideFee) }}" required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                        <p class="text-gray-400 text-sm mt-1">{{ __('admin.delivery_fee_outside_help') }}</p>
                        @error('delivery_fee_outside')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
        </div>

        <!-- Social Media Links Section -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-white mb-6">{{ __('admin.social_media_links') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('admin.social_whatsapp') }}</label>
                        <input type="url" name="social_whatsapp" value="{{ old('social_whatsapp', $whatsappUrl) }}"
                            placeholder="https://wa.me/212XXXXXXXXX"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                        <p class="text-gray-400 text-sm mt-1">{{ __('admin.social_whatsapp_help') }}</p>
                        @error('social_whatsapp')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('admin.social_facebook') }}</label>
                        <input type="url" name="social_facebook" value="{{ old('social_facebook', $facebookUrl) }}"
                            placeholder="https://facebook.com/yourpage"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                        <p class="text-gray-400 text-sm mt-1">{{ __('admin.social_facebook_help') }}</p>
                        @error('social_facebook')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('admin.social_instagram') }}</label>
                        <input type="url" name="social_instagram" value="{{ old('social_instagram', $instagramUrl) }}"
                            placeholder="https://instagram.com/yourpage"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                        <p class="text-gray-400 text-sm mt-1">{{ __('admin.social_instagram_help') }}</p>
                        @error('social_instagram')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-300 mb-2 font-medium">{{ __('admin.social_tiktok') }}</label>
                        <input type="url" name="social_tiktok" value="{{ old('social_tiktok', $tiktokUrl) }}"
                            placeholder="https://tiktok.com/@yourpage"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                        <p class="text-gray-400 text-sm mt-1">{{ __('admin.social_tiktok_help') }}</p>
                        @error('social_tiktok')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex gap-4 pt-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 px-6 rounded-lg transition text-center">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg transition font-semibold">
                        {{ __('admin.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
