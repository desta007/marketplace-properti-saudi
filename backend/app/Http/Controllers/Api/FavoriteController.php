<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Get user's favorite properties
     */
    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favorites()
            ->with(['city', 'district', 'images'])
            ->active()
            ->orderBy('favorites.created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return PropertyResource::collection($favorites);
    }

    /**
     * Add property to favorites
     */
    public function store(Property $property)
    {
        // Only active properties can be favorited
        if ($property->status !== 'active') {
            return response()->json([
                'message' => 'Property not available',
            ], 404);
        }

        $user = auth()->user();
        
        // Check if already favorited
        if ($user->favorites()->where('property_id', $property->id)->exists()) {
            return response()->json([
                'message' => 'Property already in favorites',
            ], 409);
        }

        $user->favorites()->attach($property->id);

        return response()->json([
            'message' => 'Added to favorites',
            'property_id' => $property->id,
        ]);
    }

    /**
     * Remove property from favorites
     */
    public function destroy(Property $property)
    {
        auth()->user()->favorites()->detach($property->id);

        return response()->json([
            'message' => 'Removed from favorites',
            'property_id' => $property->id,
        ]);
    }

    /**
     * Check if property is favorited
     */
    public function check(Property $property)
    {
        $isFavorited = auth()->user()
            ->favorites()
            ->where('property_id', $property->id)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited,
            'property_id' => $property->id,
        ]);
    }
}
