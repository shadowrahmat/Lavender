<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsiteSetting;

class WebsiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Lavender Food & Bakery'],
            ['key' => 'site_tagline', 'value' => 'Freshly Baked Happiness, Made with Care'],
            ['key' => 'phone_1', 'value' => '+8801730-728954'],
            ['key' => 'phone_2', 'value' => '+8801730728905'],
            ['key' => 'email', 'value' => 'hello@lavenderfoodnbakery.com'],
            ['key' => 'address', 'value' => 'AJ Height, Uttar Badda, Cha-72, 1/D Progoti Shoroni, Dhaka 1212'],
            ['key' => 'opening_hours', 'value' => '9:00 AM – 11:00 PM'],
            ['key' => 'facebook', 'value' => 'https://facebook.com/lavenderfoodnbakery'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/lavenderfoodnbakery'],
            ['key' => 'delivery_charge', 'value' => '60'],
            ['key' => 'free_delivery_above', 'value' => '500'],
            ['key' => 'currency', 'value' => 'BDT'],
            ['key' => 'currency_symbol', 'value' => '৳'],
        ];

        foreach ($settings as $setting) {
            WebsiteSetting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
