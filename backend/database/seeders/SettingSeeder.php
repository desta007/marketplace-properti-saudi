<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'SaudiProp',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
            ],
            [
                'key' => 'site_email',
                'value' => 'info@saudiprop.com',
                'type' => 'email',
                'group' => 'contact',
                'label' => 'Email Address',
            ],
            [
                'key' => 'site_phone',
                'value' => '+966 50 123 4567',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Phone Number',
            ],
            [
                'key' => 'site_address',
                'value' => 'Riyadh, Saudi Arabia',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Address',
            ],
            [
                'key' => 'site_whatsapp',
                'value' => '+966501234567',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'WhatsApp Number',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
