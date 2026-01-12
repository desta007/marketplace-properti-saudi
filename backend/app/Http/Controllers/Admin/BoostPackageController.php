<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoostPackage;
use Illuminate\Http\Request;

class BoostPackageController extends Controller
{
    /**
     * Display a listing of boost packages
     */
    public function index()
    {
        $packages = BoostPackage::ordered()->paginate(20);
        return view('admin.boost-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.boost-packages.form');
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'required|string|max:50|unique:boost_packages,slug',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'boost_type' => 'required|in:featured,top_pick,premium',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        BoostPackage::create($validated);

        return redirect()->route('admin.boost-packages.index')
            ->with('success', 'Boost package created successfully!');
    }

    /**
     * Show the form for editing a package
     */
    public function edit(BoostPackage $boostPackage)
    {
        return view('admin.boost-packages.form', ['package' => $boostPackage]);
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, BoostPackage $boostPackage)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'slug' => 'required|string|max:50|unique:boost_packages,slug,' . $boostPackage->id,
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'boost_type' => 'required|in:featured,top_pick,premium',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $boostPackage->update($validated);

        return redirect()->route('admin.boost-packages.index')
            ->with('success', 'Boost package updated successfully!');
    }

    /**
     * Remove the specified package
     */
    public function destroy(BoostPackage $boostPackage)
    {
        $boostPackage->delete();

        return redirect()->route('admin.boost-packages.index')
            ->with('success', 'Boost package deleted successfully!');
    }

    /**
     * Toggle the active status of a package
     */
    public function toggleActive(BoostPackage $boostPackage)
    {
        $boostPackage->update(['is_active' => !$boostPackage->is_active]);

        return redirect()->route('admin.boost-packages.index')
            ->with('success', 'Package status updated!');
    }
}
