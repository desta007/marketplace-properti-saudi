@extends('admin.layouts.app')

@section('title', isset($package) ? 'Edit Package: ' . $package->name_en : 'Create New Package')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ isset($package) ? '✏️ Edit Package' : '➕ Create New Package' }}</h1>
            <p>{{ isset($package) ? 'Update boost package details' : 'Add a new boost package' }}</p>
        </div>
        <a href="{{ route('admin.boost-packages.index') }}" class="btn btn-secondary">
            ← Back to Packages
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Package Details</h3>
        </div>

        <form
            action="{{ isset($package) ? route('admin.boost-packages.update', $package) : route('admin.boost-packages.store') }}"
            method="POST" style="padding: 1.5rem;">
            @csrf
            @if(isset($package))
                @method('PUT')
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Name English -->
                <div class="form-group">
                    <label for="name_en">Name (English) *</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $package->name_en ?? '') }}"
                        class="form-control @error('name_en') is-invalid @enderror" required>
                    @error('name_en')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Name Arabic -->
                <div class="form-group">
                    <label for="name_ar">Name (Arabic) *</label>
                    <input type="text" name="name_ar" id="name_ar" dir="rtl"
                        value="{{ old('name_ar', $package->name_ar ?? '') }}"
                        class="form-control @error('name_ar') is-invalid @enderror" required>
                    @error('name_ar')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="form-group">
                    <label for="slug">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $package->slug ?? '') }}"
                        class="form-control @error('slug') is-invalid @enderror" required pattern="[a-z0-9-]+"
                        placeholder="e.g., featured-7, top-pick-14">
                    <small class="text-muted">Lowercase letters, numbers, and hyphens only</small>
                    @error('slug')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Boost Type -->
                <div class="form-group">
                    <label for="boost_type">Boost Type *</label>
                    <select name="boost_type" id="boost_type" class="form-control @error('boost_type') is-invalid @enderror"
                        required>
                        <option value="featured" {{ old('boost_type', $package->boost_type ?? '') === 'featured' ? 'selected' : '' }}>
                            ⭐ Featured - Basic boost visibility
                        </option>
                        <option value="top_pick" {{ old('boost_type', $package->boost_type ?? '') === 'top_pick' ? 'selected' : '' }}>
                            🔥 Top Pick - Medium priority
                        </option>
                        <option value="premium" {{ old('boost_type', $package->boost_type ?? '') === 'premium' ? 'selected' : '' }}>
                            👑 Premium - Highest priority
                        </option>
                    </select>
                    @error('boost_type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Duration Days -->
                <div class="form-group">
                    <label for="duration_days">Duration (Days) *</label>
                    <input type="number" name="duration_days" id="duration_days"
                        value="{{ old('duration_days', $package->duration_days ?? 7) }}"
                        class="form-control @error('duration_days') is-invalid @enderror" min="1" required>
                    @error('duration_days')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label for="price">Price (SAR) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $package->price ?? 50) }}"
                        class="form-control @error('price') is-invalid @enderror" min="0" step="0.01" required>
                    @error('price')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order"
                        value="{{ old('sort_order', $package->sort_order ?? 0) }}"
                        class="form-control @error('sort_order') is-invalid @enderror" min="0">
                    @error('sort_order')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Is Active -->
                <div class="form-group" style="display: flex; align-items: center;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
                        <span>Active</span>
                    </label>
                </div>
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">
                    {{ isset($package) ? '💾 Update Package' : '➕ Create Package' }}
                </button>
                <a href="{{ route('admin.boost-packages.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection