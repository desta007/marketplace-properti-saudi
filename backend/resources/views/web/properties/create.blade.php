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

                <!-- Coordinates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block font-medium mb-2">Latitude</label>
                        <input type="text" name="latitude" id="latitude"
                            value="{{ old('latitude', $property->latitude ?? '') }}" placeholder="24.7136"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block font-medium mb-2">Longitude</label>
                        <input type="text" name="longitude" id="longitude"
                            value="{{ old('longitude', $property->longitude ?? '') }}" placeholder="46.6753"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>

                <!-- Map Picker -->
                <div class="mt-4">
                    <label class="block font-medium mb-2">📍 Pin Location on Map (click to set coordinates)</label>
                    <div id="location-map" class="h-64 rounded-xl border border-gray-300 overflow-hidden"></div>
                    <p class="text-sm text-gray-500 mt-1">Click on the map to set the property location</p>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Property Features</h2>

                @php
                    $selectedFeatures = old('features', $property->features ?? []) ?: [];
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($features as $feature)
                        <label
                            class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="features[]" value="{{ $feature->name_en }}" {{ in_array($feature->name_en, $selectedFeatures) ? 'checked' : '' }}
                                class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <span class="text-sm">{{ $feature->icon }} {{ $feature->name }}</span>
                        </label>
                    @endforeach
                </div>

                @if($features->isEmpty())
                    <p class="text-gray-500 text-center py-4">No features available. Admin needs to add features first.</p>
                @endif
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
                <p class="text-gray-500 text-sm mb-4">You can select multiple images at once. First image will be the
                    primary/cover image.</p>

                <div id="upload-area"
                    class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-emerald-500 transition-colors cursor-pointer"
                    onclick="document.getElementById('images').click()">
                    <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/webp"
                        class="hidden" onchange="handleImageSelect(this)">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-600 font-medium">Click to upload images</p>
                    <p class="text-sm text-gray-400 mt-1">JPG, PNG, WebP (max 5MB each)</p>
                    <p class="text-sm text-emerald-600 mt-2 font-medium">📷 Select multiple images at once</p>
                </div>

                <div id="image_preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                <div id="image_count" class="text-center text-gray-500 text-sm mt-2 hidden"></div>

                <button type="button" id="add-more-btn" onclick="document.getElementById('images').click()"
                    class="hidden w-full mt-4 py-3 border-2 border-dashed border-emerald-300 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-colors font-medium">
                    ➕ Add More Images
                </button>

                @error('images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                @error('images.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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
            // Store all selected files
            let selectedFiles = [];

            function handleImageSelect(input) {
                const newFiles = Array.from(input.files);

                // Add new files to array (avoid duplicates by name)
                newFiles.forEach(file => {
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) {
                        selectedFiles.push(file);
                    }
                });

                updateImagePreview();
                updateFileInput();
            }

            function updateImagePreview() {
                const preview = document.getElementById('image_preview');
                const countDiv = document.getElementById('image_count');
                const addMoreBtn = document.getElementById('add-more-btn');
                const uploadArea = document.getElementById('upload-area');

                preview.innerHTML = '';

                if (selectedFiles.length > 0) {
                    countDiv.classList.remove('hidden');
                    countDiv.textContent = `${selectedFiles.length} image${selectedFiles.length > 1 ? 's' : ''} selected`;
                    addMoreBtn.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
                } else {
                    countDiv.classList.add('hidden');
                    addMoreBtn.classList.add('hidden');
                    uploadArea.classList.remove('hidden');
                }

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const div = document.createElement('div');
                        div.className = 'relative aspect-video rounded-xl overflow-hidden shadow-md group';
                        div.innerHTML = `
                                            <img src="${e.target.result}" class="w-full h-full object-cover">
                                            ${index === 0 ? '<span class="absolute top-2 left-2 bg-emerald-500 text-white text-xs px-2 py-1 rounded-full font-medium">Primary</span>' : ''}
                                            <button type="button" onclick="removeImage(${index})" 
                                                class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                                ✕
                                            </button>
                                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                                <p class="text-white text-xs truncate">${file.name}</p>
                                            </div>
                                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function removeImage(index) {
                selectedFiles.splice(index, 1);
                updateImagePreview();
                updateFileInput();
            }

            function updateFileInput() {
                const input = document.getElementById('images');
                const dataTransfer = new DataTransfer();

                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });

                input.files = dataTransfer.files;
            }

            // Load districts when city is selected and initialize map
            document.addEventListener('DOMContentLoaded', function () {
                const citySelect = document.getElementById('city_id');
                const districtSelect = document.getElementById('district_id');
                const currentDistrictId = '{{ old('district_id', $property->district_id ?? '') }}';

                citySelect.addEventListener('change', function () {
                    loadDistricts(this.value);
                });

                // Load districts for initially selected city (for edit mode)
                if (citySelect.value) {
                    loadDistricts(citySelect.value, currentDistrictId);
                }

                function loadDistricts(cityId, selectedDistrictId = null) {
                    districtSelect.innerHTML = '<option value="">Select District</option>';

                    if (!cityId) return;

                    districtSelect.innerHTML = '<option value="">Loading...</option>';
                    districtSelect.disabled = true;

                    fetch(`/api/cities/${cityId}/districts`)
                        .then(response => response.json())
                        .then(data => {
                            districtSelect.innerHTML = '<option value="">Select District</option>';

                            const districts = data.data || data;
                            districts.forEach(district => {
                                const option = document.createElement('option');
                                option.value = district.id;
                                option.textContent = district.name || district.name_en;
                                if (selectedDistrictId && district.id == selectedDistrictId) {
                                    option.selected = true;
                                }
                                districtSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error loading districts:', error);
                            districtSelect.innerHTML = '<option value="">Failed to load districts</option>';
                        })
                        .finally(() => {
                            districtSelect.disabled = false;
                        });
                }

                // Initialize map for location picker
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');

                // Default to Riyadh center
                let lat = parseFloat(latInput.value) || 24.7136;
                let lng = parseFloat(lngInput.value) || 46.6753;

                const map = L.map('location-map').setView([lat, lng], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                let marker = null;

                // If there are existing coordinates, show marker
                if (latInput.value && lngInput.value) {
                    marker = L.marker([lat, lng]).addTo(map);
                }

                // Click on map to set location
                map.on('click', function (e) {
                    const { lat, lng } = e.latlng;

                    latInput.value = lat.toFixed(8);
                    lngInput.value = lng.toFixed(8);

                    if (marker) {
                        marker.setLatLng(e.latlng);
                    } else {
                        marker = L.marker(e.latlng).addTo(map);
                    }
                });

                // Update marker when inputs change manually
                function updateMarkerFromInputs() {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        if (marker) {
                            marker.setLatLng([lat, lng]);
                        } else {
                            marker = L.marker([lat, lng]).addTo(map);
                        }
                        map.setView([lat, lng], 15);
                    }
                }

                latInput.addEventListener('change', updateMarkerFromInputs);
                lngInput.addEventListener('change', updateMarkerFromInputs);
            });
        </script>
    @endpush
@endsection