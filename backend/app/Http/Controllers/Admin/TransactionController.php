<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Subscription;
use App\Models\ListingCredit;
use App\Models\PropertyBoost;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->latest()->paginate(20);

        $stats = [
            'total' => Transaction::count(),
            'pending' => Transaction::pending()->count(),
            'confirmed' => Transaction::confirmed()->count(),
            'total_revenue' => Transaction::confirmed()->sum('amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Display the specified transaction
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'approvedBy']);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Confirm a pending transaction
     */
    public function confirm(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending transactions can be confirmed!');
        }

        $transaction->update([
            'status' => 'confirmed',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->notes ?? $transaction->notes,
        ]);

        // Process based on transaction type
        $this->processConfirmedTransaction($transaction);

        return redirect()->back()
            ->with('success', 'Transaction confirmed successfully!');
    }

    /**
     * Reject a pending transaction
     */
    public function reject(Request $request, Transaction $transaction)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        if ($transaction->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending transactions can be rejected!');
        }

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->rejection_reason,
        ]);

        return redirect()->back()
            ->with('success', 'Transaction rejected!');
    }

    /**
     * Refund a confirmed transaction
     */
    public function refund(Request $request, Transaction $transaction)
    {
        $request->validate([
            'refund_reason' => 'nullable|string|max:500',
        ]);

        if ($transaction->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Only confirmed transactions can be refunded!');
        }

        $transaction->update([
            'status' => 'refunded',
            'notes' => $request->refund_reason ?? 'Refunded',
        ]);

        // TODO: Handle reversal logic based on transaction type

        return redirect()->back()
            ->with('success', 'Transaction refunded!');
    }

    /**
     * Process a confirmed transaction based on its type
     */
    protected function processConfirmedTransaction(Transaction $transaction): void
    {
        switch ($transaction->type) {
            case Transaction::TYPE_SUBSCRIPTION:
                // Activate pending subscription
                $subscription = Subscription::where('user_id', $transaction->user_id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();

                if ($subscription) {
                    $subscription->update([
                        'status' => 'active',
                        'starts_at' => now(),
                        'ends_at' => now()->addMonth(),
                        'featured_credits_remaining' => $subscription->plan->featured_quota_monthly,
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
                }
                break;

            case Transaction::TYPE_LISTING_CREDIT:
                // Create listing credits
                // Assume 1 credit per SAR 25
                $credits = max(1, floor($transaction->amount / 25));
                ListingCredit::create([
                    'user_id' => $transaction->user_id,
                    'transaction_id' => $transaction->id,
                    'credits_purchased' => $credits,
                    'credits_used' => 0,
                    'expires_at' => now()->addMonths(6), // Credits expire in 6 months
                ]);
                break;

            case Transaction::TYPE_FEATURED_BOOST:
                // Boost is usually created when request is made
                // Just update the related boost's transaction_id if needed
                break;
        }
    }
}
