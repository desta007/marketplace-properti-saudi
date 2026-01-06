@extends('layouts.app')

@section('title', __('nav.home'))

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 text-white overflow-hidden">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=%2240%22 height=%2240%22 viewBox=%220 0 40 40%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cpath d=%22M0 40L40 0H20L0 20M40 40V20L20 40%22 stroke=%22%23ffffff%22 stroke-opacity=%220.15%22 stroke-width=%221%22/%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    {{ __('hero.title') }}
                </h1>
                <p class="text-xl md:text-2xl text-emerald-100 mb-10">
                    {{ __('hero.subtitle') }}
                </p>

                <!-- Search Box -->
                <form action="{{ route('properties.index') }}" method="GET" class="bg-white rounded-2xl p-4 shadow-2xl max-w-4xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="relative">
                            <select name="city" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">{{ __('search.city') }}</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative">
                            <select name="type" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">{{ __('search.type') }}</option>
                                <option value="villa">{{ __('types.villa') }}</option>
                                <option value="apartment">{{ __('types.apartment') }}</option>
                                <option value="compound">{{ __('types.compound') }}</option>
                                <option value="land">{{ __('types.land') }}</option>
                            </select>
                        </div>
                        <div class="relative">
                            <select name="purpose" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                                <option value="">{{ __('search.purpose') }}</option>
                                <option value="rent">{{ __('purpose.rent') }}</option>
                                <option value="sale">{{ __('purpose.sale') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>{{ __('search.button') }}</span>
                        </button>
                    </div>
                </form>

                <!-- Stats -->
                <div class="mt-12 grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold">{{ number_format($stats['properties']) }}+</div>
                        <div class="text-emerald-200">{{ __('stats.properties') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold">{{ $stats['agents'] }}+</div>
                        <div class="text-emerald-200">{{ __('stats.agents') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold">{{ $stats['cities'] }}</div>
                        <div class="text-emerald-200">{{ __('stats.cities') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Cities -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">{{ __('cities.title') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($cities as $city)
                    <a href="{{ route('properties.index', ['city' => $city->id]) }}" class="group relative rounded-2xl overflow-hidden aspect-[4/3] shadow-lg hover:shadow-xl transition-all">
                        <img src="https://images.unsplash.com/photo-1586724237569-f3d0c1dee8c6?w=400&h=300&fit=crop" alt="{{ $city->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-4 start-4 text-white">
                            <h3 class="text-xl font-bold">{{ $city->name }}</h3>
                            <p class="text-sm text-white/80">{{ $city->properties_count }} {{ __('cities.properties') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold">{{ __('featured.title') }}</h2>
                <a href="{{ route('properties.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-2">
                    <span>{{ __('featured.view_all') }}</span>
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProperties as $property)
                    @include('components.property-card', ['property' => $property])
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">{{ __('why.title') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('why.verified') }}</h3>
                    <p class="text-gray-600">{{ __('why.verified_desc') }}</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('why.map_search') }}</h3>
                    <p class="text-gray-600">{{ __('why.map_search_desc') }}</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('why.virtual_tour') }}</h3>
                    <p class="text-gray-600">{{ __('why.virtual_tour_desc') }}</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ __('why.direct_contact') }}</h3>
                    <p class="text-gray-600">{{ __('why.direct_contact_desc') }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
