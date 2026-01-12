@extends('layouts.app')

@section('title', 'Buy Listing Credits')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('subscription.index') }}"
                class="text-emerald-600 hover:text-emerald-700 flex items-center gap-2 mb-4">
                ← Back to Subscription
            </a>
            <h1 class="text-3xl font-bold text-gray-900">📦 Buy Listing Credits</h1>
            <p class="text-gray-600 mt-1">Purchase credits to post more property listings</p>
        </div>

        <!-- Current Credits -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-amber-700">Current Credits</div>
                    <div class="text-3xl font-bold text-amber-800">{{ $currentCredits }}</div>
                </div>
                <span class="text-4xl">📦</span>
            </div>
        </div>

        <!-- Pricing Info -->
        <div class="bg-white rounded-2xl shadow-sm border p-6 mb-8">
            <h3 class="font-bold text-gray-900 mb-4">Pricing</h3>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div>
                    <div class="font-bold text-lg">SAR {{ $creditPrice }}</div>
                    <div class="text-gray-600 text-sm">per listing credit</div>
                </div>
                <div class="text-gray-600">
                    1 credit = 1 additional listing
                </div>
            </div>
            <p class="text-gray-500 text-sm mt-3">Credits expire in 6 months from purchase date</p>
        </div>

        <!-- Purchase Form -->
        <div class="bg-white rounded-2xl shadow-sm border p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Purchase Credits</h3>

            <form action="{{ route('subscription.submit-credits') }}" method="POST">
                @csrf

                <!-- Credit Count -->
                <div class="mb-6">
                    <label for="credit_count" class="block text-sm font-medium text-gray-700 mb-2">
                        Number of Credits
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="number" name="credit_count" id="credit_count" value="{{ old('credit_count', 5) }}"
                            min="1" max="100"
                            class="w-32 px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-0 text-center text-lg font-bold"
                            onchange="updateTotal(this.value)">
                        <div class="text-gray-600">
                            × SAR {{ $creditPrice }} = <span id="total-amount" class="font-bold text-emerald-600">SAR
                                {{ $creditPrice * 5 }}</span>
                        </div>
                    </div>
                    @error('credit_count')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quick Select -->
                <div class="mb-6">
                    <div class="text-sm text-gray-600 mb-2">Quick select:</div>
                    <div class="flex flex-wrap gap-2">
                        @foreach([5, 10, 20, 50] as $amount)
                            <button type="button"
                                onclick="document.getElementById('credit_count').value={{ $amount }}; updateTotal({{ $amount }})"
                                class="px-4 py-2 border-2 border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition font-medium">
                                {{ $amount }} credits
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method</label>
                    <div class="space-y-3">
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
                </div>

                <!-- Bank Details -->
                <div class="mb-6 p-4 bg-blue-50 rounded-xl">
                    <h4 class="font-medium text-blue-900 mb-2">📋 Bank Transfer Details</h4>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p><strong>Bank:</strong> Al Rajhi Bank</p>
                        <p><strong>Account Name:</strong> SaudiProp Real Estate</p>
                        <p><strong>Account Number:</strong> SA0380000000608010167519</p>
                    </div>
                </div>

                <!-- Reference Number -->
                <div class="mb-6">
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Transfer Reference Number (Optional)
                    </label>
                    <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-0"
                        placeholder="Enter bank transfer reference">
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition text-lg">
                    Purchase Credits
                </button>

                <p class="text-center text-gray-500 text-sm mt-4">
                    Credits will be added after admin confirms payment
                </p>
            </form>
        </div>
    </div>

    <script>
        function updateTotal(count) {
            const price = {{ $creditPrice }};
            const total = count * price;
            document.getElementById('total-amount').textContent = 'SAR ' + total;
        }
    </script>
@endsection