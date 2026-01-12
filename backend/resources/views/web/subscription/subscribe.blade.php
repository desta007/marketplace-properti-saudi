@extends('layouts.app')

@section('title', 'Subscribe to ' . $plan->name_en)

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('subscription.index') }}"
                class="text-emerald-600 hover:text-emerald-700 flex items-center gap-2 mb-4">
                ← Back to Subscription
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Subscribe to {{ $plan->name_en }}</h1>
        </div>

        <!-- Plan Summary -->
        <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 rounded-2xl p-8 mb-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">{{ $plan->name_en }}</h2>
                    <p class="opacity-80 mt-1">{{ $plan->description_en }}</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">SAR {{ number_format($plan->price_monthly, 0) }}</div>
                    <div class="text-sm opacity-80">per month</div>
                </div>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                <div class="bg-white/20 rounded-lg px-3 py-1.5 text-sm">
                    {{ $plan->listing_limit ? $plan->listing_limit . ' listings' : 'Unlimited listings' }}
                </div>
                @if($plan->featured_quota_monthly > 0)
                    <div class="bg-white/20 rounded-lg px-3 py-1.5 text-sm">
                        {{ $plan->featured_quota_monthly }} featured credits/month
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Form -->
        <div class="bg-white rounded-2xl shadow-sm border p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Payment Details</h3>

            <form action="{{ route('subscription.submit', $plan) }}" method="POST">
                @csrf

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Payment Method</label>
                    <div class="space-y-3">
                        <label
                            class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-emerald-500 transition {{ old('payment_method') === 'bank_transfer' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200' }}">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3" checked>
                            <div>
                                <div class="font-medium">🏦 Bank Transfer</div>
                                <div class="text-sm text-gray-500">Transfer to our bank account</div>
                            </div>
                        </label>
                        <label
                            class="flex items-center p-4 border-2 rounded-xl cursor-pointer hover:border-emerald-500 transition {{ old('payment_method') === 'cash' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200' }}">
                            <input type="radio" name="payment_method" value="cash" class="mr-3">
                            <div>
                                <div class="font-medium">💵 Cash Payment</div>
                                <div class="text-sm text-gray-500">Pay at our office</div>
                            </div>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bank Details -->
                <div class="mb-6 p-4 bg-blue-50 rounded-xl" id="bank-details">
                    <h4 class="font-medium text-blue-900 mb-2">📋 Bank Transfer Details</h4>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p><strong>Bank:</strong> Al Rajhi Bank</p>
                        <p><strong>Account Name:</strong> SaudiProp Real Estate</p>
                        <p><strong>Account Number:</strong> SA0380000000608010167519</p>
                        <p><strong>Amount:</strong> SAR {{ number_format($plan->price_monthly, 2) }}</p>
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
                    <p class="text-gray-500 text-sm mt-1">Enter after completing bank transfer</p>
                    @error('reference_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition text-lg">
                    Submit Subscription Request
                </button>

                <p class="text-center text-gray-500 text-sm mt-4">
                    Your subscription will be activated after admin confirms payment
                </p>
            </form>
        </div>
    </div>
@endsection