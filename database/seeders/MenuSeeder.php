<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $header = Menu::firstOrCreate(
            ['location' => 'header'],
            [
                'name' => [
                    'id' => 'Menu Utama (Header)',
                    'en' => 'Main Menu (Header)',
                ],
                'is_active' => true,
            ]
        );

        $headerItems = [
            ['label' => ['id' => 'Beranda', 'en' => 'Home'], 'url' => '/', 'type' => 'custom', 'sort_order' => 1],
            ['label' => ['id' => 'Profil', 'en' => 'Profile'], 'url' => '/pages/tentang-kami', 'type' => 'page', 'sort_order' => 2],
            ['label' => ['id' => 'Berita & Artikel', 'en' => 'News & Articles'], 'url' => '/posts', 'type' => 'custom', 'sort_order' => 3],
            ['label' => ['id' => 'Tanya Kami', 'en' => 'Ask Us'], 'url' => '/questions', 'type' => 'custom', 'sort_order' => 4],
            ['label' => ['id' => 'FAQ', 'en' => 'FAQ'], 'url' => '/faqs', 'type' => 'custom', 'sort_order' => 5],
        ];

        foreach ($headerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $header->id, 'url' => $item['url']],
                $item
            );
        }

        $footer = Menu::firstOrCreate(
            ['location' => 'footer'],
            [
                'name' => [
                    'id' => 'Menu Footer',
                    'en' => 'Footer Menu',
                ],
                'is_active' => true,
            ]
        );

        $footerItems = [
            ['label' => ['id' => 'Visi & Misi', 'en' => 'Vision & Mission'], 'url' => '/pages/visi-dan-misi', 'type' => 'page', 'sort_order' => 1],
            ['label' => ['id' => 'Struktur Organisasi', 'en' => 'Organizational Structure'], 'url' => '/pages/struktur-organisasi', 'type' => 'page', 'sort_order' => 2],
            ['label' => ['id' => 'FAQ Publik', 'en' => 'Public FAQ'], 'url' => '/faqs', 'type' => 'custom', 'sort_order' => 3],
        ];

        foreach ($footerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footer->id, 'url' => $item['url']],
                $item
            );
        }
    }
}
