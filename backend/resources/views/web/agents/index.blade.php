@extends('layouts.app')

@section('title', __('nav.agents'))

@section('content')
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-emerald-600 to-teal-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ __('agents.title') }}</h1>
            <p class="text-xl text-emerald-100 max-w-2xl mx-auto">{{ __('agents.subtitle') }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white shadow-sm border-b sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form method="GET" action="{{ route('agents.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[250px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Agents</label>
                    <input type="text" name="search" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="Agent name or license number..."
                           value="{{ request('search') }}">
                </div>
                <div class="min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="properties" {{ request('sort') == 'properties' ? 'selected' : '' }}>Most Properties</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Agents</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                    🔍 Search
                </button>
            </form>
        </div>
    </div>

    <!-- Agents Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($agents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($agents as $agent)
                    <a href="{{ route('agents.show', $agent) }}" 
                       class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                        <!-- Agent Header -->
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-6 text-white text-center">
                            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-4xl font-bold shadow-lg mb-4 group-hover:scale-110 transition-transform">
                                {{ substr($agent->name, 0, 1) }}
                            </div>
                            <h3 class="text-xl font-bold">{{ $agent->name }}</h3>
                            <div class="flex items-center justify-center gap-1 text-emerald-400 mt-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                                </svg>
                                <span class="text-sm">REGA Verified</span>
                            </div>
                        </div>
                        
                        <!-- Agent Info -->
                        <div class="p-6">
                            @if($agent->rega_license_number)
                                <div class="mb-4">
                                    <span class="text-xs text-gray-500">REGA License</span>
                                    <p class="font-mono font-semibold text-gray-900">{{ $agent->rega_license_number }}</p>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-emerald-600">{{ $agent->properties_count }}</p>
                                    <p class="text-sm text-gray-500">Properties</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-semibold text-gray-900">{{ $agent->language === 'ar' ? '🇸🇦' : '🇬🇧' }}</p>
                                    <p class="text-sm text-gray-500">Language</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                @if($agent->whatsapp_number)
                                    <span class="flex-1 px-4 py-2 bg-green-500 text-white text-center rounded-lg text-sm font-medium group-hover:bg-green-600 transition-colors">
                                        💬 WhatsApp
                                    </span>
                                @endif
                                <span class="flex-1 px-4 py-2 bg-emerald-600 text-white text-center rounded-lg text-sm font-medium group-hover:bg-emerald-700 transition-colors">
                                    View Profile →
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $agents->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">👥</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No agents found</h3>
                <p class="text-gray-500">Try adjusting your search criteria</p>
            </div>
        @endif
    </div>

    <!-- Become Agent CTA -->
    @guest
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 py-16">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Are you a REGA licensed agent?</h2>
                <p class="text-slate-300 mb-8 text-lg">Join SaudiProp and reach thousands of property seekers across Saudi Arabia</p>
                <a href="{{ route('register') }}" class="inline-flex px-8 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                    Register as Agent →
                </a>
            </div>
        </div>
    @endguest
@endsection
