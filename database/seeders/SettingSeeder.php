<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name_ar', 'value' => 'مدار سوفت للعلوم التقنية', 'group' => 'general'],
            ['key' => 'site_name_en', 'value' => 'Madar Soft Tech', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => '', 'group' => 'general'],
            
            // Hero Section
            ['key' => 'hero_title_ar', 'value' => "مدار سوفت \n للعلوم التقنية", 'group' => 'hero'],
            ['key' => 'hero_title_en', 'value' => "Madar Soft \n For Tech Sciences", 'group' => 'hero'],
            ['key' => 'hero_subtitle_ar', 'value' => 'نحن هنا لنحول رؤيتك إلى واقع رقمي متكامل.', 'group' => 'hero'],
            ['key' => 'hero_subtitle_en', 'value' => 'We are here to turn your vision into an integrated digital reality.', 'group' => 'hero'],
            
            // Footer
            ['key' => 'footer_copy_ar', 'value' => 'جميع الحقوق محفوظة. مدار سوفت للعلوم التقنية.', 'group' => 'footer'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com', 'group' => 'footer'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com', 'group' => 'footer'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
