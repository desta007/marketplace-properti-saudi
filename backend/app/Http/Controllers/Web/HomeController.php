<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show homepage
     */
    public function index()
    {
        // Get cities with property counts
        $cities = City::where('is_active', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderByDesc('properties_count')
            ->limit(5)
            ->get();

        // Get featured properties
        $featuredProperties = Property::with(['city', 'district', 'images'])
            ->where('status', 'active')
            ->orderByDesc('view_count')
            ->limit(8)
            ->get();

        // Stats
        $stats = [
            'properties' => Property::where('status', 'active')->count(),
            'agents' => \App\Models\User::where('role', 'agent')->count(),
            'cities' => City::where('is_active', true)->count(),
        ];

        return view('web.home', compact('cities', 'featuredProperties', 'stats'));
    }
}
