@extends('admin.layouts.app')

@section('title', 'Subscription Plans')

@section('content')
    <div class="page-header">
        <div>
            <h1>💳 Subscription Plans</h1>
            <p>Manage subscription plans for agents</p>
        </div>
        <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-primary">
            ➕ Add New Plan
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Plans ({{ $plans->total() }})</h3>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Plan</th>
                        <th>Price (SAR)</th>
                        <th>Listing Limit</th>
                        <th>Featured Credits</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                        <tr>
                            <td>{{ $plan->sort_order }}</td>
                            <td>
                                <strong>{{ $plan->name_en }}</strong>
                                <br>
                                <small class="text-muted" dir="rtl">{{ $plan->name_ar }}</small>
                                <br>
                                <code>{{ $plan->slug }}</code>
                            </td>
                            <td>
                                @if($plan->price_monthly == 0)
                                    <span class="badge badge-active">Free</span>
                                @else
                                    <strong>{{ number_format($plan->price_monthly, 0) }}</strong> /month
                                @endif
                            </td>
                            <td>
                                @if($plan->listing_limit === null)
                                    <span class="badge badge-verified">♾️ Unlimited</span>
                                @else
                                    {{ $plan->listing_limit }} listings
                                @endif
                            </td>
                            <td>
                                @if($plan->featured_quota_monthly > 0)
                                    {{ $plan->featured_quota_monthly }} credits/month
                                    <br>
                                    <small class="text-muted">({{ $plan->featured_credit_days }} days each)</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($plan->is_active)
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-suspended">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('admin.subscription-plans.edit', $plan) }}"
                                        class="btn btn-secondary btn-sm">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.subscription-plans.toggle-active', $plan) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $plan->is_active ? 'btn-warning' : 'btn-primary' }}">
                                            {{ $plan->is_active ? '⏸️ Deactivate' : '▶️ Activate' }}
                                        </button>
                                    </form>
                                    @if($plan->slug !== 'free')
                                        <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                No plans found. <a href="{{ route('admin.subscription-plans.create') }}">Add the first one!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($plans->hasPages())
            <div class="pagination">
                {{ $plans->links() }}
            </div>
        @endif
    </div>

    <!-- Features Legend -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">📋 Plan Features Preview</h3>
        </div>
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @foreach($plans as $plan)
                    <div
                        style="border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; {{ $plan->slug === 'professional' ? 'border-color: #10b981; border-width: 2px;' : '' }}">
                        <h4 style="margin-bottom: 0.5rem;">{{ $plan->name_en }}</h4>
                        <p style="font-size: 1.5rem; font-weight: bold; color: #10b981;">
                            @if($plan->price_monthly == 0)
                                Free
                            @else
                                SAR {{ number_format($plan->price_monthly, 0) }}
                            @endif
                            <span style="font-size: 0.875rem; color: #64748b; font-weight: normal;">/month</span>
                        </p>
                        @if($plan->features)
                            <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.875rem; color: #475569;">
                                @foreach($plan->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection