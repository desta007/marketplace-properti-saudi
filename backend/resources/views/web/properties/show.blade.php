@extends('layouts.app')

@section('title', $property->title)

@section('content')
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <a href="{{ route('properties.index') }}" class="text-emerald-600 hover:underline flex items-center gap-2">
                <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>{{ __('nav.properties') }}</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Image Gallery -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden" x-data="{ currentImage: 0 }">
                    <div class="relative h-96">
                        @if($property->images->count() > 0)
                            @foreach($property->images as $index => $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" x-show="currentImage === {{ $index }}"
                                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100" class="w-full h-full object-cover absolute inset-0"
                                    alt="{{ $property->title }}">
                            @endforeach

                            @if($property->images->count() > 1)
                                <!-- Navigation Arrows -->
                                <button
                                    @click="currentImage = currentImage > 0 ? currentImage - 1 : {{ $property->images->count() - 1 }}"
                                    class="absolute start-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur rounded-full flex items-center justify-center hover:bg-white transition-colors shadow-lg">
                                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button
                                    @click="currentImage = currentImage < {{ $property->images->count() - 1 }} ? currentImage + 1 : 0"
                                    class="absolute end-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur rounded-full flex items-center justify-center hover:bg-white transition-colors shadow-lg">
                                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <!-- Dots Indicator -->
                                <div class="absolute bottom-4 start-1/2 -translate-x-1/2 flex gap-2">
                                    @foreach($property->images as $index => $image)
                                        <button @click="currentImage = {{ $index }}"
                                            :class="currentImage === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                                            class="w-3 h-3 rounded-full transition-colors"></button>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span
                                    class="px-3 py-1 text-white text-sm font-bold rounded-full {{ $property->purpose === 'rent' ? 'bg-blue-600' : 'bg-emerald-600' }}">
                                    {{ $property->purpose === 'rent' ? __('purpose.rent') : __('purpose.sale') }}
                                </span>
                                @if($property->hasVirtualTour())
                                    <span class="px-3 py-1 bg-purple-600 text-white text-sm font-bold rounded-full">
                                        🎥 Virtual Tour
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-xl">No images available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if($property->images->count() > 1)
                        <div class="p-4 flex gap-2 overflow-x-auto">
                            @foreach($property->images as $index => $image)
                                <button @click="currentImage = {{ $index }}"
                                    :class="currentImage === {{ $index }} ? 'ring-2 ring-emerald-500' : ''"
                                    class="w-24 h-16 flex-shrink-0 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Property Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
                            <p class="text-gray-500 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span>{{ $property->city?->name }}, {{ $property->district?->name ?? '' }}</span>
                            </p>
                        </div>
                        @auth
                            <button onclick="toggleFavorite({{ $property->id }})"
                                class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 hover:bg-red-50 transition-colors favorite-btn"
                                data-property="{{ $property->id }}">
                                <svg class="w-6 h-6 {{ auth()->user()->favorites->contains($property->id) ? 'text-red-500 fill-current' : 'text-gray-600' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                                class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-100 hover:bg-red-50 transition-colors">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </a>
                        @endauth
                    </div>

                    <!-- Price -->
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 mb-6">
                        <p class="text-3xl font-bold text-emerald-600">
                            {{ number_format($property->price) }}
                            <span class="text-lg font-normal text-gray-600">SAR</span>
                            @if($property->purpose === 'rent')
                                <span class="text-lg font-normal text-gray-500">/ year</span>
                            @endif
                        </p>
                    </div>

                    <!-- Overview -->
                    <h2 class="text-lg font-bold mb-4">{{ __('detail.overview') }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">🛏️</div>
                            <p class="text-xl font-bold">{{ $property->bedrooms ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ __('property.bedrooms') }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">🚿</div>
                            <p class="text-xl font-bold">{{ $property->bathrooms ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ __('property.bathrooms') }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">📐</div>
                            <p class="text-xl font-bold">{{ $property->area_sqm ?? '-' }}</p>
                            <p class="text-sm text-gray-500">m²</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">🏠</div>
                            <p class="text-xl font-bold capitalize">{{ $property->type }}</p>
                            <p class="text-sm text-gray-500">{{ __('property.type') }}</p>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl mb-1">👁️</div>
                            <p class="text-xl font-bold">{{ number_format($property->view_count) }}</p>
                            <p class="text-sm text-gray-500">Views</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <h2 class="text-lg font-bold mb-4">{{ __('detail.description') }}</h2>
                    <p class="text-gray-600 leading-relaxed mb-6 whitespace-pre-line">
                        {{ $property->description ?? 'No description provided.' }}
                    </p>

                    <!-- Features -->
                    @if($property->features && count($property->features) > 0)
                        <h2 class="text-lg font-bold mb-4">{{ __('detail.features') }}</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->features as $feature)
                                <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                                    <span class="text-emerald-600">✓</span>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Virtual Tour -->
                    @if($property->hasVirtualTour())
                        <h2 class="text-lg font-bold mb-4 mt-6">🎥 Virtual Tour</h2>
                        <a href="{{ $property->virtual_tour_url }}" target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            View Virtual Tour
                        </a>
                    @endif
                </div>

                <!-- Map -->
                @if($property->latitude && $property->longitude)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h2 class="text-lg font-bold mb-4">{{ __('detail.location') }}</h2>
                        <div id="property-map" class="h-80 rounded-xl overflow-hidden"></div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Agent & Contact -->
            <div class="space-y-6">
                <!-- Agent Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-lg font-bold mb-4">{{ __('detail.agent') }}</h2>
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-2xl font-bold text-emerald-600">
                            {{ substr($property->user?->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">{{ $property->user?->name ?? 'Agent' }}</h3>
                            @if($property->user?->isVerifiedAgent())
                                <div class="flex items-center gap-1 text-emerald-600 text-sm mt-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                                    </svg>
                                    <span>REGA Verified</span>
                                </div>
                            @elseif($property->user?->isAgent())
                                <p class="text-gray-500 text-sm">Real Estate Agent</p>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        @if($property->user?->whatsapp_number)
                            <a href="{{ $property->user->whatsapp_url }}?text={{ urlencode('Hi, I am interested in: ' . $property->title . ' - ' . url()->current()) }}"
                                target="_blank"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-green-500 text-white font-medium rounded-xl hover:bg-green-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                <span>{{ __('detail.whatsapp') }}</span>
                            </a>
                        @endif

                        @if($property->user?->phone)
                            <a href="tel:{{ $property->user->phone }}"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ $property->user->phone }}</span>
                            </a>
                        @endif

                        <button onclick="document.getElementById('inquiry-modal').classList.remove('hidden')"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 border-2 border-emerald-600 text-emerald-600 font-medium rounded-xl hover:bg-emerald-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ __('detail.sendInquiry') }}</span>
                        </button>
                    </div>

                    <!-- REGA License -->
                    @if($property->rega_ad_license)
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-500">{{ __('detail.regaLicense') }}</p>
                            <p class="font-mono font-medium">{{ $property->rega_ad_license }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Similar Properties -->
        @if($similarProperties->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">{{ __('property.similar') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similarProperties as $similar)
                        <a href="{{ route('properties.show', $similar) }}"
                            class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                            <div class="relative h-48 overflow-hidden">
                                @if($similar->images->count() > 0)
                                    <img src="{{ asset('storage/' . $similar->images->first()->image_path) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No image</span>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="px-2 py-1 text-white text-xs font-bold rounded-full {{ $similar->purpose === 'rent' ? 'bg-blue-600' : 'bg-emerald-600' }}">
                                        {{ $similar->purpose === 'rent' ? __('purpose.rent') : __('purpose.sale') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 line-clamp-1">{{ $similar->title }}</h3>
                                <p class="text-gray-500 text-sm mb-2">{{ $similar->city?->name }}</p>
                                <p class="text-emerald-600 font-bold">{{ number_format($similar->price) }} SAR</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <!-- Inquiry Modal -->
    <div id="inquiry-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                onclick="document.getElementById('inquiry-modal').classList.add('hidden')"></div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="inquiry-form">
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <input type="hidden" name="source" value="form">
                    <div class="bg-white px-6 py-6">
                        <h3 class="text-xl font-bold mb-4">Send Inquiry</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                                <input type="text" name="seeker_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    value="{{ auth()->user()?->name ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="seeker_phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="+966 50 123 4567" value="{{ auth()->user()?->phone ?? '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea name="message" rows="3" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="I'm interested in this property...">Hi, I'm interested in this property: {{ $property->title }}</textarea>
                            </div>
                            <div id="inquiry-success" class="hidden p-4 bg-emerald-100 text-emerald-700 rounded-xl">
                                ✅ Inquiry sent successfully! The agent will contact you soon.
                            </div>
                            <div id="inquiry-error" class="hidden p-4 bg-red-100 text-red-700 rounded-xl">
                                ❌ Failed to send inquiry. Please try again.
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('inquiry-modal').classList.add('hidden')"
                            class="px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-100 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="inquiry-submit"
                            class="px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                            Send Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inquiryForm = document.getElementById('inquiry-form');
            const submitBtn = document.getElementById('inquiry-submit');
            const successDiv = document.getElementById('inquiry-success');
            const errorDiv = document.getElementById('inquiry-error');

            inquiryForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Hide previous messages
                successDiv.classList.add('hidden');
                errorDiv.classList.add('hidden');

                // Disable button
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';

                const formData = new FormData(inquiryForm);
                const data = {};
                formData.forEach((value, key) => data[key] = value);

                fetch('/api/leads', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        successDiv.classList.remove('hidden');
                        // Reset form fields except hidden ones
                        inquiryForm.querySelector('[name="seeker_name"]').value = '';
                        inquiryForm.querySelector('[name="seeker_phone"]').value = '';
                        inquiryForm.querySelector('[name="message"]').value = '';

                        // Close modal after 2 seconds
                        setTimeout(() => {
                            document.getElementById('inquiry-modal').classList.add('hidden');
                            successDiv.classList.add('hidden');
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        errorDiv.classList.remove('hidden');
                        if (error.message) {
                            errorDiv.textContent = '❌ ' + error.message;
                        }
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Send Inquiry';
                    });
            });
        });
    </script>

    @if($property->latitude && $property->longitude)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const map = L.map('property-map').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                L.marker([{{ $property->latitude }}, {{ $property->longitude }}]).addTo(map)
                    .bindPopup('{{ Str::limit($property->title, 50) }}')
                    .openPopup();
            });
        </script>
    @endif
@endpush