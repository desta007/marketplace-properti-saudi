@extends('admin.layouts.app')

@section('title', 'Features Management')

@section('content')
    <div class="page-header">
        <div>
            <h1>Features Management</h1>
            <p>Manage property features that agents can select</p>
        </div>
        <a href="{{ route('admin.features.create') }}" class="btn btn-primary">
            ➕ Add New Feature
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Features ({{ $features->total() }})</h3>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Icon</th>
                        <th>Name (English)</th>
                        <th>Name (Arabic)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($features as $feature)
                        <tr>
                            <td>{{ $feature->sort_order }}</td>
                            <td style="font-size: 1.5rem;">{{ $feature->icon ?: '—' }}</td>
                            <td><strong>{{ $feature->name_en }}</strong></td>
                            <td dir="rtl">{{ $feature->name_ar }}</td>
                            <td>
                                @if($feature->is_active)
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-suspended">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('admin.features.edit', $feature) }}" class="btn btn-secondary btn-sm">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('admin.features.toggle-active', $feature) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $feature->is_active ? 'btn-warning' : 'btn-primary' }}">
                                            {{ $feature->is_active ? '⏸️ Deactivate' : '▶️ Activate' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.features.destroy', $feature) }}" method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this feature?');">
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
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #64748b;">
                                No features found. <a href="{{ route('admin.features.create') }}">Add the first one!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($features->hasPages())
            <div class="pagination">
                {{ $features->links() }}
            </div>
        @endif
    </div>
@endsection