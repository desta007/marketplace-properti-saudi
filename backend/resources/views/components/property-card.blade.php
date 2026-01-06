<div class="property-card bg-white rounded-2xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300">
    <div class="relative h-56 overflow-hidden">
        @php
            $primaryImage = $property->images->firstWhere('is_primary', true) ?? $property->images->first();
            $imageUrl = $primaryImage ? asset('storage/' . $primaryImage->image_path) : 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=600&h=400&fit=crop';
        @endphp
        <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        
        <div class="absolute top-3 start-3 flex gap-2">
            <span class="px-3 py-1 text-white text-xs font-bold rounded-full {{ $property->purpose === 'rent' ? 'bg-blue-600' : 'bg-emerald-600' }}">
                {{ $property->purpose === 'rent' ? __('purpose.rent') : __('purpose.sale') }}
            </span>
        </div>
        
        @auth
            <button onclick="toggleFavorite({{ $property->id }})" class="absolute top-3 end-3 w-10 h-10 rounded-full bg-white/80 backdrop-blur flex items-center justify-center hover:bg-white transition-colors favorite-btn" data-property="{{ $property->id }}">
                <svg class="w-5 h-5 {{ auth()->user()->favorites->contains($property->id) ? 'text-red-500 fill-current' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        @endauth
    </div>
    
    <div class="p-5">
        <h3 class="font-bold text-lg text-gray-900 line-clamp-1 mb-2">{{ $property->title }}</h3>
        <p class="text-gray-500 text-sm flex items-center gap-1 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>{{ $property->city->name }}{{ $property->district ? ', ' . $property->district->name : '' }}</span>
        </p>
        
        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
            @if($property->bedrooms)
                <span class="flex items-center gap-1">🛏️ {{ $property->bedrooms }}</span>
            @endif
            @if($property->bathrooms)
                <span class="flex items-center gap-1">🚿 {{ $property->bathrooms }}</span>
            @endif
            @if($property->area_sqm)
                <span class="flex items-center gap-1">📐 {{ number_format($property->area_sqm) }}m²</span>
            @endif
        </div>
        
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-emerald-600">
                {{ number_format($property->price, 0) }}
                <span class="text-sm font-normal text-gray-500">SAR{{ $property->purpose === 'rent' ? '/yr' : '' }}</span>
            </p>
            <a href="{{ route('properties.show', $property) }}" class="px-4 py-2 bg-gray-100 hover:bg-emerald-500 hover:text-white rounded-lg transition-colors text-sm font-medium">
                {{ __('card.view') }}
            </a>
        </div>
    </div>
</div>
