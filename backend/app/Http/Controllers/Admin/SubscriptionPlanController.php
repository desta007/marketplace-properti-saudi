<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of subscription plans
     */
    public function index()
    {
        $plans = SubscriptionPlan::ordered()->paginate(20);
        return view('admin.subscription-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new plan
     */
    public function create()
    {
        return view('admin.subscription-plans.form');
    }

    /**
     * Store a newly created plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'required|string|max:50|unique:subscription_plans,slug',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'listing_limit' => 'nullable|integer|min:1',
            'featured_quota_monthly' => 'nullable|integer|min:0',
            'featured_credit_days' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['featured_quota_monthly'] = $validated['featured_quota_monthly'] ?? 0;
        $validated['featured_credit_days'] = $validated['featured_credit_days'] ?? 7;

        // Handle features as JSON if provided
        if ($request->has('features_list')) {
            $features = array_filter(array_map('trim', explode("\n", $request->features_list)));
            $validated['features'] = $features;
        }

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully!');
    }

    /**
     * Show the form for editing a plan
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plans.form', ['plan' => $subscriptionPlan]);
    }

    /**
     * Update the specified plan
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'required|string|max:50|unique:subscription_plans,slug,' . $subscriptionPlan->id,
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'listing_limit' => 'nullable|integer|min:1',
            'featured_quota_monthly' => 'nullable|integer|min:0',
            'featured_credit_days' => 'nullable|integer|min:1',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['featured_quota_monthly'] = $validated['featured_quota_monthly'] ?? 0;
        $validated['featured_credit_days'] = $validated['featured_credit_days'] ?? 7;

        // Handle features as JSON if provided
        if ($request->has('features_list')) {
            $features = array_filter(array_map('trim', explode("\n", $request->features_list)));
            $validated['features'] = $features;
        }

        $subscriptionPlan->update($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully!');
    }

    /**
     * Remove the specified plan
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        // Check if plan has active subscriptions
        if ($subscriptionPlan->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->route('admin.subscription-plans.index')
                ->with('error', 'Cannot delete plan with active subscriptions!');
        }

        $subscriptionPlan->delete();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully!');
    }

    /**
     * Toggle the active status of a plan
     */
    public function toggleActive(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->update(['is_active' => !$subscriptionPlan->is_active]);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Plan status updated!');
    }
}
