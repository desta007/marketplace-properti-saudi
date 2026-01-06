<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name_en' => 'Riyadh',
                'name_ar' => 'الرياض',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
            ],
            [
                'name_en' => 'Jeddah',
                'name_ar' => 'جدة',
                'latitude' => 21.4858,
                'longitude' => 39.1925,
            ],
            [
                'name_en' => 'Makkah',
                'name_ar' => 'مكة المكرمة',
                'latitude' => 21.4225,
                'longitude' => 39.8262,
            ],
            [
                'name_en' => 'Madinah',
                'name_ar' => 'المدينة المنورة',
                'latitude' => 24.5247,
                'longitude' => 39.5692,
            ],
            [
                'name_en' => 'Dammam',
                'name_ar' => 'الدمام',
                'latitude' => 26.4207,
                'longitude' => 50.0888,
            ],
            [
                'name_en' => 'Khobar',
                'name_ar' => 'الخبر',
                'latitude' => 26.2794,
                'longitude' => 50.2083,
            ],
            [
                'name_en' => 'Dhahran',
                'name_ar' => 'الظهران',
                'latitude' => 26.2823,
                'longitude' => 50.1003,
            ],
            [
                'name_en' => 'Tabuk',
                'name_ar' => 'تبوك',
                'latitude' => 28.3835,
                'longitude' => 36.5662,
            ],
            [
                'name_en' => 'Abha',
                'name_ar' => 'أبها',
                'latitude' => 18.2164,
                'longitude' => 42.5053,
            ],
            [
                'name_en' => 'Khamis Mushait',
                'name_ar' => 'خميس مشيط',
                'latitude' => 18.3062,
                'longitude' => 42.7293,
            ],
            [
                'name_en' => 'Taif',
                'name_ar' => 'الطائف',
                'latitude' => 21.2703,
                'longitude' => 40.4158,
            ],
            [
                'name_en' => 'Buraidah',
                'name_ar' => 'بريدة',
                'latitude' => 26.3260,
                'longitude' => 43.9750,
            ],
            [
                'name_en' => 'Jubail',
                'name_ar' => 'الجبيل',
                'latitude' => 27.0046,
                'longitude' => 49.6225,
            ],
        ];

        foreach ($cities as $city) {
            City::create([
                'name_en' => $city['name_en'],
                'name_ar' => $city['name_ar'],
                'slug' => Str::slug($city['name_en']),
                'latitude' => $city['latitude'],
                'longitude' => $city['longitude'],
                'is_active' => true,
            ]);
        }
    }
}
