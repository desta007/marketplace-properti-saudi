@extends('admin.layouts.app')

@section('title', isset($plan) ? 'Edit Plan: ' . $plan->name_en : 'Create New Plan')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ isset($plan) ? '✏️ Edit Plan' : '➕ Create New Plan' }}</h1>
            <p>{{ isset($plan) ? 'Update subscription plan details' : 'Add a new subscription plan' }}</p>
        </div>
        <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
            ← Back to Plans
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Plan Details</h3>
        </div>

        <form
            action="{{ isset($plan) ? route('admin.subscription-plans.update', $plan) : route('admin.subscription-plans.store') }}"
            method="POST" style="padding: 1.5rem;">
            @csrf
            @if(isset($plan))
                @method('PUT')
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Name English -->
                <div class="form-group">
                    <label for="name_en">Name (English) *</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $plan->name_en ?? '') }}"
                        class="form-control @error('name_en') is-invalid @enderror" required>
                    @error('name_en')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Name Arabic -->
                <div class="form-group">
                    <label for="name_ar">Name (Arabic) *</label>
                    <input type="text" name="name_ar" id="name_ar" dir="rtl"
                        value="{{ old('name_ar', $plan->name_ar ?? '') }}"
                        class="form-control @error('name_ar') is-invalid @enderror" required>
                    @error('name_ar')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="form-group">
                    <label for="slug">Slug *</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $plan->slug ?? '') }}"
                        class="form-control @error('slug') is-invalid @enderror" required pattern="[a-z0-9-]+"
                        placeholder="e.g., basic, professional">
                    <small class="text-muted">Lowercase letters, numbers, and hyphens only</small>
                    @error('slug')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Price Monthly -->
                <div class="form-group">
                    <label for="price_monthly">Price Monthly (SAR) *</label>
                    <input type="number" name="price_monthly" id="price_monthly"
                        value="{{ old('price_monthly', $plan->price_monthly ?? 0) }}"
                        class="form-control @error('price_monthly') is-invalid @enderror" min="0" step="0.01" required>
                    <small class="text-muted">Set to 0 for free plan</small>
                    @error('price_monthly')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Listing Limit -->
                <div class="form-group">
                    <label for="listing_limit">Listing Limit</label>
                    <input type="number" name="listing_limit" id="listing_limit"
                        value="{{ old('listing_limit', $plan->listing_limit ?? '') }}"
                        class="form-control @error('listing_limit') is-invalid @enderror" min="1"
                        placeholder="Leave empty for unlimited">
                    <small class="text-muted">Maximum active listings. Leave empty for unlimited.</small>
                    @error('listing_limit')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Featured Quota Monthly -->
                <div class="form-group">
                    <label for="featured_quota_monthly">Featured Credits / Month</label>
                    <input type="number" name="featured_quota_monthly" id="featured_quota_monthly"
                        value="{{ old('featured_quota_monthly', $plan->featured_quota_monthly ?? 0) }}"
                        class="form-control @error('featured_quota_monthly') is-invalid @enderror" min="0">
                    <small class="text-muted">Number of free featured boosts per month</small>
                    @error('featured_quota_monthly')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Featured Credit Days -->
                <div class="form-group">
                    <label for="featured_credit_days">Days per Featured Credit</label>
                    <input type="number" name="featured_credit_days" id="featured_credit_days"
                        value="{{ old('featured_credit_days', $plan->featured_credit_days ?? 7) }}"
                        class="form-control @error('featured_credit_days') is-invalid @enderror" min="1">
                    <small class="text-muted">How many days each featured credit provides</small>
                    @error('featured_credit_days')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order"
                        value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
                        class="form-control @error('sort_order') is-invalid @enderror" min="0">
                    @error('sort_order')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description English -->
            <div class="form-group" style="margin-top: 1rem;">
                <label for="description_en">Description (English)</label>
                <textarea name="description_en" id="description_en" rows="2"
                    class="form-control @error('description_en') is-invalid @enderror">{{ old('description_en', $plan->description_en ?? '') }}</textarea>
                @error('description_en')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description Arabic -->
            <div class="form-group">
                <label for="description_ar">Description (Arabic)</label>
                <textarea name="description_ar" id="description_ar" rows="2" dir="rtl"
                    class="form-control @error('description_ar') is-invalid @enderror">{{ old('description_ar', $plan->description_ar ?? '') }}</textarea>
                @error('description_ar')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Features List -->
            <div class="form-group">
                <label for="features_list">Features (one per line)</label>
                <textarea name="features_list" id="features_list" rows="5"
                    class="form-control @error('features_list') is-invalid @enderror"
                    placeholder="Unlimited listings&#10;Priority support&#10;Advanced analytics">{{ old('features_list', isset($plan) && $plan->features ? implode("\n", $plan->features) : '') }}</textarea>
                <small class="text-muted">Enter each feature on a new line. These will be displayed on the pricing
                    page.</small>
                @error('features_list')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Is Active -->
            <div class="form-group" style="margin-top: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
                <small class="text-muted">Inactive plans won't be shown to users</small>
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">
                    {{ isset($plan) ? '💾 Update Plan' : '➕ Create Plan' }}
                </button>
                <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection