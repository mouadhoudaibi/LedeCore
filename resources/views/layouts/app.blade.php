<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LedeCore')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">
    <!-- Toast Notifications Container -->
    <div 
        class="fixed top-4 right-4 z-50 space-y-3" 
        x-data="{
            toasts: [],
            addToast(type, message) {
                const id = Date.now() + Math.random();
                this.toasts.push({ id, type, message });
                setTimeout(() => {
                    this.removeToast(id);
                }, 5000);
            },
            removeToast(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
        }"
        x-init="
            @if(session('success'))
                addToast('success', {{ json_encode(session('success')) }});
            @endif
            @if(session('error'))
                addToast('error', {{ json_encode(session('error')) }});
            @endif
        "
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                x-show="toast"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
                :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
                class="text-white px-4 py-3 md:px-5 md:py-4 rounded-lg shadow-xl flex items-center space-x-3 min-w-[280px] md:min-w-[300px] max-w-[calc(100vw-2rem)] md:max-w-md"
            >
                <div class="flex-shrink-0">
                    <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="flex-1 font-medium text-sm" x-text="toast.message"></p>
                <button 
                    @click="removeToast(toast.id)"
                    class="flex-shrink-0 text-white hover:text-gray-200 transition"
                    aria-label="Close"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>
    <!-- Header -->
    <header class="bg-gray-800 border-b border-purple-600" 
            x-data="{ 
                mobileMenuOpen: false,
                cartCount: {{ array_sum(session('cart', [])) }},
                async updateCartCount() {
                    try {
                        const response = await fetch('{{ route('cart.count') }}');
                        const data = await response.json();
                        this.cartCount = data.count;
                    } catch (error) {
                        console.error('Error updating cart count:', error);
                    }
                },
                incrementCartCount(quantity = 1) {
                    this.cartCount += quantity;
                    // Animate counter with bounce effect
                    const badges = document.querySelectorAll('[x-ref=\'cartBadge\']');
                    badges.forEach(badge => {
                        badge.classList.add('scale-125');
                        setTimeout(() => badge.classList.remove('scale-125'), 300);
                    });
                },
                decrementCartCount(quantity = 1) {
                    this.cartCount = Math.max(0, this.cartCount - quantity);
                }
            }"
            @cart-item-added.window="incrementCartCount($event.detail.quantity)"
            @cart-updated.window="updateCartCount()"
            x-init="updateCartCount(); setInterval(() => updateCartCount(), 5000)">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-purple-400 hover:text-purple-300 transition">
                        LedeCore
                    </a>
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="text-gray-300 hover:text-purple-400 transition">
                            {{ __('common.home') }}
                        </a>
                        <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-purple-400 transition">
                            {{ __('common.shop') }}
                        </a>
                        <a href="{{ route('cart.index') }}" class="text-gray-300 hover:text-purple-400 transition flex items-center space-x-1 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>{{ __('common.cart') }}</span>
                            <span x-show="cartCount > 0" 
                                  x-ref="cartBadge"
                                  x-text="cartCount"
                                  class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center min-w-[20px] transition-all duration-300"
                                  style="display: none;"></span>
                        </a>
                        <a href="{{ route('orders.track') }}" class="text-gray-300 hover:text-purple-400 transition">
                            {{ __('common.track_order') }}
                        </a>
                    </div>
                </div>
                <!-- Desktop Language Switch -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('language.switch', 'fr') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'fr' ? 'bg-purple-600 text-white' : 'text-gray-400 hover:text-purple-400' }} transition">
                        FR
                    </a>
                    <span class="text-gray-600">|</span>
                    <a href="{{ route('language.switch', 'en') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-purple-600 text-white' : 'text-gray-400 hover:text-purple-400' }} transition">
                        EN
                    </a>
                </div>
                <!-- Mobile Hamburger Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-300 hover:text-purple-400 transition p-2" aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Slide-in Menu -->
        <div 
            x-show="mobileMenuOpen"
            @click.away="mobileMenuOpen = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 border-r border-purple-600 shadow-xl md:hidden"
            style="display: none;"
        >
            <div class="flex flex-col h-full">
                <!-- Menu Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <span class="text-xl font-bold text-purple-400">Menu</span>
                    <button @click="mobileMenuOpen = false" class="text-gray-300 hover:text-white transition p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-4">
                    <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition">
                        {{ __('common.home') }}
                    </a>
                    <a href="{{ route('products.index') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition">
                        {{ __('common.shop') }}
                    </a>
                    <a href="{{ route('cart.index') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition flex items-center space-x-2 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>{{ __('common.cart') }}</span>
                        <span x-show="cartCount > 0" 
                              x-text="cartCount"
                              x-transition:enter="transition ease-out duration-200"
                              x-transition:enter-start="scale-0"
                              x-transition:enter-end="scale-100"
                              class="ml-auto bg-purple-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center min-w-[20px] transition-all duration-300 shadow-lg"
                              style="display: none;"></span>
                    </a>
                    <a href="{{ route('orders.track') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition">
                        {{ __('common.track_order') }}
                    </a>
                </nav>

                <!-- Language Switch (Always Visible) -->
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="{{ route('language.switch', 'fr') }}" @click="mobileMenuOpen = false" class="px-4 py-2 rounded {{ app()->getLocale() === 'fr' ? 'bg-purple-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }} transition font-medium">
                            FR
                        </a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('language.switch', 'en') }}" @click="mobileMenuOpen = false" class="px-4 py-2 rounded {{ app()->getLocale() === 'en' ? 'bg-purple-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }} transition font-medium">
                            EN
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div 
            x-show="mobileMenuOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileMenuOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
            style="display: none;"
        ></div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-purple-600 mt-auto">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} LedeCore. {{ __('common.welcome') }}
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex space-x-4 text-sm">
                        <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-purple-400 transition">
                            {{ __('common.shop') }}
                        </a>
                        <a href="{{ route('orders.track') }}" class="text-gray-400 hover:text-purple-400 transition">
                            {{ __('common.track_order') }}
                        </a>
                    </div>
                    @php
                        $whatsappUrl = \App\Models\Setting::get('social_whatsapp', '');
                        $facebookUrl = \App\Models\Setting::get('social_facebook', '');
                        $instagramUrl = \App\Models\Setting::get('social_instagram', '');
                        $tiktokUrl = \App\Models\Setting::get('social_tiktok', '');
                    @endphp
                    @if($whatsappUrl || $facebookUrl || $instagramUrl || $tiktokUrl)
                        <div class="flex items-center space-x-3 border-l border-gray-700 pl-4">
                            @if($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-green-400 transition" aria-label="WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($facebookUrl)
                                <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-blue-400 transition" aria-label="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($instagramUrl)
                                <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-pink-400 transition" aria-label="Instagram">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($tiktokUrl)
                                <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-purple-400 transition" aria-label="TikTok">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-3.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

