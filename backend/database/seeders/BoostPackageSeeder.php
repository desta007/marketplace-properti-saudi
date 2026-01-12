<?php

namespace Database\Seeders;

use App\Models\BoostPackage;
use Illuminate\Database\Seeder;

class BoostPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            // Featured packages
            [
                'name_en' => 'Featured 7 Days',
                'name_ar' => 'مميز 7 أيام',
                'slug' => 'featured-7',
                'duration_days' => 7,
                'price' => 50,
                'boost_type' => 'featured',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name_en' => 'Featured 14 Days',
                'name_ar' => 'مميز 14 يوم',
                'slug' => 'featured-14',
                'duration_days' => 14,
                'price' => 100,
                'boost_type' => 'featured',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name_en' => 'Featured 30 Days',
                'name_ar' => 'مميز 30 يوم',
                'slug' => 'featured-30',
                'duration_days' => 30,
                'price' => 150,
                'boost_type' => 'featured',
                'is_active' => true,
                'sort_order' => 3,
            ],
            // Top Pick packages
            [
                'name_en' => 'Top Pick 7 Days',
                'name_ar' => 'الأفضل 7 أيام',
                'slug' => 'top-pick-7',
                'duration_days' => 7,
                'price' => 100,
                'boost_type' => 'top_pick',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name_en' => 'Top Pick 14 Days',
                'name_ar' => 'الأفضل 14 يوم',
                'slug' => 'top-pick-14',
                'duration_days' => 14,
                'price' => 180,
                'boost_type' => 'top_pick',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name_en' => 'Top Pick 30 Days',
                'name_ar' => 'الأفضل 30 يوم',
                'slug' => 'top-pick-30',
                'duration_days' => 30,
                'price' => 300,
                'boost_type' => 'top_pick',
                'is_active' => true,
                'sort_order' => 6,
            ],
            // Premium packages
            [
                'name_en' => 'Premium 7 Days',
                'name_ar' => 'بريميوم 7 أيام',
                'slug' => 'premium-7',
                'duration_days' => 7,
                'price' => 200,
                'boost_type' => 'premium',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name_en' => 'Premium 14 Days',
                'name_ar' => 'بريميوم 14 يوم',
                'slug' => 'premium-14',
                'duration_days' => 14,
                'price' => 350,
                'boost_type' => 'premium',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name_en' => 'Premium 30 Days',
                'name_ar' => 'بريميوم 30 يوم',
                'slug' => 'premium-30',
                'duration_days' => 30,
                'price' => 500,
                'boost_type' => 'premium',
                'is_active' => true,
                'sort_order' => 9,
            ],
        ];

        foreach ($packages as $package) {
            BoostPackage::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}
