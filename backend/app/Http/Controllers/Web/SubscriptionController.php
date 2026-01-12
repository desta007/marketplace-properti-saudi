<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\BoostPackage;
use App\Models\ListingCredit;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Show agent subscription status and available plans
     */
    public function index()
    {
        $user = auth()->user();
        $activeSubscription = $user->activeSubscription;
        $plans = SubscriptionPlan::active()->ordered()->get();

        // Calculate quota stats
        $quotaStats = [
            'listing_limit' => $user->getListingLimit(),
            'used_slots' => $user->properties()->whereIn('status', ['active', 'pending'])->count(),
            'remaining_slots' => $user->getRemainingListingSlots(),
            'can_create' => $user->canCreateListing(),
            'available_credits' => $user->getAvailableListingCredits(),
            'featured_credits' => $user->getRemainingFeaturedCredits(),
        ];

        // Transaction history
        $transactions = $user->transactions()->latest()->take(5)->get();

        // Pending subscriptions
        $pendingSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('web.subscription.index', compact(
            'user',
            'activeSubscription',
            'plans',
            'quotaStats',
            'transactions',
            'pendingSubscription'
        ));
    }

    /**
     * Show subscription request form
     */
    public function subscribe(SubscriptionPlan $plan)
    {
        $user = auth()->user();

        // Check if user already has active subscription
        if ($user->activeSubscription) {
            return redirect()->route('subscription.index')
                ->with('error', 'You already have an active subscription.');
        }

        // Check if user has pending subscription
        $pending = Subscription::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return redirect()->route('subscription.index')
                ->with('error', 'You have a pending subscription request. Please wait for admin approval.');
        }

        return view('web.subscription.subscribe', compact('plan'));
    }

    /**
     * Submit subscription request
     */
    public function submitSubscription(Request $request, SubscriptionPlan $plan)
    {
        $user = auth()->user();

        $request->validate([
            'payment_method' => 'required|in:bank_transfer,cash',
            'reference_number' => 'nullable|string|max:100',
        ]);

        // Create transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => Transaction::TYPE_SUBSCRIPTION,
            'amount' => $plan->price_monthly,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'notes' => "Subscription request for {$plan->name_en} plan",
        ]);

        // Create pending subscription
        Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'pending',
            'featured_credits_remaining' => 0, // Will be set when approved
        ]);

        return redirect()->route('subscription.index')
            ->with('success', 'Subscription request submitted! Please complete the payment and wait for admin approval.');
    }

    /**
     * Show listing credits purchase page
     */
    public function buyCredits()
    {
        $user = auth()->user();
        $creditPrice = 25; // SAR per credit
        $currentCredits = $user->getAvailableListingCredits();

        return view('web.subscription.buy-credits', compact('user', 'creditPrice', 'currentCredits'));
    }

    /**
     * Submit credit purchase request
     */
    public function submitCreditPurchase(Request $request)
    {
        $user = auth()->user();
        $creditPrice = 25;

        $request->validate([
            'credit_count' => 'required|integer|min:1|max:100',
            'payment_method' => 'required|in:bank_transfer,cash',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $totalAmount = $request->credit_count * $creditPrice;

        // Create transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => Transaction::TYPE_LISTING_CREDIT,
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'notes' => "Purchase of {$request->credit_count} listing credits",
        ]);

        return redirect()->route('subscription.index')
            ->with('success', 'Credit purchase request submitted! Credits will be added after admin confirms payment.');
    }

    /**
     * Show boost packages for property
     */
    public function boostProperty($propertyId)
    {
        $user = auth()->user();
        $property = $user->properties()->findOrFail($propertyId);

        // Check if property already has active boost
        if ($property->isFeatured()) {
            return redirect()->route('my-properties')
                ->with('error', 'This property is already featured.');
        }

        $packages = BoostPackage::active()->ordered()->get()->groupBy('boost_type');
        $featuredCredits = $user->getRemainingFeaturedCredits();

        return view('web.subscription.boost-property', compact('property', 'packages', 'featuredCredits'));
    }

    /**
     * Submit boost request
     */
    public function submitBoost(Request $request, $propertyId)
    {
        $user = auth()->user();
        $property = $user->properties()->findOrFail($propertyId);

        $request->validate([
            'boost_package_id' => 'required|exists:boost_packages,id',
            'use_credit' => 'nullable|boolean',
            'payment_method' => 'nullable|in:bank_transfer,cash',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $package = BoostPackage::findOrFail($request->boost_package_id);

        // Check if using subscription credit
        if ($request->use_credit && $user->getRemainingFeaturedCredits() > 0) {
            // Use subscription credit
            if ($user->useFeaturedCredit()) {
                // Create boost directly
                $property->boosts()->create([
                    'boost_type' => 'featured', // Credits only give featured type
                    'starts_at' => now(),
                    'ends_at' => now()->addDays($user->activeSubscription->plan->featured_credit_days ?? 7),
                    'is_active' => true,
                ]);

                return redirect()->route('my-properties')
                    ->with('success', 'Property boosted successfully using subscription credit!');
            }
        }

        // Create transaction for paid boost
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => Transaction::TYPE_FEATURED_BOOST,
            'amount' => $package->price,
            'status' => 'pending',
            'payment_method' => $request->payment_method ?? 'bank_transfer',
            'reference_number' => $request->reference_number,
            'notes' => "Boost for property #{$property->id} - {$package->name_en}",
        ]);

        // Create pending boost
        $property->boosts()->create([
            'transaction_id' => $transaction->id,
            'boost_type' => $package->boost_type,
            'starts_at' => now(),
            'ends_at' => now()->addDays($package->duration_days),
            'is_active' => false, // Will be activated when payment confirmed
        ]);

        return redirect()->route('my-properties')
            ->with('success', 'Boost request submitted! Property will be featured after admin confirms payment.');
    }
}
