<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            // Riyadh Districts
            'Riyadh' => [
                ['name_en' => 'Al-Nakheel', 'name_ar' => 'النخيل'],
                ['name_en' => 'Al-Olaya', 'name_ar' => 'العليا'],
                ['name_en' => 'Al-Hamra', 'name_ar' => 'الحمراء'],
                ['name_en' => 'Al-Malqa', 'name_ar' => 'الملقا'],
                ['name_en' => 'Al-Yasmin', 'name_ar' => 'الياسمين'],
                ['name_en' => 'Al-Sahafa', 'name_ar' => 'الصحافة'],
                ['name_en' => 'Al-Rabie', 'name_ar' => 'الربيع'],
                ['name_en' => 'Diplomatic Quarter', 'name_ar' => 'الحي الدبلوماسي'],
                ['name_en' => 'Al-Sulimaniyah', 'name_ar' => 'السليمانية'],
                ['name_en' => 'Al-Wurud', 'name_ar' => 'الورود'],
            ],
            // Jeddah Districts
            'Jeddah' => [
                ['name_en' => 'Al-Rawdah', 'name_ar' => 'الروضة'],
                ['name_en' => 'Al-Shati', 'name_ar' => 'الشاطئ'],
                ['name_en' => 'Al-Hamra', 'name_ar' => 'الحمراء'],
                ['name_en' => 'Al-Zahra', 'name_ar' => 'الزهراء'],
                ['name_en' => 'Al-Salamah', 'name_ar' => 'السلامة'],
                ['name_en' => 'Obhur', 'name_ar' => 'أبحر'],
                ['name_en' => 'Al-Murjan', 'name_ar' => 'المرجان'],
                ['name_en' => 'Al-Andalus', 'name_ar' => 'الأندلس'],
            ],
            // Dammam Districts
            'Dammam' => [
                ['name_en' => 'Al-Shati', 'name_ar' => 'الشاطئ'],
                ['name_en' => 'Al-Faisaliyah', 'name_ar' => 'الفيصلية'],
                ['name_en' => 'Al-Rakah', 'name_ar' => 'الراكة'],
                ['name_en' => 'Al-Mazruiyah', 'name_ar' => 'المزروعية'],
                ['name_en' => 'Al-Murjan', 'name_ar' => 'المرجان'],
            ],
            // Khobar Districts
            'Khobar' => [
                ['name_en' => 'Al-Khobar North', 'name_ar' => 'الخبر الشمالية'],
                ['name_en' => 'Al-Yarmouk', 'name_ar' => 'اليرموك'],
                ['name_en' => 'Al-Thuqbah', 'name_ar' => 'الثقبة'],
                ['name_en' => 'Al-Aqrabiyah', 'name_ar' => 'العقربية'],
                ['name_en' => 'Corniche', 'name_ar' => 'الكورنيش'],
            ],
            // Makkah Districts
            'Makkah' => [
                ['name_en' => 'Al-Aziziyah', 'name_ar' => 'العزيزية'],
                ['name_en' => 'Al-Awali', 'name_ar' => 'العوالي'],
                ['name_en' => 'Al-Shishaa', 'name_ar' => 'الششة'],
                ['name_en' => 'Al-Zahra', 'name_ar' => 'الزاهر'],
            ],
            // Madinah Districts
            'Madinah' => [
                ['name_en' => 'Al-Haram Area', 'name_ar' => 'منطقة الحرم'],
                ['name_en' => 'Al-Azhari', 'name_ar' => 'الأزهري'],
                ['name_en' => 'Al-Ranuna', 'name_ar' => 'الرانوناء'],
                ['name_en' => 'Al-Khalidiyah', 'name_ar' => 'الخالدية'],
            ],
        ];

        foreach ($districts as $cityName => $cityDistricts) {
            $city = City::where('name_en', $cityName)->first();
            
            if ($city) {
                foreach ($cityDistricts as $district) {
                    District::create([
                        'city_id' => $city->id,
                        'name_en' => $district['name_en'],
                        'name_ar' => $district['name_ar'],
                        'slug' => Str::slug($district['name_en']),
                    ]);
                }
            }
        }
    }
}
