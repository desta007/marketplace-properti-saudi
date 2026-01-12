<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            ['name_en' => 'Swimming Pool', 'name_ar' => 'مسبح', 'icon' => '🏊', 'sort_order' => 1],
            ['name_en' => 'Garden', 'name_ar' => 'حديقة', 'icon' => '🌳', 'sort_order' => 2],
            ['name_en' => 'Garage', 'name_ar' => 'موقف سيارات', 'icon' => '🚗', 'sort_order' => 3],
            ['name_en' => 'Security', 'name_ar' => 'حراسة أمنية', 'icon' => '🔒', 'sort_order' => 4],
            ['name_en' => 'Gym', 'name_ar' => 'صالة رياضية', 'icon' => '💪', 'sort_order' => 5],
            ['name_en' => 'Central AC', 'name_ar' => 'تكييف مركزي', 'icon' => '❄️', 'sort_order' => 6],
            ['name_en' => 'Elevator', 'name_ar' => 'مصعد', 'icon' => '🛗', 'sort_order' => 7],
            ['name_en' => 'Maid Room', 'name_ar' => 'غرفة خادمة', 'icon' => '🛏️', 'sort_order' => 8],
            ['name_en' => 'Driver Room', 'name_ar' => 'غرفة سائق', 'icon' => '🚙', 'sort_order' => 9],
            ['name_en' => 'Storage', 'name_ar' => 'مخزن', 'icon' => '📦', 'sort_order' => 10],
            ['name_en' => 'Balcony', 'name_ar' => 'شرفة', 'icon' => '🏠', 'sort_order' => 11],
            ['name_en' => 'Private Pool', 'name_ar' => 'مسبح خاص', 'icon' => '🏖️', 'sort_order' => 12],
            ['name_en' => 'Covered Parking', 'name_ar' => 'موقف مغطى', 'icon' => '🅿️', 'sort_order' => 13],
            ['name_en' => 'Smart Home', 'name_ar' => 'منزل ذكي', 'icon' => '🤖', 'sort_order' => 14],
            ['name_en' => 'Furnished', 'name_ar' => 'مفروش', 'icon' => '🛋️', 'sort_order' => 15],
            ['name_en' => 'Pet Friendly', 'name_ar' => 'مسموح بالحيوانات', 'icon' => '🐕', 'sort_order' => 16],
            ['name_en' => 'Built-in Kitchen', 'name_ar' => 'مطبخ مجهز', 'icon' => '🍳', 'sort_order' => 17],
            ['name_en' => 'Walk-in Closet', 'name_ar' => 'غرفة ملابس', 'icon' => '👔', 'sort_order' => 18],
            ['name_en' => 'Study Room', 'name_ar' => 'غرفة مكتب', 'icon' => '📚', 'sort_order' => 19],
            ['name_en' => 'Basement', 'name_ar' => 'قبو', 'icon' => '🏚️', 'sort_order' => 20],
            ['name_en' => 'Majlis', 'name_ar' => 'مجلس', 'icon' => '🪑', 'sort_order' => 21],
        ];

        foreach ($features as $featureData) {
            Feature::firstOrCreate(
                ['name_en' => $featureData['name_en']],
                $featureData
            );
        }

        $this->command->info('Created ' . count($features) . ' features.');
    }
}
