@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" class="btn btn-primary">
                📋 Review Properties
            </a>
            <a href="{{ route('admin.agents.index', ['status' => 'pending']) }}" class="btn"
                style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: white; border: none;">
                👥 Review Agents
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon green">🏢</div>
            <h3>Total Properties</h3>
            <div class="value">{{ number_format($stats['total_properties']) }}</div>
            <div class="change up">Active listings</div>
        </div>
        <div class="stat-card">
            <div class="icon amber">⏳</div>
            <h3>Pending Review</h3>
            <div class="value" style="color: #f59e0b;">{{ number_format($stats['pending_properties']) }}</div>
            <div class="change">Properties awaiting approval</div>
        </div>
        <div class="stat-card">
            <div class="icon blue">👥</div>
            <h3>Total Users</h3>
            <div class="value">{{ number_format($stats['total_users']) }}</div>
            <div class="change up">Registered users</div>
        </div>
        <div class="stat-card">
            <div class="icon green">✓</div>
            <h3>Verified Agents</h3>
            <div class="value">{{ number_format($stats['verified_agents']) }}</div>
            <div class="change up">REGA verified</div>
        </div>
        <div class="stat-card">
            <div class="icon amber">🕐</div>
            <h3>Pending Agents</h3>
            <div class="value" style="color: #f59e0b;">{{ number_format($stats['pending_agents']) }}</div>
            <div class="change">Awaiting verification</div>
        </div>
        <div class="stat-card">
            <div class="icon purple">📞</div>
            <h3>Total Leads</h3>
            <div class="value">{{ number_format($stats['total_leads']) }}</div>
            <div class="change up">{{ number_format($stats['new_leads']) }} new</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 1.5rem;">
        <!-- Pending Properties -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">🏢 Pending Properties</h3>
                <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}"
                    class="btn btn-sm btn-secondary">View All →</a>
            </div>
            @if($pendingProperties->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>City</th>
                                <th>Agent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingProperties as $property)
                                <tr>
                                    <td>
                                        <div style="font-weight: 600;">{{ Str::limit($property->title_en, 25) }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">{{ number_format($property->price) }} SAR
                                        </div>
                                    </td>
                                    <td>{{ $property->city?->name_en }}</td>
                                    <td>{{ $property->user?->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.properties.show', $property) }}"
                                            class="btn btn-primary btn-sm">Review</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #64748b;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">✅</div>
                    <p>All caught up! No pending properties.</p>
                </div>
            @endif
        </div>

        <!-- Pending Agents -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">👥 Pending Agent Verifications</h3>
                <a href="{{ route('admin.agents.index', ['status' => 'pending']) }}" class="btn btn-sm btn-secondary">View
                    All →</a>
            </div>
            @if($pendingAgents->count() > 0)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <th>REGA License</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingAgents as $agent)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div
                                                style="width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                                {{ substr($agent->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 600;">{{ $agent->name }}</div>
                                                <div style="font-size: 0.75rem; color: #64748b;">{{ $agent->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code
                                            style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $agent->rega_license_number }}</code>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.agents.show', $agent) }}" class="btn btn-primary btn-sm">Review</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #64748b;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">✅</div>
                    <p>All caught up! No pending verifications.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">📊 Platform Overview</h3>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div class="info-item">
                <label>Active Properties</label>
                <strong style="color: #10b981;">{{ number_format($stats['active_properties']) }}</strong>
            </div>
            <div class="info-item">
                <label>Properties for Sale</label>
                <strong>{{ number_format(\App\Models\Property::where('purpose', 'sale')->where('status', 'active')->count()) }}</strong>
            </div>
            <div class="info-item">
                <label>Properties for Rent</label>
                <strong>{{ number_format(\App\Models\Property::where('purpose', 'rent')->where('status', 'active')->count()) }}</strong>
            </div>
            <div class="info-item">
                <label>Total Views</label>
                <strong>{{ number_format(\App\Models\Property::sum('view_count')) }}</strong>
            </div>
        </div>
    </div>
@endsection