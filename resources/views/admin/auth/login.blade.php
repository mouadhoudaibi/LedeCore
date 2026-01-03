<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.login') }} - LedeCore</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
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
            @if(session('error'))
                addToast('error', {{ json_encode(session('error')) }});
            @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    addToast('error', {{ json_encode($error) }});
                @endforeach
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
                class="bg-red-600 text-white px-4 py-3 md:px-5 md:py-4 rounded-lg shadow-xl flex items-center space-x-3 min-w-[280px] md:min-w-[300px] max-w-[calc(100vw-2rem)] md:max-w-md"
            >
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div class="w-full max-w-md px-4">
        <div class="bg-gray-800 rounded-lg border border-purple-600 p-8 shadow-2xl">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-purple-400 mb-2">LedeCore</h1>
                <p class="text-gray-400">{{ __('admin.admin_login') }}</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-gray-300 text-sm font-medium mb-2">
                        {{ __('admin.email') }}
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                </div>

                <div>
                    <label for="password" class="block text-gray-300 text-sm font-medium mb-2">
                        {{ __('admin.password') }}
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 transition">
                </div>

                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-6 rounded-lg transition font-semibold flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>{{ __('admin.login') }}</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-purple-400 transition text-sm">
                    {{ __('common.back_to_shop') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>

