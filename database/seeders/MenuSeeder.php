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
                'name' => 'Menu Utama (Header)',
                'is_active' => true,
            ]
        );

        $headerItems = [
            ['label' => 'Beranda', 'url' => '/', 'type' => 'custom', 'sort_order' => 1],
            ['label' => 'Profil', 'url' => '/pages/tentang-kami', 'type' => 'page', 'sort_order' => 2],
            ['label' => 'Berita & Artikel', 'url' => '/posts', 'type' => 'custom', 'sort_order' => 3],
            ['label' => 'Tanya Kami', 'url' => '/questions', 'type' => 'custom', 'sort_order' => 4],
            ['label' => 'FAQ', 'url' => '/faqs', 'type' => 'custom', 'sort_order' => 5],
        ];

        foreach ($headerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $header->id, 'label' => $item['label']],
                $item
            );
        }

        $footer = Menu::firstOrCreate(
            ['location' => 'footer'],
            [
                'name' => 'Menu Footer',
                'is_active' => true,
            ]
        );

        $footerItems = [
            ['label' => 'Visi & Misi', 'url' => '/pages/visi-dan-misi', 'type' => 'page', 'sort_order' => 1],
            ['label' => 'Struktur Organisasi', 'url' => '/pages/struktur-organisasi', 'type' => 'page', 'sort_order' => 2],
            ['label' => 'FAQ Publik', 'url' => '/faqs', 'type' => 'custom', 'sort_order' => 3],
        ];

        foreach ($footerItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footer->id, 'label' => $item['label']],
                $item
            );
        }
    }
}
