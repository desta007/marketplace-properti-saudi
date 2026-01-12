<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyImage;
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
        // Only show active properties to non-owners
        if ($property->status !== 'active' && (!auth()->check() || auth()->id() !== $property->user_id)) {
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
     * Show create property form
     */
    public function create()
    {
        $cities = City::where('is_active', true)->orderBy('name_en')->get();
        return view('web.properties.create', compact('cities'));
    }

    /**
     * Store new property
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'features' => 'nullable|array',
            'virtual_tour_url' => 'nullable|url|max:500',
            'virtual_tour_type' => 'nullable|in:matterport,youtube_360,custom',
            'rega_ad_license' => 'nullable|string|max:50',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
        ]);

        $property = Property::create([
            'user_id' => auth()->id(),
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
            'virtual_tour_url' => $request->virtual_tour_url,
            'virtual_tour_type' => $request->virtual_tour_type,
            'rega_ad_license' => $request->rega_ad_license,
            'status' => 'pending',
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('my-properties')
            ->with('success', 'Property submitted for review. It will be published after admin approval.');
    }

    /**
     * Show edit property form
     */
    public function edit(Property $property)
    {
        // Authorization
        if (auth()->id() !== $property->user_id) {
            abort(403);
        }

        $cities = City::where('is_active', true)->orderBy('name_en')->get();
        $districts = $property->city?->districts ?? collect();

        return view('web.properties.create', compact('property', 'cities', 'districts'));
    }

    /**
     * Update property
     */
    public function update(Request $request, Property $property)
    {
        // Authorization
        if (auth()->id() !== $property->user_id) {
            abort(403);
        }

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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'features' => 'nullable|array',
            'virtual_tour_url' => 'nullable|url|max:500',
            'virtual_tour_type' => 'nullable|in:matterport,youtube_360,custom',
            'rega_ad_license' => 'nullable|string|max:50',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
        ]);

        $property->update($request->only([
            'city_id',
            'district_id',
            'title_en',
            'title_ar',
            'description_en',
            'description_ar',
            'type',
            'purpose',
            'price',
            'area_sqm',
            'bedrooms',
            'bathrooms',
            'latitude',
            'longitude',
            'virtual_tour_url',
            'virtual_tour_type',
            'rega_ad_license',
        ]));

        // Handle features separately since it's an array
        $property->update(['features' => $request->features]);

        // Handle new images
        if ($request->hasFile('images')) {
            $startIndex = $property->images()->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'is_primary' => $startIndex === 0 && $index === 0,
                    'sort_order' => $startIndex + $index,
                ]);
            }
        }

        return redirect()->route('my-properties')
            ->with('success', 'Property updated successfully.');
    }

    /**
     * Agent's property list
     */
    public function myProperties()
    {
        $properties = auth()->user()
            ->properties()
            ->with(['city', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('web.properties.my-properties', compact('properties'));
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

