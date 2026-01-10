@extends('admin.layouts.app')

@section('title', 'Property Details')

@section('content')
    <div class="header">
        <div>
            <a href="{{ route('admin.properties.index') }}" style="color: #6b7280; text-decoration: none;">← Back to
                Properties</a>
            <h1 style="margin-top: 0.5rem;">Property #{{ $property->id }}</h1>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            @if($property->status === 'pending')
                <form action="{{ route('admin.properties.approve', $property) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">✓ Approve</button>
                </form>
                <button type="button" class="btn btn-danger" onclick="showRejectModal()">✗ Reject</button>
            @else
                <span class="badge badge-{{ $property->status }}" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                    {{ ucfirst($property->status) }}
                </span>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Property Details -->
        <div>
            <div class="card">
                <h3 style="margin-bottom: 1rem;">Property Information</h3>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280; width: 150px;">Title (EN)</td>
                        <td style="padding: 0.5rem 0;">{{ $property->title_en }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Title (AR)</td>
                        <td style="padding: 0.5rem 0;" dir="rtl">{{ $property->title_ar }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Type</td>
                        <td style="padding: 0.5rem 0;">{{ ucfirst($property->type) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Purpose</td>
                        <td style="padding: 0.5rem 0;">{{ ucfirst($property->purpose) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Price</td>
                        <td style="padding: 0.5rem 0; font-weight: 600; color: #059669;">
                            {{ number_format($property->price) }} SAR</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">City</td>
                        <td style="padding: 0.5rem 0;">{{ $property->city?->name_en }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">District</td>
                        <td style="padding: 0.5rem 0;">{{ $property->district?->name_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Area</td>
                        <td style="padding: 0.5rem 0;">{{ $property->area_sqm ?? '-' }} m²</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Bedrooms</td>
                        <td style="padding: 0.5rem 0;">{{ $property->bedrooms ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Bathrooms</td>
                        <td style="padding: 0.5rem 0;">{{ $property->bathrooms ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">REGA License</td>
                        <td style="padding: 0.5rem 0;">
                            @if($property->rega_ad_license)
                                <span
                                    style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-family: monospace;">
                                    {{ $property->rega_ad_license }}
                                </span>
                            @else
                                <span style="color: #dc2626;">Not provided</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Virtual Tour</td>
                        <td style="padding: 0.5rem 0;">
                            @if($property->virtual_tour_url)
                                <a href="{{ $property->virtual_tour_url }}" target="_blank" style="color: #059669;">View Tour
                                    →</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="card">
                <h3 style="margin-bottom: 1rem;">Description</h3>
                <div style="margin-bottom: 1rem;">
                    <strong>English:</strong>
                    <p style="margin-top: 0.5rem; color: #4b5563;">{{ $property->description_en ?? 'No description' }}</p>
                </div>
                <div>
                    <strong>Arabic:</strong>
                    <p style="margin-top: 0.5rem; color: #4b5563;" dir="rtl">
                        {{ $property->description_ar ?? 'لا يوجد وصف' }}</p>
                </div>
            </div>

            @if($property->images->count() > 0)
                <div class="card">
                    <h3 style="margin-bottom: 1rem;">Images</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                        @foreach($property->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Property Image"
                                style="width: 100%; height: 120px; object-fit: cover; border-radius: 0.5rem;">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Agent Info -->
        <div>
            <div class="card">
                <h3 style="margin-bottom: 1rem;">Agent Information</h3>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div
                        style="width: 60px; height: 60px; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        {{ substr($property->user?->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 600;">{{ $property->user?->name }}</div>
                        <div style="color: #6b7280; font-size: 0.875rem;">{{ $property->user?->email }}</div>
                    </div>
                </div>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Phone</td>
                        <td style="padding: 0.5rem 0;">{{ $property->user?->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">Agent Status</td>
                        <td style="padding: 0.5rem 0;">
                            @if($property->user?->agent_status)
                                <span class="badge badge-{{ $property->user->agent_status }}">
                                    {{ ucfirst($property->user->agent_status) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #6b7280;">REGA License</td>
                        <td style="padding: 0.5rem 0;">{{ $property->user?->rega_license_number ?? '-' }}</td>
                    </tr>
                </table>
                <a href="{{ route('admin.agents.show', $property->user) }}" class="btn btn-secondary"
                    style="width: 100%; justify-content: center; margin-top: 1rem;">View Agent Profile →</a>
            </div>

            <div class="card">
                <h3 style="margin-bottom: 1rem;">Timeline</h3>
                <div style="font-size: 0.875rem; color: #6b7280;">
                    <div style="margin-bottom: 0.5rem;">
                        <strong>Created:</strong> {{ $property->created_at->format('M d, Y H:i') }}
                    </div>
                    <div>
                        <strong>Updated:</strong> {{ $property->updated_at->format('M d, Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal"
        style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: 1rem; width: 90%; max-width: 400px;">
            <h3 style="margin-bottom: 1rem;">Reject Property</h3>
            <form action="{{ route('admin.properties.reject', $property) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Rejection Reason (optional)</label>
                    <textarea name="reason" class="form-control" rows="3"
                        placeholder="Enter reason for rejection..."></textarea>
                </div>
                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="hideRejectModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Property</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function showRejectModal() {
                document.getElementById('rejectModal').style.display = 'flex';
            }
            function hideRejectModal() {
                document.getElementById('rejectModal').style.display = 'none';
            }
        </script>
    @endpush
@endsection