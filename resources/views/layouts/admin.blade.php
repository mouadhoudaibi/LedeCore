<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - LedeCore')</title>
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
                    <svg x-show="toast.type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <svg x-show="toast.type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="flex-1 font-medium text-sm" x-text="toast.message"></p>
                <button 
                    @click="removeToast(toast.id)"
                    class="flex-shrink-0 text-white hover:text-gray-200 transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Admin Header -->
    <header class="bg-gray-800 border-b border-purple-600" x-data="{ mobileMenuOpen: false }">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-purple-400 hover:text-purple-300 transition">
                        {{ __('admin.admin') }} - LedeCore
                    </a>
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-purple-400 transition flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>{{ __('admin.dashboard') }}</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="text-gray-300 hover:text-purple-400 transition flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>{{ __('admin.categories') }}</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-300 hover:text-purple-400 transition flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>{{ __('admin.products') }}</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="text-gray-300 hover:text-purple-400 transition flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>{{ __('admin.orders') }}</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="text-gray-300 hover:text-purple-400 transition flex items-center space-x-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ __('admin.settings') }}</span>
                        </a>
                    </div>
                </div>
                <!-- Desktop Language Switch & Shop Link -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-purple-400 transition text-sm">
                        {{ __('common.shop') }}
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-purple-400 transition text-sm">
                            {{ __('admin.logout') }}
                        </button>
                    </form>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('language.switch', 'fr') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'fr' ? 'bg-purple-600 text-white' : 'text-gray-400 hover:text-purple-400' }} transition">
                            FR
                        </a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('language.switch', 'en') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-purple-600 text-white' : 'text-gray-400 hover:text-purple-400' }} transition">
                            EN
                        </a>
                    </div>
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
                    <span class="text-xl font-bold text-purple-400">{{ __('admin.admin') }}</span>
                    <button @click="mobileMenuOpen = false" class="text-gray-300 hover:text-white transition p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>{{ __('admin.dashboard') }}</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span>{{ __('admin.categories') }}</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>{{ __('admin.products') }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>{{ __('admin.orders') }}</span>
                    </a>
                    <a href="{{ route('products.index') }}" @click="mobileMenuOpen = false" class="block text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition mt-4 border-t border-gray-700 pt-4">
                        {{ __('common.shop') }}
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="mt-4 border-t border-gray-700 pt-4">
                        @csrf
                        <button type="submit" @click="mobileMenuOpen = false" class="block w-full text-center text-gray-300 hover:text-purple-400 hover:bg-gray-700 px-4 py-3 rounded-lg transition">
                            {{ __('admin.logout') }}
                        </button>
                    </form>
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
        <div class="container mx-auto px-4 py-4 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} LedeCore {{ __('admin.admin') }}
        </div>
    </footer>
</body>
</html>

