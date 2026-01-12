@extends('admin.layouts.app')

@section('title', isset($feature) ? 'Edit Feature' : 'Add Feature')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ isset($feature) ? 'Edit Feature' : 'Add New Feature' }}</h1>
            <p>{{ isset($feature) ? 'Update feature details' : 'Create a new property feature' }}</p>
        </div>
        <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
            ← Back to Features
        </a>
    </div>

    <div class="card" style="max-width: 600px;">
        <form action="{{ isset($feature) ? route('admin.features.update', $feature) : route('admin.features.store') }}"
            method="POST">
            @csrf
            @if(isset($feature))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name_en">Name (English) <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name_en" id="name_en" class="form-control"
                    value="{{ old('name_en', $feature->name_en ?? '') }}" placeholder="e.g., Swimming Pool" required>
                @error('name_en')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="name_ar">Name (Arabic) <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name_ar" id="name_ar" class="form-control" dir="rtl"
                    value="{{ old('name_ar', $feature->name_ar ?? '') }}" placeholder="مسبح" required>
                @error('name_ar')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="icon">Icon (Emoji)</label>
                <input type="text" name="icon" id="icon" class="form-control"
                    value="{{ old('icon', $feature->icon ?? '') }}" placeholder="🏊" style="font-size: 1.5rem;">
                <p style="color: #64748b; font-size: 0.75rem; margin-top: 0.25rem;">
                    Enter an emoji to represent this feature (e.g., 🏊 🌳 🚗 🔒)
                </p>
            </div>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-control"
                    value="{{ old('sort_order', $feature->sort_order ?? 0) }}" min="0" placeholder="0">
                <p style="color: #64748b; font-size: 0.75rem; margin-top: 0.25rem;">
                    Lower numbers appear first
                </p>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem;">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $feature->is_active ?? true) ? 'checked' : '' }} style="width: 1.25rem; height: 1.25rem;">
                <label for="is_active" style="margin: 0;">Active (visible to agents)</label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    {{ isset($feature) ? '💾 Update Feature' : '➕ Create Feature' }}
                </button>
                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection