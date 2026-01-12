@extends('layouts.app')

@section('title', 'My Leads')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">📩 My Leads</h1>
        <a href="{{ route('my-properties') }}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            My Properties
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
            <div class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Leads</div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-blue-500">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['new'] }}</div>
            <div class="text-sm text-gray-500 mt-1">New</div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-amber-500">
            <div class="text-3xl font-bold text-amber-600">{{ $stats['contacted'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Contacted</div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-purple-500">
            <div class="text-3xl font-bold text-purple-600">{{ $stats['qualified'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Qualified</div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center border-l-4 border-emerald-500">
            <div class="text-3xl font-bold text-emerald-600">{{ $stats['converted'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Converted</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <label class="text-sm font-medium text-gray-700">Filter by Status:</label>
            <select name="status" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>🆕 New</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>📞 Contacted</option>
                <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>✅ Qualified</option>
                <option value="viewing_scheduled" {{ request('status') === 'viewing_scheduled' ? 'selected' : '' }}>📅 Viewing Scheduled</option>
                <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>🎉 Converted</option>
                <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>❌ Lost</option>
            </select>
        </form>
    </div>
    
    @if($leads->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Seeker</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Property</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($leads as $lead)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $lead->seeker_name }}</div>
                                <div class="text-sm text-gray-500">📱 {{ $lead->seeker_phone }}</div>
                                @if($lead->seeker_email)
                                    <div class="text-sm text-gray-500">📧 {{ $lead->seeker_email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
                                        @if($lead->property && $lead->property->images->count() > 0)
                                            <img src="{{ asset('storage/' . $lead->property->images->first()->image_path) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        @if($lead->property)
                                            <a href="{{ route('properties.show', $lead->property) }}" class="font-medium text-emerald-600 hover:text-emerald-700">
                                                {{ Str::limit($lead->property->title, 30) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Property deleted</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @switch($lead->status)
                                    @case('new')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">🆕 New</span>
                                        @break
                                    @case('contacted')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-medium rounded-full">📞 Contacted</span>
                                        @break
                                    @case('qualified')
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-medium rounded-full">✅ Qualified</span>
                                        @break
                                    @case('viewing_scheduled')
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-full">📅 Viewing</span>
                                        @break
                                    @case('converted')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">🎉 Converted</span>
                                        @break
                                    @case('lost')
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">❌ Lost</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">
                                {{ $lead->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $lead->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('leads.show', $lead) }}" class="p-2 hover:bg-gray-100 rounded-lg" title="View Details">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="tel:{{ $lead->seeker_phone }}" class="p-2 hover:bg-emerald-100 rounded-lg" title="Call">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->seeker_phone) }}" target="_blank" class="p-2 hover:bg-green-100 rounded-lg" title="WhatsApp">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
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
            {{ $leads->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl shadow-lg">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">No leads yet</h2>
            <p class="text-gray-500 mb-6">When customers inquire about your properties, their leads will appear here.</p>
            <a href="{{ route('my-properties') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                View My Properties
            </a>
        </div>
    @endif
</div>
@endsection
