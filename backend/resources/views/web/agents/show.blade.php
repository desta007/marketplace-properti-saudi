@extends('layouts.app')

@section('title', $agent->name)

@section('content')
    <!-- Agent Header -->
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Breadcrumb -->
            <a href="{{ route('agents.index') }}"
                class="text-emerald-400 hover:text-emerald-300 flex items-center gap-2 mb-6">
                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Agents
            </a>

            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Avatar -->
                <div
                    class="w-32 h-32 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-5xl font-bold shadow-xl">
                    {{ substr($agent->name, 0, 1) }}
                </div>

                <!-- Info -->
                <div class="flex-1 text-center md:text-start">
                    <h1 class="text-3xl font-bold mb-2">{{ $agent->name }}</h1>

                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 mb-4">
                        <div class="flex items-center gap-1 text-emerald-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                            </svg>
                            <span class="font-medium">REGA Verified Agent</span>
                        </div>
                        @if($agent->agent_verified_at)
                            <span class="text-slate-400">• Verified {{ $agent->agent_verified_at->diffForHumans() }}</span>
                        @endif
                    </div>

                    @if($agent->rega_license_number)
                        <div class="inline-block bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2 mb-6">
                            <span class="text-sm text-slate-300">REGA License:</span>
                            <span class="font-mono font-bold text-emerald-400 ms-2">{{ $agent->rega_license_number }}</span>
                        </div>
                    @endif

                    <!-- Contact Buttons -->
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        @if($agent->whatsapp_number)
                            <a href="{{ $agent->whatsapp_url }}?text={{ urlencode('Hi, I found your profile on SaudiProp and would like to inquire about properties.') }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white font-medium rounded-xl hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                </svg>
                                WhatsApp
                            </a>
                        @endif
                        @if($agent->phone)
                            <a href="tel:{{ $agent->phone }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $agent->phone }}
                            </a>
                        @endif
                        @if($agent->email)
                            <a href="mailto:{{ $agent->email }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Stats -->
                <div class="flex gap-6 bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-emerald-400">{{ $properties->total() }}</p>
                        <p class="text-sm text-slate-300">Active Listings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent Properties -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Properties by {{ $agent->name }}</h2>

        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($properties as $property)
                    <a href="{{ route('properties.show', $property) }}"
                        class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                        <div class="relative h-48 overflow-hidden">
                            @if($property->images->count() > 0)
                                <img src="{{ asset('storage/' . $property->images->first()->image_path) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                    alt="{{ $property->title }}">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-4xl">🏠</span>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-3 py-1 text-white text-sm font-bold rounded-full {{ $property->purpose === 'rent' ? 'bg-blue-600' : 'bg-emerald-600' }}">
                                    {{ $property->purpose === 'rent' ? __('purpose.rent') : __('purpose.sale') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 mb-1 line-clamp-1">{{ $property->title }}</h3>
                            <p class="text-gray-500 text-sm mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $property->city?->name }}
                            </p>
                            <div class="flex items-center justify-between">
                                <p class="text-emerald-600 font-bold">{{ number_format($property->price) }} SAR</p>
                                <div class="flex gap-2 text-xs text-gray-500">
                                    @if($property->bedrooms)
                                        <span>🛏️ {{ $property->bedrooms }}</span>
                                    @endif
                                    @if($property->area_sqm)
                                        <span>📐 {{ $property->area_sqm }}m²</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $properties->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-gray-50 rounded-2xl">
                <div class="text-6xl mb-4">🏠</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No properties listed yet</h3>
                <p class="text-gray-500">This agent hasn't listed any properties</p>
            </div>
        @endif
    </div>
@endsection