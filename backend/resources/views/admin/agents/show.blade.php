@extends('admin.layouts.app')

@section('title', 'Agent Details')

@section('content')
    <div class="page-header">
        <div>
            <a href="{{ route('admin.agents.index') }}" style="color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                ← Back to Agents
            </a>
            <h1 style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 700;">
                    {{ substr($agent->name, 0, 1) }}
                </div>
                {{ $agent->name }}
            </h1>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            @if($agent->agent_status === 'pending')
                <form action="{{ route('admin.agents.verify', $agent) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">✓ Verify Agent</button>
                </form>
                <button type="button" class="btn btn-danger" onclick="showRejectModal()">✗ Reject</button>
            @elseif($agent->agent_status === 'verified')
                <span class="badge badge-verified" style="padding: 0.625rem 1.25rem; font-size: 0.875rem;">✅ Verified</span>
                <button type="button" class="btn btn-warning" onclick="showSuspendModal()">🚫 Suspend</button>
            @elseif($agent->agent_status === 'suspended')
                <span class="badge badge-suspended" style="padding: 0.625rem 1.25rem; font-size: 0.875rem;">🚫 Suspended</span>
                <form action="{{ route('admin.agents.verify', $agent) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Reinstate</button>
                </form>
            @else
                <span class="badge badge-rejected" style="padding: 0.625rem 1.25rem; font-size: 0.875rem;">❌ Rejected</span>
                <form action="{{ route('admin.agents.verify', $agent) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Reinstate</button>
                </form>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
        <!-- Agent Details -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">👤 Agent Information</h3>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <strong>{{ $agent->name }}</strong>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <strong>{{ $agent->email }}</strong>
                </div>
                <div class="info-item">
                    <label>Phone</label>
                    <strong>{{ $agent->phone ?? '-' }}</strong>
                </div>
                <div class="info-item">
                    <label>WhatsApp</label>
                    @if($agent->whatsapp_number)
                        <a href="{{ $agent->whatsapp_url }}" target="_blank" style="color: #25d366; font-weight: 600;">
                            {{ $agent->whatsapp_number }} →
                        </a>
                    @else
                        <strong>-</strong>
                    @endif
                </div>
                <div class="info-item">
                    <label>Language</label>
                    <strong>{{ $agent->language === 'ar' ? '🇸🇦 Arabic' : '🇬🇧 English' }}</strong>
                </div>
                <div class="info-item">
                    <label>Joined</label>
                    <strong>{{ $agent->created_at->format('M d, Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- License Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📜 REGA License</h3>
            </div>
            <div class="info-grid">
                <div class="info-item" style="grid-column: span 2;">
                    <label>License Number</label>
                    @if($agent->rega_license_number)
                        <code style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 0.5rem 0.75rem; border-radius: 8px; font-size: 1.125rem; font-weight: 700; display: inline-block;">
                            {{ $agent->rega_license_number }}
                        </code>
                    @else
                        <strong style="color: #ef4444;">Not provided</strong>
                    @endif
                </div>
                <div class="info-item">
                    <label>Document</label>
                    @if($agent->rega_license_document)
                        <a href="{{ asset('storage/' . $agent->rega_license_document) }}" target="_blank" class="btn btn-secondary btn-sm">
                            📄 View Document
                        </a>
                    @else
                        <strong style="color: #ef4444;">Not uploaded</strong>
                    @endif
                </div>
                <div class="info-item">
                    <label>Status</label>
                    <span class="badge badge-{{ $agent->agent_status ?? 'pending' }}">
                        @if($agent->agent_status === 'verified') ✅ 
                        @elseif($agent->agent_status === 'rejected') ❌ 
                        @elseif($agent->agent_status === 'suspended') 🚫 
                        @else ⏳ 
                        @endif
                        {{ ucfirst($agent->agent_status ?? 'pending') }}
                    </span>
                </div>
                @if($agent->agent_verified_at)
                    <div class="info-item" style="grid-column: span 2;">
                        <label>Verified At</label>
                        <strong style="color: #10b981;">{{ $agent->agent_verified_at->format('M d, Y H:i') }}</strong>
                    </div>
                @endif
                @if($agent->agent_rejection_reason)
                    <div class="info-item" style="grid-column: span 2; background: #fee2e2;">
                        <label>Rejection/Suspension Reason</label>
                        <strong style="color: #991b1b;">{{ $agent->agent_rejection_reason }}</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Agent's Properties -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3 class="card-title">🏢 Agent's Properties ({{ $agent->properties->count() }})</h3>
            @if($agent->properties->count() > 0)
                <a href="{{ route('admin.properties.index', ['search' => $agent->name]) }}" class="btn btn-sm btn-secondary">View All →</a>
            @endif
        </div>
        @if($agent->properties->count() > 0)
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Property</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agent->properties as $property)
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
                                            <h4>{{ Str::limit($property->title_en, 30) }}</h4>
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
                                    <div style="font-size: 0.7rem; color: #94a3b8;">SAR</div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $property->status }}">
                                        @if($property->status === 'active') ✅ @elseif($property->status === 'pending') ⏳ @else ❌ @endif
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </td>
                                <td style="font-size: 0.8rem; color: #64748b;">{{ $property->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.properties.show', $property) }}" class="btn btn-secondary btn-sm">👁️ View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; color: #64748b;">
                <div style="font-size: 3rem; margin-bottom: 0.5rem;">📭</div>
                <p>No properties listed yet</p>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>❌ Reject Agent</h3>
            </div>
            <form action="{{ route('admin.agents.reject', $agent) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejection Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideRejectModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Agent</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suspend Modal -->
    <div id="suspendModal" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>🚫 Suspend Agent</h3>
            </div>
            <form action="{{ route('admin.agents.suspend', $agent) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-error" style="margin-bottom: 1rem;">
                        ⚠️ This will suspend the agent and deactivate all their property listings.
                    </div>
                    <div class="form-group">
                        <label>Suspension Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for suspension..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideSuspendModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Suspend Agent</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 100;
        align-items: center;
        justify-content: center;
    }
    .modal-backdrop.show {
        display: flex;
    }
    .modal {
        background: white;
        border-radius: 20px;
        max-width: 450px;
        width: 90%;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: modalSlide 0.3s ease;
    }
    @keyframes modalSlide {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-radius: 0 0 20px 20px;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }
</style>
@endpush

@push('scripts')
<script>
    function showRejectModal() {
        document.getElementById('rejectModal').classList.add('show');
    }
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.remove('show');
    }
    function showSuspendModal() {
        document.getElementById('suspendModal').classList.add('show');
    }
    function hideSuspendModal() {
        document.getElementById('suspendModal').classList.remove('show');
    }
    
    // Close modal on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });
</script>
@endpush