@extends('admin.layouts.app')

@section('title', 'Transaction #' . $transaction->id)

@section('content')
    <div class="page-header">
        <div>
            <h1>💰 Transaction #{{ $transaction->id }}</h1>
            <p>View transaction details</p>
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
            ← Back to Transactions
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Main Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaction Details</h3>
            </div>
            <div style="padding: 1.5rem;">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 0.75rem 0; color: #64748b; width: 150px;">Amount</td>
                        <td style="padding: 0.75rem 0;">
                            <span style="font-size: 1.5rem; font-weight: bold; color: #10b981;">
                                SAR {{ number_format($transaction->amount, 2) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.75rem 0; color: #64748b;">Type</td>
                        <td style="padding: 0.75rem 0;">
                            @switch($transaction->type)
                                @case('subscription')
                                    <span class="badge" style="background: #dbeafe; color: #1d4ed8; font-size: 0.875rem;">💳 Subscription</span>
                                    @break
                                @case('listing_credit')
                                    <span class="badge" style="background: #f3e8ff; color: #7c3aed; font-size: 0.875rem;">📝 Listing Credit</span>
                                    @break
                                @case('featured_boost')
                                    <span class="badge" style="background: #fef3c7; color: #d97706; font-size: 0.875rem;">⭐ Featured Boost</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.75rem 0; color: #64748b;">Status</td>
                        <td style="padding: 0.75rem 0;">
                            <span class="{{ $transaction->status_badge_class }}" style="padding: 0.35rem 0.75rem; border-radius: 0.25rem; font-size: 0.875rem;">
                                {{ $transaction->status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.75rem 0; color: #64748b;">Payment Method</td>
                        <td style="padding: 0.75rem 0;">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</td>
                    </tr>
                    @if($transaction->reference_number)
                        <tr>
                            <td style="padding: 0.75rem 0; color: #64748b;">Reference Number</td>
                            <td style="padding: 0.75rem 0;"><code>{{ $transaction->reference_number }}</code></td>
                        </tr>
                    @endif
                    <tr>
                        <td style="padding: 0.75rem 0; color: #64748b;">Created At</td>
                        <td style="padding: 0.75rem 0;">{{ $transaction->created_at->format('F d, Y H:i') }}</td>
                    </tr>
                    @if($transaction->approvedBy)
                        <tr>
                            <td style="padding: 0.75rem 0; color: #64748b;">{{ $transaction->status === 'confirmed' ? 'Confirmed' : 'Processed' }} By</td>
                            <td style="padding: 0.75rem 0;">
                                {{ $transaction->approvedBy->name }}
                                <span class="text-muted">({{ $transaction->approved_at?->format('M d, Y H:i') }})</span>
                            </td>
                        </tr>
                    @endif
                    @if($transaction->notes)
                        <tr>
                            <td style="padding: 0.75rem 0; color: #64748b;">Notes</td>
                            <td style="padding: 0.75rem 0;">{{ $transaction->notes }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- User Info & Actions -->
        <div>
            <div class="card" style="margin-bottom: 1rem;">
                <div class="card-header">
                    <h3 class="card-title">User</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ $transaction->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $transaction->user->email }}</small>
                            <br>
                            <small class="text-muted">{{ $transaction->user->phone }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    @if($transaction->status === 'pending')
                        <form action="{{ route('admin.transactions.confirm', $transaction) }}" method="POST">
                            @csrf
                            <input type="text" name="notes" placeholder="Add notes (optional)" class="form-control" style="margin-bottom: 0.5rem;">
                            <button type="submit" class="btn btn-primary" style="width: 100%;" onclick="return confirm('Confirm this payment?')">
                                ✅ Confirm Payment
                            </button>
                        </form>
                        <form action="{{ route('admin.transactions.reject', $transaction) }}" method="POST" style="margin-top: 0.5rem;">
                            @csrf
                            <input type="text" name="rejection_reason" placeholder="Rejection reason" class="form-control" style="margin-bottom: 0.5rem;">
                            <button type="submit" class="btn btn-danger" style="width: 100%;" onclick="return confirm('Reject this transaction?')">
                                ❌ Reject Transaction
                            </button>
                        </form>
                    @elseif($transaction->status === 'confirmed')
                        <form action="{{ route('admin.transactions.refund', $transaction) }}" method="POST">
                            @csrf
                            <input type="text" name="refund_reason" placeholder="Refund reason" class="form-control" style="margin-bottom: 0.5rem;">
                            <button type="submit" class="btn btn-warning" style="width: 100%;" onclick="return confirm('Refund this transaction? This action cannot be undone.')">
                                💸 Issue Refund
                            </button>
                        </form>
                    @else
                        <p class="text-muted" style="text-align: center;">No actions available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
