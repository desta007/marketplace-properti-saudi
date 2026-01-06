<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * List properties with filters
     */
    public function index(Request $request)
    {
        $query = Property::with(['city', 'district', 'images'])
            ->active()
            ->purpose($request->purpose)
            ->type($request->type)
            ->inCity($request->city_id);

        // Price range filter
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Bedrooms filter
        if ($request->bedrooms) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // District filter
        if ($request->district_id) {
            $query->where('district_id', $request->district_id);
        }

        // Sort
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $properties = $query->paginate($request->get('per_page', 15));

        return PropertyResource::collection($properties);
    }

    /**
     * Get featured properties
     */
    public function featured()
    {
        $properties = Property::with(['city', 'district', 'images'])
            ->active()
            ->orderBy('view_count', 'desc')
            ->limit(8)
            ->get();

        return PropertyResource::collection($properties);
    }

    /**
     * Get property detail
     */
    public function show(Property $property)
    {
        // Only show active properties or owner's properties
        if ($property->status !== 'active' && 
            (!auth()->check() || auth()->id() !== $property->user_id)) {
            abort(404);
        }

        $property->load(['city', 'district', 'images', 'user']);
        
        // Increment view count
        $property->increment('view_count');

        return new PropertyResource($property);
    }

    /**
     * Create new property (for agents)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'type' => 'required|in:villa,apartment,compound,land,office',
            'purpose' => 'required|in:sale,rent',
            'price' => 'required|numeric|min:0',
            'area_sqm' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'features' => 'nullable|array',
            'rega_ad_license' => 'nullable|string|max:50',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120', // Max 5MB per image
        ]);

        $user = $request->user();
        
        // Only agents can post properties
        if (!$user->isAgent() && !$user->isAdmin()) {
            return response()->json([
                'message' => 'Only agents can post properties',
            ], 403);
        }

        $property = Property::create([
            'user_id' => $user->id,
            'city_id' => $request->city_id,
            'district_id' => $request->district_id,
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'type' => $request->type,
            'purpose' => $request->purpose,
            'price' => $request->price,
            'area_sqm' => $request->area_sqm,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'features' => $request->features,
            'rega_ad_license' => $request->rega_ad_license,
            'status' => 'pending', // Requires admin approval
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');
                $property->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        $property->load(['city', 'district', 'images']);

        return response()->json([
            'message' => 'Property created successfully. Pending approval.',
            'property' => new PropertyResource($property),
        ], 201);
    }

    /**
     * Update property
     */
    public function update(Request $request, Property $property)
    {
        // Only owner or admin can update
        if (auth()->id() !== $property->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title_en' => 'sometimes|string|max:255',
            'title_ar' => 'sometimes|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'city_id' => 'sometimes|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
            'type' => 'sometimes|in:villa,apartment,compound,land,office',
            'purpose' => 'sometimes|in:sale,rent',
            'price' => 'sometimes|numeric|min:0',
            'area_sqm' => 'nullable|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'features' => 'nullable|array',
            'rega_ad_license' => 'nullable|string|max:50',
        ]);

        $property->update($request->only([
            'title_en', 'title_ar', 'description_en', 'description_ar',
            'city_id', 'district_id', 'type', 'purpose', 'price',
            'area_sqm', 'bedrooms', 'bathrooms', 'latitude', 'longitude',
            'features', 'rega_ad_license',
        ]));

        $property->load(['city', 'district', 'images']);

        return new PropertyResource($property);
    }

    /**
     * Delete property
     */
    public function destroy(Property $property)
    {
        // Only owner or admin can delete
        if (auth()->id() !== $property->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Delete images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $property->delete();

        return response()->json([
            'message' => 'Property deleted successfully',
        ]);
    }

    /**
     * Get user's properties (my listings)
     */
    public function myProperties(Request $request)
    {
        $properties = Property::with(['city', 'district', 'images'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return PropertyResource::collection($properties);
    }
}
