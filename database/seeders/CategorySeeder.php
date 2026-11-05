<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => ['id' => 'Berita Terkini', 'en' => 'Latest News'],
                'description' => ['id' => 'Kabar dan berita terbaru seputar kegiatan instansi.', 'en' => 'Latest news and updates regarding institution activities.'],
                'slug' => 'berita-terkini',
                'sort_order' => 1,
            ],
            [
                'name' => ['id' => 'Pengumuman', 'en' => 'Announcements'],
                'description' => ['id' => 'Informasi penting dan pengumuman resmi.', 'en' => 'Important information and official announcements.'],
                'slug' => 'pengumuman',
                'sort_order' => 2,
            ],
            [
                'name' => ['id' => 'Prestasi', 'en' => 'Achievements'],
                'description' => ['id' => 'Pencapaian dan prestasi peserta didik serta tenaga pendidik.', 'en' => 'Achievements and accolades of students and educators.'],
                'slug' => 'prestasi',
                'sort_order' => 3,
            ],
            [
                'name' => ['id' => 'Kegiatan', 'en' => 'Events & Activities'],
                'description' => ['id' => 'Dokumentasi agenda dan kegiatan sekolah.', 'en' => 'Documentation of agendas and school activities.'],
                'slug' => 'kegiatan',
                'sort_order' => 4,
            ],
            [
                'name' => ['id' => 'Artikel Edukasi', 'en' => 'Educational Articles'],
                'description' => ['id' => 'Wawasan, opini, dan materi edukatif.', 'en' => 'Insights, opinions, and educational materials.'],
                'slug' => 'artikel-edukasi',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
