@extends('admin.layouts.app')

@section('title', 'Subscription #' . $subscription->id)

@section('content')
    <div class="page-header">
        <div>
            <h1>🔄 Subscription #{{ $subscription->id }}</h1>
            <p>View subscription details</p>
        </div>
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
            ← Back to Subscriptions
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
                <h3 class="card-title">Subscription Details</h3>
            </div>
            <div style="padding: 1.5rem;">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 0.5rem 0; color: #64748b; width: 150px;">Status</td>
                        <td style="padding: 0.5rem 0;">
                            @switch($subscription->status)
                                @case('pending')
                                    <span class="badge badge-pending" style="font-size: 1rem;">⏳ Pending Approval</span>
                                    @break
                                @case('active')
                                    <span class="badge badge-active" style="font-size: 1rem;">✅ Active</span>
                                    @break
                                @case('expired')
                                    <span class="badge badge-suspended" style="font-size: 1rem;">⏰ Expired</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge badge-rejected" style="font-size: 1rem;">❌ Cancelled</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #64748b;">Plan</td>
                        <td style="padding: 0.5rem 0;">
                            <strong>{{ $subscription->plan->name_en }}</strong>
                            <span style="color: #10b981;">(SAR {{ number_format($subscription->plan->price_monthly, 0) }}/month)</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #64748b;">Start Date</td>
                        <td style="padding: 0.5rem 0;">{{ $subscription->starts_at?->format('F d, Y') ?? 'Not started' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #64748b;">End Date</td>
                        <td style="padding: 0.5rem 0;">
                            {{ $subscription->ends_at?->format('F d, Y') ?? 'N/A' }}
                            @if($subscription->status === 'active' && $subscription->ends_at)
                                <span style="color: {{ $subscription->daysRemaining() <= 7 ? '#ef4444' : '#10b981' }};">
                                    ({{ $subscription->daysRemaining() }} days remaining)
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #64748b;">Featured Credits</td>
                        <td style="padding: 0.5rem 0;">
                            <strong>{{ $subscription->featured_credits_remaining }}</strong> remaining
                            <span class="text-muted">/ {{ $subscription->plan->featured_quota_monthly }} per month</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #64748b;">Approved By</td>
                        <td style="padding: 0.5rem 0;">
                            @if($subscription->approvedBy)
                                {{ $subscription->approvedBy->name }}
                                <span class="text-muted">({{ $subscription->approved_at?->format('M d, Y H:i') }})</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @if($subscription->notes)
                        <tr>
                            <td style="padding: 0.5rem 0; color: #64748b;">Notes</td>
                            <td style="padding: 0.5rem 0;">{{ $subscription->notes }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Agent Info & Actions -->
        <div>
            <div class="card" style="margin-bottom: 1rem;">
                <div class="card-header">
                    <h3 class="card-title">Agent</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                            {{ strtoupper(substr($subscription->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong>{{ $subscription->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $subscription->user->email }}</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.agents.show', $subscription->user) }}" class="btn btn-secondary btn-sm" style="width: 100%;">
                        View Agent Profile →
                    </a>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                    @if($subscription->status === 'pending')
                        <form action="{{ route('admin.subscriptions.approve', $subscription) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width: 100%;" onclick="return confirm('Approve this subscription?')">
                                ✅ Approve Subscription
                            </button>
                        </form>
                        <form action="{{ route('admin.subscriptions.reject', $subscription) }}" method="POST">
                            @csrf
                            <input type="text" name="rejection_reason" placeholder="Rejection reason (optional)" class="form-control" style="margin-bottom: 0.5rem;">
                            <button type="submit" class="btn btn-danger" style="width: 100%;" onclick="return confirm('Reject this subscription?')">
                                ❌ Reject Subscription
                            </button>
                        </form>
                    @endif

                    @if($subscription->status === 'active')
                        <form action="{{ route('admin.subscriptions.extend', $subscription) }}" method="POST">
                            @csrf
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="number" name="months" value="1" min="1" max="12" class="form-control" style="width: 80px;">
                                <span style="align-self: center;">month(s)</span>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                ➕ Extend Subscription
                            </button>
                        </form>
                        <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST" style="margin-top: 1rem;">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="width: 100%;" onclick="return confirm('Cancel this subscription?')">
                                ❌ Cancel Subscription
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
