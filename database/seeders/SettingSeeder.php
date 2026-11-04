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
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'SMK Negeri 1 Contoh', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Sekolah Vokasi Unggulan & Berkarakter', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Portal resmi profil instansi dan layanan informasi publik.', 'type' => 'textarea'],
            ['group' => 'general', 'key' => 'site_logo', 'value' => null, 'type' => 'image'],

            // Contact
            ['group' => 'contact', 'key' => 'contact_address', 'value' => 'Jl. Pendidikan No. 123, Kota Contoh', 'type' => 'textarea'],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '(021) 1234567', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'info@smkn1contoh.sch.id', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_whatsapp', 'value' => '+6281234567890', 'type' => 'text'],

            // Social Media
            ['group' => 'social', 'key' => 'social_facebook', 'value' => 'https://facebook.com/smkn1contoh', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_instagram', 'value' => 'https://instagram.com/smkn1contoh', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_youtube', 'value' => 'https://youtube.com/@smkn1contoh', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_tiktok', 'value' => 'https://tiktok.com/@smkn1contoh', 'type' => 'text'],

            // Footer & Others
            ['group' => 'footer', 'key' => 'footer_text', 'value' => '© 2026 SMK Negeri 1 Contoh. Hak Cipta Dilindungi.', 'type' => 'text'],
            ['group' => 'maps', 'key' => 'google_maps_embed', 'value' => '', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
