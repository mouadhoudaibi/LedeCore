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
        @if(session('success'))
            <div class="mb-6 bg-green-600 text-white px-5 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-600 text-white px-5 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-purple-600 mt-auto">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; {{ date('Y') }} LedeCore. {{ __('common.welcome') }}
                </div>
                <div class="flex space-x-6 text-sm">
                    <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-purple-400 transition">
                        {{ __('common.shop') }}
                    </a>
                    <a href="{{ route('orders.track') }}" class="text-gray-400 hover:text-purple-400 transition">
                        {{ __('common.track_order') }}
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

