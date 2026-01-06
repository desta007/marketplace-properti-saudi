<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * List properties with filters
     */
    public function index(Request $request)
    {
        $query = Property::with(['city', 'district', 'images'])
            ->where('status', 'active');

        // Apply filters
        if ($request->city) {
            $query->where('city_id', $request->city);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->purpose) {
            $query->where('purpose', $request->purpose);
        }
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->bedrooms) {
            $query->where('bedrooms', '>=', $request->bedrooms);
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

        $properties = $query->paginate(12);
        $cities = City::where('is_active', true)->orderBy('name_en')->get();

        return view('web.properties.index', compact('properties', 'cities'));
    }

    /**
     * Show property detail
     */
    public function show(Property $property)
    {
        // Only show active properties
        if ($property->status !== 'active') {
            abort(404);
        }

        $property->load(['city', 'district', 'images', 'user']);
        $property->increment('view_count');

        // Similar properties
        $similarProperties = Property::with(['city', 'images'])
            ->where('status', 'active')
            ->where('id', '!=', $property->id)
            ->where('city_id', $property->city_id)
            ->where('type', $property->type)
            ->limit(4)
            ->get();

        return view('web.properties.show', compact('property', 'similarProperties'));
    }

    /**
     * User favorites
     */
    public function favorites()
    {
        $properties = auth()->user()
            ->favorites()
            ->with(['city', 'images'])
            ->where('status', 'active')
            ->paginate(12);

        return view('web.properties.favorites', compact('properties'));
    }

    /**
     * Toggle favorite
     */
    public function toggleFavorite(Property $property)
    {
        $user = auth()->user();
        
        if ($user->favorites()->where('property_id', $property->id)->exists()) {
            $user->favorites()->detach($property->id);
            $message = 'Removed from favorites';
        } else {
            $user->favorites()->attach($property->id);
            $message = 'Added to favorites';
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
