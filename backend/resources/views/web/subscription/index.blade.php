@extends('layouts.app')

@section('title', 'My Subscription')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">💳 My Subscription</h1>
            <p class="text-gray-600 mt-1">Manage your subscription plan and listing quota</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 text-emerald-800 rounded-xl flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-xl flex items-center gap-2">
                ❌ {{ session('error') }}
            </div>
        @endif

        <!-- Quota Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                <div class="text-3xl font-bold text-emerald-600">
                    {{ $quotaStats['remaining_slots'] === PHP_INT_MAX ? '∞' : $quotaStats['remaining_slots'] }}</div>
                <div class="text-gray-600 text-sm mt-1">Remaining Slots</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                <div class="text-3xl font-bold text-gray-900">{{ $quotaStats['used_slots'] }}</div>
                <div class="text-gray-600 text-sm mt-1">Active Listings</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                <div class="text-3xl font-bold text-amber-600">{{ $quotaStats['available_credits'] }}</div>
                <div class="text-gray-600 text-sm mt-1">Listing Credits</div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $quotaStats['featured_credits'] }}</div>
                <div class="text-gray-600 text-sm mt-1">Featured Credits</div>
            </div>
        </div>

        <!-- Current Subscription / Pending -->
        @if($pendingSubscription)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">⏳</span>
                    <div>
                        <h3 class="font-bold text-amber-800">Pending Subscription Request</h3>
                        <p class="text-amber-700">You have requested <strong>{{ $pendingSubscription->plan->name_en }}</strong>
                            plan. Waiting for admin approval.</p>
                    </div>
                </div>
            </div>
        @elseif($activeSubscription)
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 rounded-2xl p-8 mb-8 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="text-sm opacity-80 uppercase tracking-wide mb-1">Current Plan</div>
                        <h2 class="text-3xl font-bold">{{ $activeSubscription->plan->name_en }}</h2>
                        <p class="opacity-80 mt-2">
                            @if($activeSubscription->ends_at)
                                Expires: {{ $activeSubscription->ends_at->format('M d, Y') }}
                                ({{ $activeSubscription->daysRemaining() }} days left)
                            @else
                                Active
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">SAR {{ number_format($activeSubscription->plan->price_monthly, 0) }}
                        </div>
                        <div class="text-sm opacity-80">per month</div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-2xl p-8 mb-8 text-center">
                <span class="text-5xl mb-4 block">🎁</span>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Free Plan ({{ $quotaStats['listing_limit'] ?? 2 }} listings)
                </h3>
                <p class="text-gray-600 mb-4">Upgrade to get more listings and featured credits</p>
                <a href="#plans"
                    class="inline-flex px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">
                    View Plans ↓
                </a>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('subscription.buy-credits') }}"
                class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        📦
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Buy Listing Credits</h3>
                        <p class="text-gray-600 text-sm">SAR 25/credit - Post more listings</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('my-properties') }}"
                class="bg-white rounded-2xl shadow-sm border p-6 hover:shadow-md transition group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🚀
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Boost Property</h3>
                        <p class="text-gray-600 text-sm">Feature your listing for more visibility</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Available Plans -->
        <div id="plans" class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Plans</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($plans as $plan)
                    <div
                        class="bg-white rounded-2xl shadow-sm border p-6 {{ $plan->slug === 'professional' ? 'ring-2 ring-emerald-500 relative' : '' }}">
                        @if($plan->slug === 'professional')
                            <div
                                class="absolute -top-3 left-1/2 -translate-x-1/2 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                POPULAR
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name_en }}</h3>
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-emerald-600">
                                @if($plan->price_monthly == 0)
                                    Free
                                @else
                                    SAR {{ number_format($plan->price_monthly, 0) }}
                                @endif
                            </span>
                            @if($plan->price_monthly > 0)
                                <span class="text-gray-500">/month</span>
                            @endif
                        </div>
                        <ul class="space-y-2 mb-6 text-sm">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500">✓</span>
                                {{ $plan->listing_limit ? $plan->listing_limit . ' listings' : 'Unlimited listings' }}
                            </li>
                            @if($plan->featured_quota_monthly > 0)
                                <li class="flex items-center gap-2">
                                    <span class="text-emerald-500">✓</span>
                                    {{ $plan->featured_quota_monthly }} featured/month
                                </li>
                            @endif
                            @if($plan->features)
                                @foreach(array_slice($plan->features, 0, 3) as $feature)
                                    <li class="flex items-center gap-2">
                                        <span class="text-emerald-500">✓</span>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        @if($activeSubscription && $activeSubscription->plan->id === $plan->id)
                            <button disabled
                                class="w-full py-3 bg-gray-200 text-gray-600 font-semibold rounded-xl cursor-not-allowed">
                                Current Plan
                            </button>
                        @elseif($plan->price_monthly == 0)
                            <span class="block text-center text-gray-500 text-sm py-3">Default plan</span>
                        @else
                            <a href="{{ route('subscription.subscribe', $plan) }}"
                                class="block w-full py-3 text-center bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">
                                Subscribe
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Transactions -->
        @if($transactions->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Recent Transactions</h2>
                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $transaction->type_label }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        SAR {{ number_format($transaction->amount, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium {{ $transaction->status_badge_class }}">
                                            {{ $transaction->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection