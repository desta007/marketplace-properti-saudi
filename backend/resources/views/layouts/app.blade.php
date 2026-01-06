<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SaudiProp') - Saudi Arabia Property Marketplace</title>
    
    <!-- Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Leaflet for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @stack('styles')
</head>
<body class="font-sans bg-gray-50 text-gray-900 {{ app()->getLocale() === 'ar' ? 'rtl' : '' }}">
    
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-lg">S</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Saudi<span class="text-emerald-600">Prop</span></span>
                </a>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-emerald-600 font-medium transition-colors {{ request()->routeIs('home') ? 'text-emerald-600' : '' }}">
                        {{ __('nav.home') }}
                    </a>
                    <a href="{{ route('properties.index') }}" class="text-gray-600 hover:text-emerald-600 font-medium transition-colors {{ request()->routeIs('properties.*') ? 'text-emerald-600' : '' }}">
                        {{ __('nav.properties') }}
                    </a>
                    <a href="#" class="text-gray-600 hover:text-emerald-600 font-medium transition-colors">
                        {{ __('nav.agents') }}
                    </a>
                    <a href="#" class="text-gray-600 hover:text-emerald-600 font-medium transition-colors">
                        {{ __('nav.about') }}
                    </a>
                </nav>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                            <span class="text-lg">{{ app()->getLocale() === 'ar' ? '🇸🇦' : '🇬🇧' }}</span>
                            <span class="text-sm font-medium uppercase">{{ app()->getLocale() }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute end-0 mt-2 w-40 bg-white rounded-xl shadow-lg border overflow-hidden z-50">
                            <a href="{{ route('locale.switch', 'en') }}" class="w-full px-4 py-3 text-start hover:bg-gray-50 flex items-center gap-3 {{ app()->getLocale() === 'en' ? 'bg-emerald-50' : '' }}">
                                <span>🇬🇧</span> English
                            </a>
                            <a href="{{ route('locale.switch', 'ar') }}" class="w-full px-4 py-3 text-start hover:bg-gray-50 flex items-center gap-3 {{ app()->getLocale() === 'ar' ? 'bg-emerald-50' : '' }}">
                                <span>🇸🇦</span> العربية
                            </a>
                        </div>
                    </div>

                    <!-- Login Button -->
                    @guest
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                            {{ __('nav.login') }}
                        </a>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute end-0 mt-2 w-48 bg-white rounded-xl shadow-lg border overflow-hidden z-50">
                                <a href="#" class="block px-4 py-3 text-start hover:bg-gray-50">{{ __('nav.profile') }}</a>
                                <a href="#" class="block px-4 py-3 text-start hover:bg-gray-50">{{ __('nav.favorites') }}</a>
                                @if(auth()->user()->isAgent())
                                    <a href="#" class="block px-4 py-3 text-start hover:bg-gray-50">{{ __('nav.my_properties') }}</a>
                                @endif
                                <hr class="my-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 text-start hover:bg-gray-50 text-red-600">{{ __('nav.logout') }}</button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    <!-- Mobile Menu Toggle -->
                    <button class="md:hidden p-2" x-data @click="$dispatch('toggle-mobile-menu')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">S</span>
                        </div>
                        <span class="text-xl font-bold">Saudi<span class="text-emerald-400">Prop</span></span>
                    </div>
                    <p class="text-gray-400">{{ __('footer.desc') }}</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ __('footer.quick_links') }}</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">{{ __('nav.home') }}</a></li>
                        <li><a href="{{ route('properties.index') }}" class="hover:text-emerald-400 transition-colors">{{ __('nav.properties') }}</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">{{ __('nav.agents') }}</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">{{ __('nav.about') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ __('footer.property_types') }}</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('properties.index', ['type' => 'villa']) }}" class="hover:text-emerald-400 transition-colors">{{ __('types.villa') }}</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'apartment']) }}" class="hover:text-emerald-400 transition-colors">{{ __('types.apartment') }}</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'compound']) }}" class="hover:text-emerald-400 transition-colors">{{ __('types.compound') }}</a></li>
                        <li><a href="{{ route('properties.index', ['type' => 'land']) }}" class="hover:text-emerald-400 transition-colors">{{ __('types.land') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">{{ __('footer.contact') }}</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>📧 info@saudiprop.com</li>
                        <li>📱 +966 50 123 4567</li>
                        <li>📍 Riyadh, Saudi Arabia</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-500">
                <p>© {{ date('Y') }} SaudiProp. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
