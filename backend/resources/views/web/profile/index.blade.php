@extends('layouts.app')

@section('title', __('nav.profile'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">{{ __('nav.profile') }}</h1>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar -->
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center text-3xl font-bold text-emerald-600">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-lg">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-500">{{ auth()->user()->email }}</p>
                        @if(auth()->user()->isAgent())
                            <span
                                class="inline-flex items-center gap-1 text-sm {{ auth()->user()->isVerifiedAgent() ? 'text-emerald-600' : 'text-amber-600' }}">
                                @if(auth()->user()->isVerifiedAgent())
                                    ✓ Verified Agent
                                @else
                                    ⏳ Pending Verification
                                @endif
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Name -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email (readonly) -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500">
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                        placeholder="+966 50 123 4567"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- WhatsApp -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">WhatsApp Number</label>
                    <input type="tel" name="whatsapp_number"
                        value="{{ old('whatsapp_number', auth()->user()->whatsapp_number) }}" placeholder="+966 50 123 4567"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('whatsapp_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Language -->
                <div class="mb-6">
                    <label class="block font-medium mb-2">Preferred Language</label>
                    <select name="language"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="en" {{ auth()->user()->language === 'en' ? 'selected' : '' }}>English</option>
                        <option value="ar" {{ auth()->user()->language === 'ar' ? 'selected' : '' }}>العربية</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Become an Agent Section -->
        @if(!auth()->user()->isAgent())
            <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                <h2 class="text-xl font-bold mb-4">🏢 Become an Agent</h2>
                <p class="text-gray-600 mb-4">Register as a property agent to list and sell properties. You'll need a valid REGA
                    license.</p>
                <a href="{{ route('agent.register') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Register as Agent →
                </a>
            </div>
        @endif
    </div>
@endsection