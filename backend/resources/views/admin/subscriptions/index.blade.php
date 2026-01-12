@extends('admin.layouts.app')

@section('title', 'Agent Subscriptions')

@section('content')
    <div class="page-header">
        <div>
            <h1>🔄 Agent Subscriptions</h1>
            <p>Manage agent subscription requests and active subscriptions</p>
        </div>
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

    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #3b82f6;">{{ $stats['total'] }}</div>
            <div style="color: #64748b;">Total Subscriptions</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #f59e0b;">{{ $stats['pending'] }}</div>
            <div style="color: #64748b;">Pending Approval</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #10b981;">{{ $stats['active'] }}</div>
            <div style="color: #64748b;">Active</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #64748b;">{{ $stats['expired'] }}</div>
            <div style="color: #64748b;">Expired</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form action="{{ route('admin.subscriptions.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div class="form-group" style="margin: 0;">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <label for="plan_id">Plan</label>
                <select name="plan_id" id="plan_id" class="form-control">
                    <option value="">All Plans</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Subscriptions ({{ $subscriptions->total() }})</h3>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Agent</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Period</th>
                        <th>Featured Credits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td>#{{ $subscription->id }}</td>
                            <td>
                                <strong>{{ $subscription->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $subscription->user->email }}</small>
                            </td>
                            <td>
                                <span class="badge" style="background: #e0e7ff; color: #4f46e5;">
                                    {{ $subscription->plan->name_en }}
                                </span>
                                <br>
                                <small>SAR {{ number_format($subscription->plan->price_monthly, 0) }}/mo</small>
                            </td>
                            <td>
                                @switch($subscription->status)
                                    @case('pending')
                                        <span class="badge badge-pending">⏳ Pending</span>
                                        @break
                                    @case('active')
                                        <span class="badge badge-active">✅ Active</span>
                                        @break
                                    @case('expired')
                                        <span class="badge badge-suspended">⏰ Expired</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-rejected">❌ Cancelled</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if($subscription->starts_at)
                                    {{ $subscription->starts_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">to {{ $subscription->ends_at?->format('M d, Y') ?? 'N/A' }}</small>
                                    @if($subscription->status === 'active' && $subscription->ends_at)
                                        <br>
                                        <small style="color: {{ $subscription->daysRemaining() <= 7 ? '#ef4444' : '#10b981' }};">
                                            {{ $subscription->daysRemaining() }} days left
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">Not started</span>
                                @endif
                            </td>
                            <td>
                                {{ $subscription->featured_credits_remaining }} left
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn btn-secondary btn-sm">
                                        👁️ View
                                    </a>
                                    @if($subscription->status === 'pending')
                                        <form action="{{ route('admin.subscriptions.approve', $subscription) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Approve this subscription?')">
                                                ✅ Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.subscriptions.reject', $subscription) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this subscription?')">
                                                ❌ Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                No subscriptions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="pagination">
                {{ $subscriptions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
