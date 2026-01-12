<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name_en' => 'Free',
                'name_ar' => 'مجاني',
                'slug' => 'free',
                'description_en' => 'Get started with 2 free listings',
                'description_ar' => 'ابدأ بإعلانين مجانيين',
                'price_monthly' => 0,
                'listing_limit' => 2,
                'featured_quota_monthly' => 0,
                'featured_credit_days' => 7,
                'features' => [
                    'Basic listing visibility',
                    'Standard support',
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name_en' => 'Basic',
                'name_ar' => 'أساسي',
                'slug' => 'basic',
                'description_en' => 'Perfect for occasional property listings',
                'description_ar' => 'مثالي لإعلانات العقارات العرضية',
                'price_monthly' => 99,
                'listing_limit' => 10,
                'featured_quota_monthly' => 0,
                'featured_credit_days' => 7,
                'features' => [
                    '10 active listings',
                    'Standard support',
                    'Basic analytics',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name_en' => 'Professional',
                'name_ar' => 'احترافي',
                'slug' => 'professional',
                'description_en' => 'For active real estate agents',
                'description_ar' => 'للوكلاء العقاريين النشطين',
                'price_monthly' => 299,
                'listing_limit' => null, // Unlimited
                'featured_quota_monthly' => 5,
                'featured_credit_days' => 7,
                'features' => [
                    'Unlimited listings',
                    '5 featured credits/month',
                    'Priority support',
                    'Advanced analytics',
                    'REGA verified badge',
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name_en' => 'Enterprise',
                'name_ar' => 'مؤسسي',
                'slug' => 'enterprise',
                'description_en' => 'For agencies and power users',
                'description_ar' => 'للوكالات والمستخدمين المتميزين',
                'price_monthly' => 799,
                'listing_limit' => null, // Unlimited
                'featured_quota_monthly' => 20,
                'featured_credit_days' => 7,
                'features' => [
                    'Unlimited listings',
                    '20 featured credits/month',
                    'Dedicated account manager',
                    'Premium analytics & reports',
                    'REGA verified badge',
                    'API access',
                    'White-label options',
                ],
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
