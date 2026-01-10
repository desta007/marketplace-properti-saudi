@extends('layouts.app')

@section('title', __('nav.favorites'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">{{ __('nav.favorites') }}</h1>

        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all">
                        <div class="relative h-56 overflow-hidden">
                            @if($property->images->count() > 0)
                                <img src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No image</span>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-3 py-1 text-white text-xs font-bold rounded-full {{ $property->purpose === 'rent' ? 'bg-blue-600' : 'bg-emerald-600' }}">
                                    {{ $property->purpose === 'rent' ? __('purpose.rent') : __('purpose.sale') }}
                                </span>
                            </div>
                            <!-- Remove from favorites -->
                            <form action="{{ route('favorites.toggle', $property) }}" method="POST" class="absolute top-3 right-3">
                                @csrf
                                <button type="submit"
                                    class="w-10 h-10 rounded-full bg-white/80 backdrop-blur flex items-center justify-center hover:bg-white transition-colors">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-lg text-gray-900 line-clamp-1 mb-2">{{ $property->title }}</h3>
                            <p class="text-gray-500 text-sm flex items-center gap-1 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $property->city?->name }}
                            </p>
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center gap-1">🛏️ {{ $property->bedrooms ?? '-' }}</span>
                                <span class="flex items-center gap-1">🚿 {{ $property->bathrooms ?? '-' }}</span>
                                <span class="flex items-center gap-1">📐 {{ $property->area_sqm ?? '-' }}m²</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-bold text-emerald-600">
                                    {{ number_format($property->price) }}
                                    <span
                                        class="text-sm font-normal text-gray-500">SAR{{ $property->purpose === 'rent' ? '/yr' : '' }}</span>
                                </p>
                                <a href="{{ route('properties.show', $property) }}"
                                    class="px-4 py-2 bg-gray-100 hover:bg-emerald-500 hover:text-white rounded-lg transition-colors text-sm font-medium">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $properties->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">No favorites yet</h2>
                <p class="text-gray-500 mb-6">Start exploring properties and save your favorites here.</p>
                <a href="{{ route('properties.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                    Browse Properties
                </a>
            </div>
        @endif
    </div>
@endsection