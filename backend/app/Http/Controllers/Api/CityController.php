<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\DistrictResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * List all active cities
     */
    public function index()
    {
        $cities = City::where('is_active', true)
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('name_en')
            ->get();

        return CityResource::collection($cities);
    }

    /**
     * Get city details with districts
     */
    public function show(City $city)
    {
        $city->load('districts');
        $city->loadCount(['properties' => function ($query) {
            $query->where('status', 'active');
        }]);

        return new CityResource($city);
    }

    /**
     * Get districts for a city
     */
    public function districts(City $city)
    {
        $districts = $city->districts()
            ->withCount(['properties' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('name_en')
            ->get();

        return DistrictResource::collection($districts);
    }
}
