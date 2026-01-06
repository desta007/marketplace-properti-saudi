<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get agent user
        $agent = User::where('role', 'agent')->first();
        
        if (!$agent) {
            $this->command->warn('No agent user found. Please run DatabaseSeeder first.');
            return;
        }

        // Get cities and districts
        $riyadh = City::where('name_en', 'Riyadh')->first();
        $jeddah = City::where('name_en', 'Jeddah')->first();
        $dammam = City::where('name_en', 'Dammam')->first();

        $properties = [
            // Riyadh Properties
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Nakheel')->first()?->id,
                'title_en' => 'Modern Villa in Al-Nakheel District',
                'title_ar' => 'فيلا حديثة في حي النخيل',
                'description_en' => 'Stunning modern villa with private pool and garden. Features include smart home system, majlis, and driver room.',
                'description_ar' => 'فيلا حديثة رائعة مع مسبح خاص وحديقة. تشمل نظام المنزل الذكي ومجلس وغرفة سائق.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 4500000,
                'area_sqm' => 650,
                'bedrooms' => 6,
                'bathrooms' => 7,
                'latitude' => 24.7606,
                'longitude' => 46.6358,
                'features' => ['majlis', 'pool', 'garden', 'driver_room', 'maid_room', 'parking'],
                'rega_ad_license' => '7100012345',
                'status' => 'active',
            ],
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Olaya')->first()?->id,
                'title_en' => 'Luxury Apartment in Al-Olaya',
                'title_ar' => 'شقة فاخرة في العليا',
                'description_en' => 'High-rise luxury apartment with stunning city views. Features modern finishes and premium amenities.',
                'description_ar' => 'شقة فاخرة في برج عالي مع إطلالة رائعة على المدينة. تشطيبات حديثة ومرافق ممتازة.',
                'type' => 'apartment',
                'purpose' => 'rent',
                'price' => 120000,
                'area_sqm' => 180,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'latitude' => 24.6943,
                'longitude' => 46.6868,
                'features' => ['elevator', 'ac', 'kitchen', 'parking'],
                'rega_ad_license' => '7100012346',
                'status' => 'active',
            ],
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Malqa')->first()?->id,
                'title_en' => 'Family Compound Villa in Al-Malqa',
                'title_ar' => 'فيلا كمباوند عائلية في الملقا',
                'description_en' => 'Spacious compound villa perfect for families. Community pool, gym, and 24/7 security.',
                'description_ar' => 'فيلا كمباوند واسعة مثالية للعائلات. مسبح مشترك وصالة رياضية وأمن على مدار الساعة.',
                'type' => 'compound',
                'purpose' => 'rent',
                'price' => 180000,
                'area_sqm' => 400,
                'bedrooms' => 4,
                'bathrooms' => 5,
                'latitude' => 24.8143,
                'longitude' => 46.6246,
                'features' => ['pool', 'garden', 'maid_room', 'parking', 'ac'],
                'rega_ad_license' => '7100012347',
                'status' => 'active',
            ],

            // Jeddah Properties
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Al-Shati')->first()?->id,
                'title_en' => 'Seafront Villa in Al-Shati',
                'title_ar' => 'فيلا واجهة بحرية في الشاطئ',
                'description_en' => 'Magnificent seafront villa with private beach access. Features stunning sea views from all rooms.',
                'description_ar' => 'فيلا رائعة على الواجهة البحرية مع وصول خاص للشاطئ. إطلالات بحرية خلابة من جميع الغرف.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 12000000,
                'area_sqm' => 1200,
                'bedrooms' => 8,
                'bathrooms' => 10,
                'latitude' => 21.5573,
                'longitude' => 39.1034,
                'features' => ['majlis', 'pool', 'garden', 'driver_room', 'maid_room', 'parking', 'elevator'],
                'rega_ad_license' => '7200034567',
                'status' => 'active',
            ],
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Obhur')->first()?->id,
                'title_en' => 'Modern Apartment in Obhur',
                'title_ar' => 'شقة عصرية في أبحر',
                'description_en' => 'Newly built apartment near the beach. Modern design with open floor plan.',
                'description_ar' => 'شقة حديثة البناء قرب الشاطئ. تصميم عصري مع مساحة مفتوحة.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 850000,
                'area_sqm' => 160,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'latitude' => 21.6792,
                'longitude' => 39.0998,
                'features' => ['kitchen', 'parking', 'ac'],
                'rega_ad_license' => '7200034568',
                'status' => 'active',
            ],

            // Dammam Properties
            [
                'user_id' => $agent->id,
                'city_id' => $dammam->id,
                'district_id' => District::where('city_id', $dammam->id)->where('name_en', 'Al-Shati')->first()?->id,
                'title_en' => 'Commercial Land in Al-Shati',
                'title_ar' => 'أرض تجارية في الشاطئ',
                'description_en' => 'Prime commercial land with excellent visibility. Suitable for retail or office development.',
                'description_ar' => 'أرض تجارية ممتازة مع موقع استراتيجي. مناسبة للتطوير التجاري أو المكاتب.',
                'type' => 'land',
                'purpose' => 'sale',
                'price' => 3500000,
                'area_sqm' => 2000,
                'bedrooms' => null,
                'bathrooms' => null,
                'latitude' => 26.4521,
                'longitude' => 50.1021,
                'features' => [],
                'rega_ad_license' => '7300056789',
                'status' => 'active',
            ],
            [
                'user_id' => $agent->id,
                'city_id' => $dammam->id,
                'district_id' => District::where('city_id', $dammam->id)->where('name_en', 'Al-Faisaliyah')->first()?->id,
                'title_en' => 'Family Villa in Al-Faisaliyah',
                'title_ar' => 'فيلا عائلية في الفيصلية',
                'description_en' => 'Spacious family villa in quiet neighborhood. Features modern kitchen and large garden.',
                'description_ar' => 'فيلا عائلية واسعة في حي هادئ. مطبخ حديث وحديقة كبيرة.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 2800000,
                'area_sqm' => 450,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'latitude' => 26.4345,
                'longitude' => 50.0876,
                'features' => ['majlis', 'garden', 'maid_room', 'parking', 'kitchen'],
                'rega_ad_license' => '7300056790',
                'status' => 'active',
            ],
            [
                'user_id' => $agent->id,
                'city_id' => $dammam->id,
                'district_id' => District::where('city_id', $dammam->id)->where('name_en', 'Al-Rakah')->first()?->id,
                'title_en' => 'Cozy Apartment for Rent in Al-Rakah',
                'title_ar' => 'شقة مريحة للإيجار في الراكة',
                'description_en' => 'Well-maintained apartment ideal for small families or professionals. Close to amenities.',
                'description_ar' => 'شقة مُصانة جيدًا مثالية للعائلات الصغيرة أو المهنيين. قريبة من المرافق.',
                'type' => 'apartment',
                'purpose' => 'rent',
                'price' => 45000,
                'area_sqm' => 120,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'latitude' => 26.3890,
                'longitude' => 50.0567,
                'features' => ['ac', 'kitchen', 'parking'],
                'rega_ad_license' => '7300056791',
                'status' => 'active',
            ],
        ];

        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }

        $this->command->info('Created ' . count($properties) . ' sample properties.');
    }
}
