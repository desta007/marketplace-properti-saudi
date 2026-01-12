@extends('layouts.app')

@section('title', __('nav.my_properties'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">{{ __('nav.my_properties') }}</h1>
        @if($quotaInfo['can_create'])
            <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Property
            </a>
        @else
            <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors">
                ⚠️ Upgrade to Add More
            </a>
        @endif
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Quota Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
            <div class="text-2xl font-bold text-emerald-600">
                {{ $quotaInfo['remaining'] === PHP_INT_MAX ? '∞' : $quotaInfo['remaining'] }}
            </div>
            <div class="text-gray-600 text-sm">Remaining Slots</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
            <div class="text-2xl font-bold text-gray-900">{{ $quotaInfo['used'] }}</div>
            <div class="text-gray-600 text-sm">Active Listings</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
            <div class="text-2xl font-bold text-amber-600">{{ $quotaInfo['credits'] }}</div>
            <div class="text-gray-600 text-sm">Listing Credits</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $quotaInfo['featured_credits'] }}</div>
            <div class="text-gray-600 text-sm">Featured Credits</div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('subscription.index') }}" class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 text-sm font-medium">
            💳 Subscription
        </a>
        <a href="{{ route('subscription.buy-credits') }}" class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 text-sm font-medium">
            📦 Buy Credits
        </a>
    </div>
    
    @if($properties->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Property</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Price</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Boost</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Views</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($properties as $property)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-12 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                                        @if($property->images->count() > 0)
                                            <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ Str::limit($property->title, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ $property->city?->name }} • {{ ucfirst($property->type) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-emerald-600">{{ number_format($property->price) }} SAR</span>
                            </td>
                            <td class="px-6 py-4">
                                @switch($property->status)
                                    @case('active')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">Active</span>
                                        @break
                                    @case('pending')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-medium rounded-full">Pending Review</span>
                                        @break
                                    @case('rejected')
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">Rejected</span>
                                        @break
                                    @case('sold')
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">Sold</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                @if($property->activeBoost)
                                    @php
                                        $badge = $property->boostBadge;
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-bold rounded-full {{ $badge['class'] ?? 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $badge['label'] ?? 'Featured' }}
                                    </span>
                                    <br>
                                    <span class="text-xs text-gray-500">{{ $property->activeBoost->daysRemaining() }}d left</span>
                                @elseif($property->status === 'active')
                                    <a href="{{ route('subscription.boost', $property) }}" class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full hover:bg-purple-200 transition">
                                        🚀 Boost
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ number_format($property->view_count) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('properties.show', $property) }}" class="p-2 hover:bg-gray-100 rounded-lg" title="View">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('properties.edit', $property) }}" class="p-2 hover:bg-gray-100 rounded-lg" title="Edit">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $properties->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl shadow-lg">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">No properties yet</h2>
            <p class="text-gray-500 mb-6">Start by adding your first property listing.</p>
            @if($quotaInfo['can_create'])
                <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Your First Property
                </a>
            @else
                <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-colors">
                    ⚠️ Upgrade Plan to Add Properties
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
