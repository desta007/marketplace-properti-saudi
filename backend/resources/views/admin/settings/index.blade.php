@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="page-header">
        <div>
            <h1>⚙️ Settings</h1>
            <p>Manage your website settings and contact information.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">📞 Contact Information</h3>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" id="site_name" name="site_name" class="form-control"
                        value="{{ old('site_name', $settings['site_name']) }}" placeholder="SaudiProp">
                    @error('site_name')
                        <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="site_email">Email Address</label>
                    <input type="email" id="site_email" name="site_email" class="form-control"
                        value="{{ old('site_email', $settings['site_email']) }}" placeholder="info@saudiprop.com">
                    @error('site_email')
                        <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="site_phone">Phone Number</label>
                    <input type="text" id="site_phone" name="site_phone" class="form-control"
                        value="{{ old('site_phone', $settings['site_phone']) }}" placeholder="+966 50 123 4567">
                    @error('site_phone')
                        <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="site_whatsapp">WhatsApp Number</label>
                    <input type="text" id="site_whatsapp" name="site_whatsapp" class="form-control"
                        value="{{ old('site_whatsapp', $settings['site_whatsapp']) }}" placeholder="+966501234567">
                    <small style="color: #64748b; font-size: 0.75rem;">Include country code without spaces</small>
                    @error('site_whatsapp')
                        <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label for="site_address">Address</label>
                <textarea id="site_address" name="site_address" class="form-control" rows="2"
                    placeholder="Riyadh, Saudi Arabia">{{ old('site_address', $settings['site_address']) }}</textarea>
                @error('site_address')
                    <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary">
                💾 Save Settings
            </button>
        </div>
    </form>
@endsection