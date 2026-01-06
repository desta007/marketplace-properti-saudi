@extends('layouts.app')

@section('title', __('nav.properties'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Filters Sidebar -->
            <aside class="lg:w-72 shrink-0">
                <form action="{{ route('properties.index') }}" method="GET" class="bg-white rounded-2xl p-6 shadow-lg sticky top-24">
                    <h3 class="font-bold text-lg mb-6">{{ __('search.button') }}</h3>
                    
                    <!-- City -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('search.city') }}</label>
                        <select name="city" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">{{ __('search.city') }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('search.type') }}</label>
                        <select name="type" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">{{ __('search.type') }}</option>
                            <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>{{ __('types.villa') }}</option>
                            <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>{{ __('types.apartment') }}</option>
                            <option value="compound" {{ request('type') == 'compound' ? 'selected' : '' }}>{{ __('types.compound') }}</option>
                            <option value="land" {{ request('type') == 'land' ? 'selected' : '' }}>{{ __('types.land') }}</option>
                        </select>
                    </div>

                    <!-- Purpose -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('search.purpose') }}</label>
                        <select name="purpose" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">{{ __('search.purpose') }}</option>
                            <option value="rent" {{ request('purpose') == 'rent' ? 'selected' : '' }}>{{ __('purpose.rent') }}</option>
                            <option value="sale" {{ request('purpose') == 'sale' ? 'selected' : '' }}>{{ __('purpose.sale') }}</option>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('search.min_price') }}</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('search.max_price') }}</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="10000000" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <!-- Bedrooms -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('search.bedrooms') }}</label>
                        <select name="bedrooms" class="w-full px-4 py-3 bg-gray-100 rounded-xl text-gray-700 focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="">Any</option>
                            @for($i = 1; $i <= 7; $i++)
                                <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all">
                        {{ __('search.button') }}
                    </button>
                    
                    @if(request()->hasAny(['city', 'type', 'purpose', 'min_price', 'max_price', 'bedrooms']))
                        <a href="{{ route('properties.index') }}" class="block text-center mt-3 text-sm text-gray-600 hover:text-emerald-600">Clear Filters</a>
                    @endif
                </form>
            </aside>

            <!-- Properties Grid -->
            <div class="flex-1">
                <!-- Sort & View Toggle -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600">{{ $properties->total() }} {{ __('stats.properties') }}</p>
                    <div class="flex items-center gap-4">
                        <select name="sort" onchange="window.location.href=this.value" class="px-4 py-2 bg-white rounded-lg border text-sm">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Properties -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($properties as $property)
                        @include('components.property-card', ['property' => $property])
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No properties found</h3>
                            <p class="text-gray-600">Try adjusting your filters</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $properties->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
