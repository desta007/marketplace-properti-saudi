@extends('admin.layouts.app')

@section('title', 'Transactions')

@section('content')
    <div class="page-header">
        <div>
            <h1>💰 Transactions</h1>
            <p>View and manage payment transactions</p>
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
            <div style="color: #64748b;">Total Transactions</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #f59e0b;">{{ $stats['pending'] }}</div>
            <div style="color: #64748b;">Pending Confirmation</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #10b981;">{{ $stats['confirmed'] }}</div>
            <div style="color: #64748b;">Confirmed</div>
        </div>
        <div class="card" style="padding: 1.5rem; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #10b981;">SAR {{ number_format($stats['total_revenue'], 0) }}</div>
            <div style="color: #64748b;">Total Revenue</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem; padding: 1rem;">
        <form action="{{ route('admin.transactions.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div class="form-group" style="margin: 0;">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="">All Types</option>
                    <option value="subscription" {{ request('type') === 'subscription' ? 'selected' : '' }}>Subscription</option>
                    <option value="listing_credit" {{ request('type') === 'listing_credit' ? 'selected' : '' }}>Listing Credit</option>
                    <option value="featured_boost" {{ request('type') === 'featured_boost' ? 'selected' : '' }}>Featured Boost</option>
                </select>
            </div>
            <div class="form-group" style="margin: 0;">
                <label for="from_date">From Date</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="form-group" style="margin: 0;">
                <label for="to_date">To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transactions ({{ $transactions->total() }})</h3>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>
                                {{ $transaction->created_at->format('M d, Y') }}
                                <br>
                                <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $transaction->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $transaction->user->email }}</small>
                            </td>
                            <td>
                                @switch($transaction->type)
                                    @case('subscription')
                                        <span class="badge" style="background: #dbeafe; color: #1d4ed8;">💳 Subscription</span>
                                        @break
                                    @case('listing_credit')
                                        <span class="badge" style="background: #f3e8ff; color: #7c3aed;">📝 Listing Credit</span>
                                        @break
                                    @case('featured_boost')
                                        <span class="badge" style="background: #fef3c7; color: #d97706;">⭐ Featured Boost</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <strong style="color: #10b981;">SAR {{ number_format($transaction->amount, 0) }}</strong>
                            </td>
                            <td>
                                <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                                @if($transaction->reference_number)
                                    <br>
                                    <small>Ref: {{ $transaction->reference_number }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $transaction->status_badge_class }}" style="padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">
                                    {{ $transaction->status_label }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-secondary btn-sm">
                                        👁️ View
                                    </a>
                                    @if($transaction->status === 'pending')
                                        <form action="{{ route('admin.transactions.confirm', $transaction) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Confirm this payment?')">
                                                ✅ Confirm
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.transactions.reject', $transaction) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this transaction?')">
                                                ❌ Reject
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="pagination">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
