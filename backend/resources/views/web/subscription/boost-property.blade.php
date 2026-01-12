@extends('layouts.app')

@section('title', 'Boost Property')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('my-properties') }}"
                class="text-emerald-600 hover:text-emerald-700 flex items-center gap-2 mb-4">
                ← Back to My Properties
            </a>
            <h1 class="text-3xl font-bold text-gray-900">🚀 Boost Property</h1>
            <p class="text-gray-600 mt-1">Feature your listing for increased visibility</p>
        </div>

        <!-- Property Preview -->
        <div class="bg-white rounded-2xl shadow-sm border p-6 mb-8">
            <div class="flex gap-4">
                @if($property->images->count() > 0)
                    <img src="{{ Storage::url($property->images->first()->image_path) }}" alt="{{ $property->title }}"
                        class="w-32 h-24 object-cover rounded-xl">
                @else
                    <div class="w-32 h-24 bg-gray-200 rounded-xl flex items-center justify-center">
                        <span class="text-gray-400">No image</span>
                    </div>
                @endif
                <div>
                    <h3 class="font-bold text-lg text-gray-900">{{ $property->title }}</h3>
                    <p class="text-gray-600">{{ $property->city?->name }} • {{ $property->type }}</p>
                    <p class="text-emerald-600 font-bold mt-1">SAR {{ number_format($property->price, 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Free Credits Notice -->
        @if($featuredCredits > 0)
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6 mb-8">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🎁</span>
                    <div>
                        <h3 class="font-bold text-purple-800">You have {{ $featuredCredits }} free featured credit(s)!</h3>
                        <p class="text-purple-700">Use it to boost this property for 7 days at no cost.</p>
                    </div>
                </div>
                <form action="{{ route('subscription.submit-boost', $property->id) }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="use_credit" value="1">
                    <input type="hidden" name="boost_package_id" value="{{ $packages->flatten()->first()->id ?? '' }}">
                    <button type="submit"
                        class="px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition">
                        Use Free Credit
                    </button>
                </form>
            </div>
        @endif

        <!-- Boost Packages -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Choose Boost Package</h2>

        <form action="{{ route('subscription.submit-boost', $property->id) }}" method="POST" id="boost-form">
            @csrf

            @foreach(['premium' => ['icon' => '👑', 'label' => 'Premium', 'desc' => 'Highest visibility - Top of all listings', 'color' => 'amber'], 'top_pick' => ['icon' => '🔥', 'label' => 'Top Pick', 'desc' => 'High visibility - Featured section', 'color' => 'purple'], 'featured' => ['icon' => '⭐', 'label' => 'Featured', 'desc' => 'Enhanced visibility - Featured badge', 'color' => 'blue']] as $type => $info)
                @if(isset($packages[$type]) && $packages[$type]->count() > 0)
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-2xl">{{ $info['icon'] }}</span>
                            <h3 class="font-bold text-lg text-gray-900">{{ $info['label'] }}</h3>
                            <span class="text-gray-500 text-sm">- {{ $info['desc'] }}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($packages[$type] as $package)
                                <label
                                    class="bg-white rounded-xl border-2 p-4 cursor-pointer hover:border-{{ $info['color'] }}-500 transition package-option"
                                    data-package="{{ $package->id }}" data-price="{{ $package->price }}">
                                    <input type="radio" name="boost_package_id" value="{{ $package->id }}" class="hidden">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-bold">{{ $package->duration_days }} Days</div>
                                            <div class="text-gray-500 text-sm">{{ $package->name_en }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-emerald-600">SAR {{ number_format($package->price, 0) }}</div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Payment Method -->
            <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6" id="payment-section" style="display: none;">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Payment Details</h3>

                <div class="mb-4">
                    <div class="text-gray-600">Selected Package: <span id="selected-package-name" class="font-bold"></span>
                    </div>
                    <div class="text-2xl font-bold text-emerald-600">Total: <span id="selected-package-price">SAR 0</span>
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <label
                        class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-emerald-500 transition border-gray-200">
                        <input type="radio" name="payment_method" value="bank_transfer" class="mr-3" checked>
                        <div>
                            <div class="font-medium">🏦 Bank Transfer</div>
                        </div>
                    </label>
                    <label
                        class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-emerald-500 transition border-gray-200">
                        <input type="radio" name="payment_method" value="cash" class="mr-3">
                        <div>
                            <div class="font-medium">💵 Cash Payment</div>
                        </div>
                    </label>
                </div>

                <div class="mb-4 p-4 bg-blue-50 rounded-xl">
                    <h4 class="font-medium text-blue-900 mb-2">📋 Bank Transfer Details</h4>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p><strong>Bank:</strong> Al Rajhi Bank</p>
                        <p><strong>Account:</strong> SA0380000000608010167519</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Reference Number (Optional)
                    </label>
                    <input type="text" name="reference_number" id="reference_number"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-0"
                        placeholder="Enter after completing transfer">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition text-lg">
                    Submit Boost Request
                </button>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.package-option').forEach(option => {
            option.addEventListener('click', function () {
                // Remove selection from all
                document.querySelectorAll('.package-option').forEach(o => {
                    o.classList.remove('border-emerald-500', 'bg-emerald-50');
                    o.classList.add('border-gray-200');
                });

                // Select this one
                this.classList.remove('border-gray-200');
                this.classList.add('border-emerald-500', 'bg-emerald-50');
                this.querySelector('input[type="radio"]').checked = true;

                // Show payment section
                document.getElementById('payment-section').style.display = 'block';
                document.getElementById('selected-package-price').textContent = 'SAR ' + this.dataset.price;
            });
        });
    </script>
@endsection