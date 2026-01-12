@extends('admin.layouts.app')

@section('title', 'Boost Packages')

@section('content')
    <div class="page-header">
        <div>
            <h1>🚀 Boost Packages</h1>
            <p>Manage property boost packages for featured listings</p>
        </div>
        <a href="{{ route('admin.boost-packages.create') }}" class="btn btn-primary">
            ➕ Add New Package
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
            <h3 class="card-title">All Packages ({{ $packages->total() }})</h3>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Package</th>
                        <th>Boost Type</th>
                        <th>Duration</th>
                        <th>Price (SAR)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>{{ $package->sort_order }}</td>
                            <td>
                                <strong>{{ $package->name_en }}</strong>
                                <br>
                                <small class="text-muted" dir="rtl">{{ $package->name_ar }}</small>
                                <br>
                                <code>{{ $package->slug }}</code>
                            </td>
                            <td>
                                @switch($package->boost_type)
                                    @case('featured')
                                        <span class="badge" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1d4ed8;">⭐ Featured</span>
                                        @break
                                    @case('top_pick')
                                        <span class="badge" style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); color: #7c3aed;">🔥 Top Pick</span>
                                        @break
                                    @case('premium')
                                        <span class="badge" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #d97706;">👑 Premium</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $package->duration_days }} days</td>
                            <td>
                                <strong style="color: #10b981;">{{ number_format($package->price, 0) }}</strong>
                            </td>
                            <td>
                                @if($package->is_active)
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-suspended">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('admin.boost-packages.edit', $package) }}" class="btn btn-secondary btn-sm">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.boost-packages.toggle-active', $package) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $package->is_active ? 'btn-warning' : 'btn-primary' }}">
                                            {{ $package->is_active ? '⏸️ Deactivate' : '▶️ Activate' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.boost-packages.destroy', $package) }}" method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this package?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem; color: #64748b;">
                                No packages found. <a href="{{ route('admin.boost-packages.create') }}">Add the first one!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($packages->hasPages())
            <div class="pagination">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
@endsection
