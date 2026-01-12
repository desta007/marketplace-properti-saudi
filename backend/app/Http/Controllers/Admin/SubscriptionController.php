<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'plan', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by plan
        if ($request->filled('plan_id')) {
            $query->where('subscription_plan_id', $request->plan_id);
        }

        $subscriptions = $query->latest()->paginate(20);
        $plans = SubscriptionPlan::active()->ordered()->get();

        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::active()->count(),
            'pending' => Subscription::pending()->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'plans', 'stats'));
    }

    /**
     * Display the specified subscription
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'plan', 'approvedBy']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Approve a pending subscription
     */
    public function approve(Request $request, Subscription $subscription)
    {
        if ($subscription->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending subscriptions can be approved!');
        }

        $subscription->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'featured_credits_remaining' => $subscription->plan->featured_quota_monthly,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Also confirm any related pending transaction
        Transaction::where('user_id', $subscription->user_id)
            ->where('type', 'subscription')
            ->where('status', 'pending')
            ->latest()
            ->first()
                ?->update([
                'status' => 'confirmed',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        return redirect()->back()
            ->with('success', 'Subscription approved successfully!');
    }

    /**
     * Reject a pending subscription
     */
    public function reject(Request $request, Subscription $subscription)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        if ($subscription->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending subscriptions can be rejected!');
        }

        $subscription->update([
            'status' => 'cancelled',
            'notes' => $request->rejection_reason,
        ]);

        // Also reject any related pending transaction
        Transaction::where('user_id', $subscription->user_id)
            ->where('type', 'subscription')
            ->where('status', 'pending')
            ->latest()
            ->first()
                ?->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'notes' => $request->rejection_reason,
            ]);

        return redirect()->back()
            ->with('success', 'Subscription rejected!');
    }

    /**
     * Extend an active subscription
     */
    public function extend(Request $request, Subscription $subscription)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:12',
        ]);

        if ($subscription->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Only active subscriptions can be extended!');
        }

        $newEndDate = $subscription->ends_at
            ? $subscription->ends_at->addMonths($request->months)
            : now()->addMonths($request->months);

        // Add additional featured credits
        $additionalCredits = $subscription->plan->featured_quota_monthly * $request->months;

        $subscription->update([
            'ends_at' => $newEndDate,
            'featured_credits_remaining' => $subscription->featured_credits_remaining + $additionalCredits,
        ]);

        return redirect()->back()
            ->with('success', "Subscription extended by {$request->months} month(s)!");
    }

    /**
     * Cancel an active subscription
     */
    public function cancel(Request $request, Subscription $subscription)
    {
        if (!in_array($subscription->status, ['active', 'pending'])) {
            return redirect()->back()
                ->with('error', 'Only active or pending subscriptions can be cancelled!');
        }

        $subscription->update([
            'status' => 'cancelled',
            'notes' => $request->cancellation_reason ?? 'Cancelled by admin',
        ]);

        return redirect()->back()
            ->with('success', 'Subscription cancelled!');
    }
}
