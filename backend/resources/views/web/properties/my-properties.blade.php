@extends('layouts.app')

@section('title', __('nav.my_properties'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">{{ __('nav.my_properties') }}</h1>
        <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Property
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif
    
    @if($properties->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Property</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Price</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Views</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Posted</th>
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
                            <td class="px-6 py-4 text-gray-600">
                                {{ number_format($property->view_count) }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">
                                {{ $property->created_at->format('M d, Y') }}
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
            <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Your First Property
            </a>
        </div>
    @endif
</div>
@endsection
