@extends('layouts.app')

@section('title', 'Lead Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <a href="{{ route('leads.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Leads
    </a>
    
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Lead Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <span class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    Seeker Information
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                            {{ strtoupper(substr($lead->seeker_name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-gray-900">{{ $lead->seeker_name }}</div>
                            <div class="text-sm text-gray-500">Potential Buyer</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="tel:{{ $lead->seeker_phone }}" class="flex items-center gap-3 p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                            <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Phone</div>
                                <div class="font-medium text-gray-900">{{ $lead->seeker_phone }}</div>
                            </div>
                        </a>
                        
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->seeker_phone) }}" target="_blank" class="flex items-center gap-3 p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">WhatsApp</div>
                                <div class="font-medium text-gray-900">Send Message</div>
                            </div>
                        </a>
                    </div>
                    
                    @if($lead->seeker_email)
                        <a href="mailto:{{ $lead->seeker_email }}" class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Email</div>
                                <div class="font-medium text-gray-900">{{ $lead->seeker_email }}</div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Message -->
            @if($lead->message)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </span>
                        Message
                    </h2>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $lead->message }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Notes -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <span class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </span>
                    Notes
                </h2>
                <form action="{{ route('leads.update-status', $lead) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $lead->status }}">
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Add notes about this lead...">{{ $lead->notes }}</textarea>
                    <button type="submit" class="mt-3 px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                        Save Notes
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Date -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Lead Status</h3>
                
                <form action="{{ route('leads.update-status', $lead) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="notes" value="{{ $lead->notes }}">
                    <select name="status" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>🆕 New</option>
                        <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>📞 Contacted</option>
                        <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>✅ Qualified</option>
                        <option value="viewing_scheduled" {{ $lead->status === 'viewing_scheduled' ? 'selected' : '' }}>📅 Viewing Scheduled</option>
                        <option value="converted" {{ $lead->status === 'converted' ? 'selected' : '' }}>🎉 Converted</option>
                        <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>❌ Lost</option>
                    </select>
                </form>
                
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">Received</div>
                    <div class="font-medium text-gray-900">{{ $lead->created_at->format('M d, Y \a\t H:i') }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $lead->created_at->diffForHumans() }}</div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500">Source</div>
                    <div class="font-medium text-gray-900">
                        @switch($lead->source)
                            @case('whatsapp')
                                💬 WhatsApp
                                @break
                            @case('phone')
                                📱 Phone Call
                                @break
                            @case('chat')
                                💭 In-App Chat
                                @break
                            @case('form')
                                📋 Contact Form
                                @break
                            @case('viewing_request')
                                🏠 Viewing Request
                                @break
                            @default
                                📋 Contact Form
                        @endswitch
                    </div>
                </div>
            </div>
            
            <!-- Property Card -->
            @if($lead->property)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="aspect-video bg-gray-200">
                        @if($lead->property->images->count() > 0)
                            <img src="{{ asset('storage/' . $lead->property->images->first()->image_path) }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ Str::limit($lead->property->title, 35) }}</h3>
                        <div class="text-sm text-gray-500 mb-2">{{ $lead->property->city?->name }}</div>
                        <div class="text-lg font-bold text-emerald-600">{{ number_format($lead->property->price) }} SAR</div>
                        <a href="{{ route('properties.show', $lead->property) }}" class="mt-3 inline-flex items-center gap-2 text-sm text-emerald-600 hover:text-emerald-700">
                            View Property
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
