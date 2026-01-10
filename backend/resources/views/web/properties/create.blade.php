@extends('layouts.app')

@section('title', isset($property) ? 'Edit Property' : 'Add Property')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-8">{{ isset($property) ? 'Edit Property' : 'Add New Property' }}</h1>

        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ isset($property) ? route('properties.update', $property) : route('properties.store') }}"
            method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($property))
                @method('PUT')
            @endif

            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Property Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Title English -->
                    <div>
                        <label class="block font-medium mb-2">Title (English) <span class="text-red-500">*</span></label>
                        <input type="text" name="title_en" value="{{ old('title_en', $property->title_en ?? '') }}" required
                            placeholder="3BR Villa in Al Malqa"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('title_en') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Title Arabic -->
                    <div>
                        <label class="block font-medium mb-2">Title (Arabic) <span class="text-red-500">*</span></label>
                        <input type="text" name="title_ar" value="{{ old('title_ar', $property->title_ar ?? '') }}" required
                            dir="rtl" placeholder="فيلا ٣ غرف نوم في الملقا"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('title_ar') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <!-- Type -->
                    <div>
                        <label class="block font-medium mb-2">Property Type <span class="text-red-500">*</span></label>
                        <select name="type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select Type</option>
                            <option value="villa" {{ old('type', $property->type ?? '') === 'villa' ? 'selected' : '' }}>Villa
                            </option>
                            <option value="apartment" {{ old('type', $property->type ?? '') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="compound" {{ old('type', $property->type ?? '') === 'compound' ? 'selected' : '' }}>Compound</option>
                            <option value="land" {{ old('type', $property->type ?? '') === 'land' ? 'selected' : '' }}>Land
                            </option>
                            <option value="office" {{ old('type', $property->type ?? '') === 'office' ? 'selected' : '' }}>
                                Office</option>
                        </select>
                        @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Purpose -->
                    <div>
                        <label class="block font-medium mb-2">Purpose <span class="text-red-500">*</span></label>
                        <select name="purpose" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select Purpose</option>
                            <option value="sale" {{ old('purpose', $property->purpose ?? '') === 'sale' ? 'selected' : '' }}>
                                For Sale</option>
                            <option value="rent" {{ old('purpose', $property->purpose ?? '') === 'rent' ? 'selected' : '' }}>
                                For Rent</option>
                        </select>
                        @error('purpose') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block font-medium mb-2">Price (SAR) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $property->price ?? '') }}" required
                            min="0" placeholder="1500000"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Location</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- City -->
                    <div>
                        <label class="block font-medium mb-2">City <span class="text-red-500">*</span></label>
                        <select name="city_id" id="city_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $property->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('city_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- District -->
                    <div>
                        <label class="block font-medium mb-2">District</label>
                        <select name="district_id" id="district_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select District</option>
                            @if(isset($districts))
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $property->district_id ?? '') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <!-- Property Specs -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Specifications</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-medium mb-2">Bedrooms</label>
                        <input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms ?? '') }}"
                            min="0" max="20"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-medium mb-2">Bathrooms</label>
                        <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms ?? '') }}"
                            min="0" max="20"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-medium mb-2">Area (m²)</label>
                        <input type="number" name="area_sqm" value="{{ old('area_sqm', $property->area_sqm ?? '') }}"
                            min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-medium mb-2">REGA Ad License</label>
                        <input type="text" name="rega_ad_license"
                            value="{{ old('rega_ad_license', $property->rega_ad_license ?? '') }}"
                            placeholder="Ad license number"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Description</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-2">Description (English)</label>
                        <textarea name="description_en" rows="5" placeholder="Describe the property in English..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description_en', $property->description_en ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block font-medium mb-2">Description (Arabic)</label>
                        <textarea name="description_ar" rows="5" dir="rtl" placeholder="وصف العقار بالعربية..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description_ar', $property->description_ar ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Virtual Tour -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">🎥 Virtual Tour (Optional)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-2">Virtual Tour URL</label>
                        <input type="url" name="virtual_tour_url"
                            value="{{ old('virtual_tour_url', $property->virtual_tour_url ?? '') }}"
                            placeholder="https://my.matterport.com/show/..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-medium mb-2">Tour Type</label>
                        <select name="virtual_tour_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select Type</option>
                            <option value="matterport" {{ old('virtual_tour_type', $property->virtual_tour_type ?? '') === 'matterport' ? 'selected' : '' }}>Matterport</option>
                            <option value="youtube_360" {{ old('virtual_tour_type', $property->virtual_tour_type ?? '') === 'youtube_360' ? 'selected' : '' }}>YouTube 360°</option>
                            <option value="custom" {{ old('virtual_tour_type', $property->virtual_tour_type ?? '') === 'custom' ? 'selected' : '' }}>Custom/Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Property Images</h2>

                <div
                    class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-emerald-500 transition-colors">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden"
                        onchange="previewImages(this)">
                    <label for="images" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-600">Click to upload images</p>
                        <p class="text-sm text-gray-400 mt-1">JPG, PNG (max 5MB each)</p>
                    </label>
                </div>
                <div id="image_preview" class="grid grid-cols-4 gap-4 mt-4"></div>
                @error('images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <div class="flex gap-4">
                <a href="{{ route('my-properties') }}"
                    class="px-6 py-4 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                    {{ isset($property) ? 'Update Property' : 'Submit for Review' }}
                </button>
            </div>

            <p class="text-center text-gray-500 text-sm">
                Your property will be reviewed before being published.
            </p>
        </form>
    </div>

    @push('scripts')
        <script>
            function previewImages(input) {
                const preview = document.getElementById('image_preview');
                preview.innerHTML = '';

                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-video rounded-lg overflow-hidden';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        </script>
    @endpush
@endsection