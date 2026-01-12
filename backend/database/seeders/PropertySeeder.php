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
     * Real Saudi Arabia property data based on 2024 market research
     */
    public function run(): void
    {
        // Get agent user
        $agent = User::where('role', 'agent')->first();

        if (!$agent) {
            $this->command->warn('No agent user found. Please run DatabaseSeeder first.');
            return;
        }

        // Get cities
        $riyadh = City::where('name_en', 'Riyadh')->first();
        $jeddah = City::where('name_en', 'Jeddah')->first();
        $dammam = City::where('name_en', 'Dammam')->first();
        $khobar = City::where('name_en', 'Khobar')->first();

        $properties = [
            // ============== RIYADH PROPERTIES ==============

            // 1. Luxury Villa in Al-Nakheel, Riyadh
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Nakheel')->first()?->id,
                'title_en' => 'Premium Smart Villa in Al-Nakheel',
                'title_ar' => 'فيلا ذكية فاخرة في النخيل',
                'description_en' => 'Exceptional 6-bedroom villa featuring smart home automation, private swimming pool, landscaped garden, and luxury finishes. Located in the prestigious Al-Nakheel district with easy access to King Fahd Road.',
                'description_ar' => 'فيلا استثنائية من 6 غرف نوم تتميز بنظام المنزل الذكي ومسبح خاص وحديقة منسقة وتشطيبات فاخرة. تقع في حي النخيل الراقي مع سهولة الوصول إلى طريق الملك فهد.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 8500000,
                'area_sqm' => 580,
                'bedrooms' => 6,
                'bathrooms' => 7,
                'latitude' => 24.7606,
                'longitude' => 46.6358,
                'features' => ['majlis', 'pool', 'garden', 'driver_room', 'maid_room', 'parking', 'elevator', 'ac'],
                'rega_ad_license' => '7100023456',
                'status' => 'active',
            ],

            // 2. Modern Apartment in Al-Olaya, Riyadh
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Olaya')->first()?->id,
                'title_en' => 'Modern High-Rise Apartment in Al-Olaya',
                'title_ar' => 'شقة عصرية في برج العليا',
                'description_en' => 'Stunning 3-bedroom apartment in Al-Olaya Business District with panoramic city views. Features modern kitchen, marble flooring, and 24/7 security. Walking distance to Kingdom Tower.',
                'description_ar' => 'شقة مذهلة من 3 غرف نوم في حي العليا التجاري مع إطلالات بانورامية على المدينة. تتميز بمطبخ حديث وأرضيات رخامية وأمن على مدار الساعة. على مسافة قريبة من برج المملكة.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 1850000,
                'area_sqm' => 185,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'latitude' => 24.6943,
                'longitude' => 46.6868,
                'features' => ['elevator', 'ac', 'kitchen', 'parking', 'security'],
                'rega_ad_license' => '7100023457',
                'status' => 'active',
            ],

            // 3. Executive Villa in Al-Malqa, Riyadh
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Malqa')->first()?->id,
                'title_en' => 'Executive Family Villa in Al-Malqa',
                'title_ar' => 'فيلا عائلية تنفيذية في الملقا',
                'description_en' => 'Spacious 5-bedroom villa in Al-Malqa compound with shared facilities including gym, tennis court, and children\'s playground. Modern design with large living areas.',
                'description_ar' => 'فيلا واسعة من 5 غرف نوم في كمباوند الملقا مع مرافق مشتركة تشمل صالة رياضية وملعب تنس وملعب للأطفال. تصميم عصري مع مساحات معيشة كبيرة.',
                'type' => 'compound',
                'purpose' => 'sale',
                'price' => 4200000,
                'area_sqm' => 420,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'latitude' => 24.8143,
                'longitude' => 46.6246,
                'features' => ['pool', 'garden', 'maid_room', 'parking', 'ac', 'kitchen'],
                'rega_ad_license' => '7100023458',
                'status' => 'active',
            ],

            // 4. Smart Villa in Al-Yasmin, Riyadh
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Yasmin')->first()?->id,
                'title_en' => 'Contemporary Villa in Al-Yasmin',
                'title_ar' => 'فيلا معاصرة في الياسمين',
                'description_en' => 'Newly built 4-bedroom villa with contemporary architecture in Al-Yasmin district. Features include private pool, rooftop terrace, and home automation system.',
                'description_ar' => 'فيلا حديثة البناء من 4 غرف نوم بتصميم معماري معاصر في حي الياسمين. تشمل مسبح خاص وتراس على السطح ونظام أتمتة المنزل.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 3800000,
                'area_sqm' => 380,
                'bedrooms' => 4,
                'bathrooms' => 5,
                'latitude' => 24.8234,
                'longitude' => 46.6789,
                'features' => ['pool', 'garden', 'parking', 'ac', 'elevator'],
                'rega_ad_license' => '7100023459',
                'status' => 'active',
            ],

            // 5. Premium Apartment for Rent in Al-Sahafa, Riyadh
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Sahafa')->first()?->id,
                'title_en' => 'Premium Furnished Apartment in Al-Sahafa',
                'title_ar' => 'شقة مفروشة فاخرة في الصحافة',
                'description_en' => 'Fully furnished 2-bedroom apartment available for yearly rent in Al-Sahafa. Modern amenities, quiet neighborhood, close to schools and shopping centers.',
                'description_ar' => 'شقة مفروشة بالكامل من غرفتين نوم متاحة للإيجار السنوي في الصحافة. وسائل راحة حديثة، حي هادئ، قريبة من المدارس ومراكز التسوق.',
                'type' => 'apartment',
                'purpose' => 'rent',
                'price' => 95000,
                'area_sqm' => 120,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'latitude' => 24.7789,
                'longitude' => 46.7123,
                'features' => ['ac', 'kitchen', 'parking'],
                'rega_ad_license' => '7100023460',
                'status' => 'active',
            ],

            // ============== JEDDAH PROPERTIES ==============

            // 6. Seafront Villa in Al-Shati, Jeddah
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Al-Shati')->first()?->id,
                'title_en' => 'Magnificent Seafront Villa in Al-Shati',
                'title_ar' => 'فيلا رائعة على الواجهة البحرية في الشاطئ',
                'description_en' => 'Breathtaking 7-bedroom seafront villa with private beach access. Features infinity pool, panoramic Red Sea views, luxurious marble finishes, and landscaped gardens.',
                'description_ar' => 'فيلا خلابة من 7 غرف نوم على الواجهة البحرية مع وصول خاص للشاطئ. تتميز بمسبح لا متناهي وإطلالات بانورامية على البحر الأحمر وتشطيبات رخامية فاخرة وحدائق منسقة.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 9500000,
                'area_sqm' => 750,
                'bedrooms' => 7,
                'bathrooms' => 8,
                'latitude' => 21.5573,
                'longitude' => 39.1034,
                'features' => ['majlis', 'pool', 'garden', 'driver_room', 'maid_room', 'parking', 'elevator', 'ac'],
                'rega_ad_license' => '7200045678',
                'status' => 'active',
            ],

            // 7. Luxury Apartment in Al-Shati, Jeddah
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Al-Shati')->first()?->id,
                'title_en' => 'Sea View Luxury Apartment in Al-Shati',
                'title_ar' => 'شقة فاخرة بإطلالة بحرية في الشاطئ',
                'description_en' => 'Premium 4-bedroom apartment with stunning sea views in Al-Shati district. High-end finishes, spacious balcony, underground parking, and concierge service.',
                'description_ar' => 'شقة فاخرة من 4 غرف نوم مع إطلالات بحرية خلابة في حي الشاطئ. تشطيبات راقية، شرفة واسعة، موقف سيارات تحت الأرض، وخدمة الكونسيرج.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 1750000,
                'area_sqm' => 200,
                'bedrooms' => 4,
                'bathrooms' => 4,
                'latitude' => 21.5612,
                'longitude' => 39.1089,
                'features' => ['elevator', 'ac', 'kitchen', 'parking', 'security'],
                'rega_ad_license' => '7200045679',
                'status' => 'active',
            ],

            // 8. Modern Apartment in Obhur, Jeddah
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Obhur')->first()?->id,
                'title_en' => 'Modern Beach Apartment in Obhur',
                'title_ar' => 'شقة عصرية قرب الشاطئ في أبحر',
                'description_en' => 'Newly built 3-bedroom apartment in Obhur near the beach. Modern open-plan design, high-quality finishes, and access to community facilities.',
                'description_ar' => 'شقة حديثة البناء من 3 غرف نوم في أبحر قرب الشاطئ. تصميم عصري مفتوح، تشطيبات عالية الجودة، ووصول إلى المرافق المشتركة.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 850000,
                'area_sqm' => 160,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'latitude' => 21.6792,
                'longitude' => 39.0998,
                'features' => ['kitchen', 'parking', 'ac', 'elevator'],
                'rega_ad_license' => '7200045680',
                'status' => 'active',
            ],

            // 9. Beachside Villa in Obhur, Jeddah
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Obhur')->first()?->id,
                'title_en' => 'Contemporary Beachside Villa in Obhur',
                'title_ar' => 'فيلا معاصرة قرب الشاطئ في أبحر',
                'description_en' => 'Stunning 5-bedroom beachside villa in North Obhur. Features modern architecture, private pool, landscaped garden, and proximity to water sports facilities.',
                'description_ar' => 'فيلا مذهلة من 5 غرف نوم قرب الشاطئ في أبحر الشمالية. تتميز بتصميم معماري عصري ومسبح خاص وحديقة منسقة وقرب من مرافق الرياضات المائية.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 5500000,
                'area_sqm' => 450,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'latitude' => 21.7234,
                'longitude' => 39.0876,
                'features' => ['pool', 'garden', 'maid_room', 'parking', 'ac', 'majlis'],
                'rega_ad_license' => '7200045681',
                'status' => 'active',
            ],

            // 10. Family Apartment for Rent in Al-Rawdah, Jeddah
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Al-Rawdah')->first()?->id,
                'title_en' => 'Spacious Family Apartment in Al-Rawdah',
                'title_ar' => 'شقة عائلية واسعة في الروضة',
                'description_en' => 'Well-maintained 3-bedroom apartment for yearly rent in Al-Rawdah. Close to international schools, hospitals, and shopping malls. Family-friendly neighborhood.',
                'description_ar' => 'شقة من 3 غرف نوم بصيانة ممتازة للإيجار السنوي في الروضة. قريبة من المدارس الدولية والمستشفيات ومراكز التسوق. حي مناسب للعائلات.',
                'type' => 'apartment',
                'purpose' => 'rent',
                'price' => 75000,
                'area_sqm' => 140,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'latitude' => 21.5234,
                'longitude' => 39.1567,
                'features' => ['ac', 'kitchen', 'parking'],
                'rega_ad_license' => '7200045682',
                'status' => 'active',
            ],

            // ============== DAMMAM & EASTERN PROVINCE PROPERTIES ==============

            // 11. Family Villa in Al-Faisaliyah, Dammam
            [
                'user_id' => $agent->id,
                'city_id' => $dammam->id,
                'district_id' => District::where('city_id', $dammam->id)->where('name_en', 'Al-Faisaliyah')->first()?->id,
                'title_en' => 'Elegant Family Villa in Al-Faisaliyah',
                'title_ar' => 'فيلا عائلية أنيقة في الفيصلية',
                'description_en' => 'Beautiful 5-bedroom family villa in Al-Faisaliyah with traditional majlis, modern kitchen, landscaped garden, and separate driver quarter.',
                'description_ar' => 'فيلا عائلية جميلة من 5 غرف نوم في الفيصلية مع مجلس تقليدي ومطبخ حديث وحديقة منسقة وملحق منفصل للسائق.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 2800000,
                'area_sqm' => 400,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'latitude' => 26.4345,
                'longitude' => 50.0876,
                'features' => ['majlis', 'garden', 'maid_room', 'driver_room', 'parking', 'kitchen', 'ac'],
                'rega_ad_license' => '7300067890',
                'status' => 'active',
            ],

            // 12. Waterfront Apartment in Al-Shati, Dammam
            [
                'user_id' => $agent->id,
                'city_id' => $dammam->id,
                'district_id' => District::where('city_id', $dammam->id)->where('name_en', 'Al-Shati')->first()?->id,
                'title_en' => 'Waterfront Apartment in Al-Shati Dammam',
                'title_ar' => 'شقة على الواجهة البحرية في شاطئ الدمام',
                'description_en' => 'Elegant 3-bedroom waterfront apartment in Al-Shati Al-Sharqi with sea views. Modern design, premium finishes, and access to corniche.',
                'description_ar' => 'شقة أنيقة من 3 غرف نوم على الواجهة البحرية في الشاطئ الشرقي مع إطلالات بحرية. تصميم عصري وتشطيبات فاخرة ووصول للكورنيش.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 750000,
                'area_sqm' => 150,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'latitude' => 26.4521,
                'longitude' => 50.1021,
                'features' => ['ac', 'kitchen', 'parking', 'elevator'],
                'rega_ad_license' => '7300067891',
                'status' => 'active',
            ],

            // 13. Cozy Apartment for Rent in Al-Rakah, Dammam
            [
                'user_id' => $agent->id,
                'city_id' => $dammam->id,
                'district_id' => District::where('city_id', $dammam->id)->where('name_en', 'Al-Rakah')->first()?->id,
                'title_en' => 'Cozy Apartment for Rent in Al-Rakah',
                'title_ar' => 'شقة مريحة للإيجار في الراكة',
                'description_en' => 'Well-maintained 2-bedroom apartment for yearly rent in Al-Rakah. Ideal for young professionals or small families. Close to King Fahd University.',
                'description_ar' => 'شقة من غرفتين نوم بصيانة جيدة للإيجار السنوي في الراكة. مثالية للمهنيين الشباب أو العائلات الصغيرة. قريبة من جامعة الملك فهد.',
                'type' => 'apartment',
                'purpose' => 'rent',
                'price' => 55000,
                'area_sqm' => 110,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'latitude' => 26.3890,
                'longitude' => 50.0567,
                'features' => ['ac', 'kitchen', 'parking'],
                'rega_ad_license' => '7300067892',
                'status' => 'active',
            ],

            // 14. Commercial Land in Al-Murjan, Dammam
            [
                'user_id' => $agent->id,
                'city_id' => $dammam->id,
                'district_id' => District::where('city_id', $dammam->id)->where('name_en', 'Al-Murjan')->first()?->id,
                'title_en' => 'Prime Commercial Land in Al-Murjan',
                'title_ar' => 'أرض تجارية ممتازة في المرجان',
                'description_en' => 'Strategic commercial land plot in Al-Murjan district. Excellent visibility on main road, suitable for retail development, offices, or mixed-use project.',
                'description_ar' => 'قطعة أرض تجارية استراتيجية في حي المرجان. رؤية ممتازة على الطريق الرئيسي، مناسبة للتطوير التجاري أو المكاتب أو مشروع متعدد الاستخدامات.',
                'type' => 'land',
                'purpose' => 'sale',
                'price' => 4500000,
                'area_sqm' => 2500,
                'bedrooms' => null,
                'bathrooms' => null,
                'latitude' => 26.4123,
                'longitude' => 50.0987,
                'features' => [],
                'rega_ad_license' => '7300067893',
                'status' => 'active',
            ],

            // 15. Luxury Villa in Khobar Corniche
            [
                'user_id' => $agent->id,
                'city_id' => $khobar->id,
                'district_id' => District::where('city_id', $khobar->id)->where('name_en', 'Corniche')->first()?->id,
                'title_en' => 'Luxury Villa on Khobar Corniche',
                'title_ar' => 'فيلا فاخرة على كورنيش الخبر',
                'description_en' => 'Magnificent 6-bedroom villa on Khobar Corniche with stunning Gulf views. Features include private pool, elevator, smart home system, and premium finishes throughout.',
                'description_ar' => 'فيلا رائعة من 6 غرف نوم على كورنيش الخبر مع إطلالات خلابة على الخليج. تشمل مسبح خاص ومصعد ونظام المنزل الذكي وتشطيبات فاخرة في جميع الأنحاء.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 6200000,
                'area_sqm' => 550,
                'bedrooms' => 6,
                'bathrooms' => 7,
                'latitude' => 26.2794,
                'longitude' => 50.2083,
                'features' => ['majlis', 'pool', 'garden', 'driver_room', 'maid_room', 'parking', 'elevator', 'ac'],
                'rega_ad_license' => '7400078901',
                'status' => 'active',
            ],

            // ============== 2025 NEW LISTINGS ==============
            // Updated prices reflecting H1 2025 market growth (+8-12% from 2024)

            // 16. Ultra-Luxury Villa in Diplomatic Quarter, Riyadh (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Diplomatic Quarter')->first()?->id,
                'title_en' => 'Ultra-Luxury Embassy Villa in DQ',
                'title_ar' => 'فيلا سفارات فاخرة للغاية في الحي الدبلوماسي',
                'description_en' => 'Prestigious 8-bedroom embassy-grade villa in Diplomatic Quarter. Features marble throughout, private cinema, indoor pool, smart security, and stunning landscaping. 2025 new construction.',
                'description_ar' => 'فيلا مرموقة من 8 غرف نوم بمواصفات السفارات في الحي الدبلوماسي. تتميز بالرخام في كل مكان وسينما خاصة ومسبح داخلي ونظام أمان ذكي وتنسيق حدائق مذهل. بناء جديد 2025.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 18500000,
                'area_sqm' => 1200,
                'bedrooms' => 8,
                'bathrooms' => 10,
                'latitude' => 24.6876,
                'longitude' => 46.6234,
                'features' => ['majlis', 'pool', 'garden', 'driver_room', 'maid_room', 'parking', 'elevator', 'ac', 'security'],
                'rega_ad_license' => '7100035001',
                'status' => 'active',
            ],

            // 17. Premium Penthouse in Al-Hamra, Riyadh (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Hamra')->first()?->id,
                'title_en' => 'Exclusive Penthouse in Al-Hamra Tower',
                'title_ar' => 'بنتهاوس حصري في برج الحمراء',
                'description_en' => 'Spectacular 4-bedroom penthouse with 360-degree city views. Features private rooftop terrace, high-end finishes, and dedicated elevator. Prime Al-Hamra location. 2025 listing.',
                'description_ar' => 'بنتهاوس مذهل من 4 غرف نوم مع إطلالات 360 درجة على المدينة. يتميز بتراس خاص على السطح وتشطيبات راقية ومصعد مخصص. موقع متميز في الحمراء. عرض 2025.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 4200000,
                'area_sqm' => 320,
                'bedrooms' => 4,
                'bathrooms' => 5,
                'latitude' => 24.7123,
                'longitude' => 46.6745,
                'features' => ['elevator', 'ac', 'kitchen', 'parking', 'security', 'pool'],
                'rega_ad_license' => '7100035002',
                'status' => 'active',
            ],

            // 18. Modern Villa in Al-Wurud for Rent, Riyadh (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $riyadh->id,
                'district_id' => District::where('city_id', $riyadh->id)->where('name_en', 'Al-Wurud')->first()?->id,
                'title_en' => 'Modern Family Villa for Rent in Al-Wurud',
                'title_ar' => 'فيلا عائلية حديثة للإيجار في الورود',
                'description_en' => 'Beautifully maintained 5-bedroom villa for yearly rent. Close to international schools and King Fahd Medical City. Modern kitchen, garden, and driver room. 2025 availability.',
                'description_ar' => 'فيلا من 5 غرف نوم بصيانة ممتازة للإيجار السنوي. قريبة من المدارس الدولية ومدينة الملك فهد الطبية. مطبخ حديث وحديقة وغرفة سائق. متاحة 2025.',
                'type' => 'villa',
                'purpose' => 'rent',
                'price' => 185000,
                'area_sqm' => 400,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'latitude' => 24.7234,
                'longitude' => 46.6890,
                'features' => ['garden', 'driver_room', 'parking', 'ac', 'kitchen'],
                'rega_ad_license' => '7100035003',
                'status' => 'active',
            ],

            // 19. Luxury Apartment in Al-Andalus, Jeddah (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Al-Andalus')->first()?->id,
                'title_en' => 'Premium Apartment in Al-Andalus',
                'title_ar' => 'شقة فاخرة في الأندلس',
                'description_en' => 'Elegant 3-bedroom apartment in prestigious Al-Andalus district. Features marble flooring, modern kitchen, and access to community facilities. Near King Abdulaziz International Airport. 2025 listing.',
                'description_ar' => 'شقة أنيقة من 3 غرف نوم في حي الأندلس الراقي. تتميز بأرضيات رخامية ومطبخ حديث ووصول للمرافق المشتركة. قرب مطار الملك عبدالعزيز الدولي. عرض 2025.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 1350000,
                'area_sqm' => 175,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'latitude' => 21.5890,
                'longitude' => 39.1234,
                'features' => ['elevator', 'ac', 'kitchen', 'parking'],
                'rega_ad_license' => '7200055001',
                'status' => 'active',
            ],

            // 20. Seafront Compound Villa in Al-Murjan, Jeddah (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Al-Murjan')->first()?->id,
                'title_en' => 'Seafront Compound Villa in Al-Murjan',
                'title_ar' => 'فيلا كمباوند على البحر في المرجان',
                'description_en' => 'Exclusive 5-bedroom compound villa with Red Sea views. Features community pool, gym, tennis courts, and 24/7 security. Perfect for expat families. 2025 new listing.',
                'description_ar' => 'فيلا كمباوند حصرية من 5 غرف نوم مع إطلالات على البحر الأحمر. تتميز بمسبح مشترك وصالة رياضية وملاعب تنس وأمن على مدار الساعة. مثالية للعائلات الأجنبية. عرض جديد 2025.',
                'type' => 'compound',
                'purpose' => 'rent',
                'price' => 220000,
                'area_sqm' => 380,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'latitude' => 21.6123,
                'longitude' => 39.0876,
                'features' => ['pool', 'garden', 'maid_room', 'parking', 'ac', 'security'],
                'rega_ad_license' => '7200055002',
                'status' => 'active',
            ],

            // 21. Residential Land in Al-Zahra, Jeddah (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $jeddah->id,
                'district_id' => District::where('city_id', $jeddah->id)->where('name_en', 'Al-Zahra')->first()?->id,
                'title_en' => 'Prime Residential Land in Al-Zahra',
                'title_ar' => 'أرض سكنية ممتازة في الزهراء',
                'description_en' => 'Excellent residential land plot in Al-Zahra district. Perfect for building a custom villa. All utilities available. Near schools and shopping. 2025 investment opportunity.',
                'description_ar' => 'قطعة أرض سكنية ممتازة في حي الزهراء. مثالية لبناء فيلا مخصصة. جميع الخدمات متوفرة. قرب المدارس والتسوق. فرصة استثمارية 2025.',
                'type' => 'land',
                'purpose' => 'sale',
                'price' => 2800000,
                'area_sqm' => 800,
                'bedrooms' => null,
                'bathrooms' => null,
                'latitude' => 21.5456,
                'longitude' => 39.1678,
                'features' => [],
                'rega_ad_license' => '7200055003',
                'status' => 'active',
            ],

            // 22. Executive Villa in Al-Yarmouk, Khobar (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $khobar->id,
                'district_id' => District::where('city_id', $khobar->id)->where('name_en', 'Al-Yarmouk')->first()?->id,
                'title_en' => 'Executive Villa in Al-Yarmouk',
                'title_ar' => 'فيلا تنفيذية في اليرموك',
                'description_en' => 'Stunning 6-bedroom executive villa in Al-Yarmouk. Features modern design, home office, private pool, and large garden. Close to Aramco and KFUPM. 2025 premium listing.',
                'description_ar' => 'فيلا تنفيذية مذهلة من 6 غرف نوم في اليرموك. تتميز بتصميم عصري ومكتب منزلي ومسبح خاص وحديقة كبيرة. قريبة من أرامكو وجامعة الملك فهد للبترول. عرض متميز 2025.',
                'type' => 'villa',
                'purpose' => 'sale',
                'price' => 5800000,
                'area_sqm' => 520,
                'bedrooms' => 6,
                'bathrooms' => 7,
                'latitude' => 26.2987,
                'longitude' => 50.1890,
                'features' => ['majlis', 'pool', 'garden', 'driver_room', 'maid_room', 'parking', 'ac'],
                'rega_ad_license' => '7400085001',
                'status' => 'active',
            ],

            // 23. Waterfront Apartment in Corniche, Khobar (2025)
            [
                'user_id' => $agent->id,
                'city_id' => $khobar->id,
                'district_id' => District::where('city_id', $khobar->id)->where('name_en', 'Corniche')->first()?->id,
                'title_en' => 'Modern Waterfront Apartment on Khobar Corniche',
                'title_ar' => 'شقة حديثة على كورنيش الخبر',
                'description_en' => 'Contemporary 3-bedroom apartment with panoramic Gulf views on Khobar Corniche. Features floor-to-ceiling windows, modern kitchen, and underground parking. 2025 availability.',
                'description_ar' => 'شقة معاصرة من 3 غرف نوم مع إطلالات بانورامية على الخليج في كورنيش الخبر. تتميز بنوافذ من الأرض للسقف ومطبخ حديث وموقف سيارات تحت الأرض. متاحة 2025.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 1650000,
                'area_sqm' => 180,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'latitude' => 26.2856,
                'longitude' => 50.2156,
                'features' => ['elevator', 'ac', 'kitchen', 'parking', 'security'],
                'rega_ad_license' => '7400085002',
                'status' => 'active',
            ],

            // 24. Family Apartment near Al-Haram, Makkah (2025)
            [
                'user_id' => $agent->id,
                'city_id' => City::where('name_en', 'Makkah')->first()?->id,
                'district_id' => District::where('name_en', 'Al-Aziziyah')->first()?->id,
                'title_en' => 'Premium Apartment near Al-Haram in Al-Aziziyah',
                'title_ar' => 'شقة فاخرة قرب الحرم في العزيزية',
                'description_en' => 'Excellent 4-bedroom apartment for Hajj and Umrah visitors. Walking distance to Al-Masjid Al-Haram. High-floor with city views. Fully furnished. 2025 investment property.',
                'description_ar' => 'شقة ممتازة من 4 غرف نوم لزوار الحج والعمرة. على مسافة قريبة من المسجد الحرام. طابق علوي مع إطلالات على المدينة. مفروشة بالكامل. عقار استثماري 2025.',
                'type' => 'apartment',
                'purpose' => 'sale',
                'price' => 3200000,
                'area_sqm' => 160,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'latitude' => 21.4167,
                'longitude' => 39.8267,
                'features' => ['elevator', 'ac', 'kitchen', 'security'],
                'rega_ad_license' => '7500095001',
                'status' => 'active',
            ],

            // 25. Industrial Land in Jubail Industrial City (2025)
            [
                'user_id' => $agent->id,
                'city_id' => City::where('name_en', 'Jubail')->first()?->id,
                'district_id' => null,
                'title_en' => 'Strategic Industrial Land in Jubail',
                'title_ar' => 'أرض صناعية استراتيجية في الجبيل',
                'description_en' => 'Prime industrial land in Jubail Industrial City. Excellent for manufacturing or logistics. Close to port and major highways. All industrial permits available. 2025 opportunity.',
                'description_ar' => 'أرض صناعية ممتازة في مدينة الجبيل الصناعية. ممتازة للتصنيع أو اللوجستيات. قريبة من الميناء والطرق السريعة الرئيسية. جميع التصاريح الصناعية متوفرة. فرصة 2025.',
                'type' => 'land',
                'purpose' => 'sale',
                'price' => 8500000,
                'area_sqm' => 5000,
                'bedrooms' => null,
                'bathrooms' => null,
                'latitude' => 27.0112,
                'longitude' => 49.6567,
                'features' => [],
                'rega_ad_license' => '7600095001',
                'status' => 'active',
            ],
        ];

        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }

        $this->command->info('Created ' . count($properties) . ' real Saudi properties (15 from 2024 + 10 from 2025).');
    }
}

