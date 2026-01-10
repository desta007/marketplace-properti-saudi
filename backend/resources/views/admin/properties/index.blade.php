@extends('admin.layouts.app')

@section('title', 'Properties')

@section('content')
    <div class="page-header">
        <div>
            <h1>🏢 Properties</h1>
            <p>Manage and moderate property listings</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <form method="GET" action="{{ route('admin.properties.index') }}"
            style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>✅ Active</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 2; min-width: 200px;">
                <label>Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by title..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Properties Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Agent</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                        <tr>
                            <td style="color: #94a3b8; font-size: 0.75rem;">{{ $property->id }}</td>
                            <td>
                                <a href="{{ route('admin.properties.show', $property) }}" class="property-title-link">
                                    @if($property->images->count() > 0)
                                        <img src="{{ asset('storage/' . $property->images->first()->image_path) }}" 
                                             class="property-thumb" alt="">
                                    @else
                                        <div class="property-thumb" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; font-size: 1rem;">🏠</div>
                                    @endif
                                    <div class="property-info">
                                        <h4>{{ Str::limit($property->title_en, 35) }}</h4>
                                        <p>📍 {{ $property->city?->name_en }}</p>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.75rem; font-weight: 500;">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #10b981;">{{ number_format($property->price) }}</div>
                                <div style="font-size: 0.7rem; color: #94a3b8;">SAR {{ $property->purpose === 'rent' ? '/ year' : '' }}</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 28px; height: 28px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.7rem; font-weight: 600;">
                                        {{ substr($property->user?->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span style="font-size: 0.8rem;">{{ Str::limit($property->user?->name, 15) }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $property->status }}">
                                    @if($property->status === 'pending') ⏳ @elseif($property->status === 'active') ✅ @else ❌ @endif
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                            <td style="font-size: 0.8rem; color: #64748b;">{{ $property->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.properties.show', $property) }}"
                                        class="btn btn-secondary btn-sm">👁️ View</a>
                                    @if($property->status === 'pending')
                                        <form action="{{ route('admin.properties.approve', $property) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">✓</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 3rem; color: #64748b;">
                                <div style="font-size: 3rem; margin-bottom: 0.5rem;">📭</div>
                                <p>No properties found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 0 1rem;">
            {{ $properties->links() }}
        </div>
    </div>
@endsection