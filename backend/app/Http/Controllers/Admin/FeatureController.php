<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of features
     */
    public function index()
    {
        $features = Feature::ordered()->paginate(20);
        return view('admin.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new feature
     */
    public function create()
    {
        return view('admin.features.form');
    }

    /**
     * Store a newly created feature
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Feature::create($validated);

        return redirect()->route('admin.features.index')
            ->with('success', 'Feature created successfully!');
    }

    /**
     * Show the form for editing a feature
     */
    public function edit(Feature $feature)
    {
        return view('admin.features.form', compact('feature'));
    }

    /**
     * Update the specified feature
     */
    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $feature->update($validated);

        return redirect()->route('admin.features.index')
            ->with('success', 'Feature updated successfully!');
    }

    /**
     * Remove the specified feature
     */
    public function destroy(Feature $feature)
    {
        $feature->delete();

        return redirect()->route('admin.features.index')
            ->with('success', 'Feature deleted successfully!');
    }

    /**
     * Toggle the active status of a feature
     */
    public function toggleActive(Feature $feature)
    {
        $feature->update(['is_active' => !$feature->is_active]);

        return redirect()->route('admin.features.index')
            ->with('success', 'Feature status updated!');
    }
}
