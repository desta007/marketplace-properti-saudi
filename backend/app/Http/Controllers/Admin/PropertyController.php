<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * List all properties for moderation
     */
    public function index(Request $request)
    {
        $query = Property::with(['user', 'city', 'district']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                    ->orWhere('title_ar', 'like', "%{$search}%");
            });
        }

        $properties = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show property detail for review
     */
    public function show(Property $property)
    {
        $property->load(['user', 'city', 'district', 'images']);
        return view('admin.properties.show', compact('property'));
    }

    /**
     * Approve property listing
     */
    public function approve(Request $request, Property $property)
    {
        $property->update(['status' => 'active']);

        if ($request->ajax()) {
            return response()->json(['message' => 'Property approved successfully']);
        }

        return redirect()->back()->with('success', 'Property approved successfully');
    }

    /**
     * Reject property listing
     */
    public function reject(Request $request, Property $property)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $property->update(['status' => 'rejected']);

        // TODO: Send notification to agent with rejection reason

        if ($request->ajax()) {
            return response()->json(['message' => 'Property rejected']);
        }

        return redirect()->back()->with('success', 'Property rejected');
    }
}
