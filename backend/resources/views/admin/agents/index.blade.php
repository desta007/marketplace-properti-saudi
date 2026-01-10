@extends('admin.layouts.app')

@section('title', 'Agents')

@section('content')
    <div class="page-header">
        <div>
            <h1>👥 Agents</h1>
            <p>Manage and verify real estate agents</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <form method="GET" action="{{ route('admin.agents.index') }}"
            style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>✅ Verified</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>🚫 Suspended</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 2; min-width: 200px;">
                <label>Search</label>
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, license..."
                    value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.agents.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Agents Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Agent</th>
                        <th>REGA License</th>
                        <th>Status</th>
                        <th>Properties</th>
                        <th>Joined</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                        <tr>
                            <td style="color: #94a3b8; font-size: 0.75rem;">{{ $agent->id }}</td>
                            <td>
                                <a href="{{ route('admin.agents.show', $agent) }}" class="property-title-link">
                                    <div
                                        style="width: 50px; height: 50px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; font-weight: 700; flex-shrink: 0;">
                                        {{ substr($agent->name, 0, 1) }}
                                    </div>
                                    <div class="property-info">
                                        <h4>{{ $agent->name }}</h4>
                                        <p>📧 {{ $agent->email }}</p>
                                        @if($agent->phone)
                                            <p>📱 {{ $agent->phone }}</p>
                                        @endif
                                    </div>
                                </a>
                            </td>
                            <td>
                                @if($agent->rega_license_number)
                                    <code
                                        style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600;">
                                                    {{ $agent->rega_license_number }}
                                                </code>
                                    @if($agent->rega_license_document)
                                        <a href="{{ asset('storage/' . $agent->rega_license_document) }}" target="_blank"
                                            style="display: block; margin-top: 0.25rem; font-size: 0.7rem; color: #64748b;">
                                            📄 View Document
                                        </a>
                                    @endif
                                @else
                                    <span style="color: #94a3b8;">Not provided</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $agent->agent_status ?? 'pending' }}">
                                    @if($agent->agent_status === 'verified') ✅
                                    @elseif($agent->agent_status === 'rejected') ❌
                                    @elseif($agent->agent_status === 'suspended') 🚫
                                    @else ⏳
                                    @endif
                                    {{ ucfirst($agent->agent_status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span
                                        style="background: #f1f5f9; padding: 0.375rem 0.75rem; border-radius: 8px; font-weight: 600;">
                                        🏢 {{ $agent->properties_count ?? $agent->properties()->count() }}
                                    </span>
                                </div>
                            </td>
                            <td style="font-size: 0.8rem; color: #64748b;">{{ $agent->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('admin.agents.show', $agent) }}" class="btn btn-secondary btn-sm">👁️
                                        View</a>
                                    @if($agent->agent_status === 'pending')
                                        <form action="{{ route('admin.agents.verify', $agent) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">✓ Verify</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: #64748b;">
                                <div style="font-size: 3rem; margin-bottom: 0.5rem;">👥</div>
                                <p>No agents found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 0 1rem;">
            {{ $agents->links() }}
        </div>
    </div>
@endsection