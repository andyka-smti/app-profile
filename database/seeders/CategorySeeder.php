<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Berita Terkini', 'description' => 'Kabar dan berita terbaru seputar kegiatan instansi.', 'sort_order' => 1],
            ['name' => 'Pengumuman', 'description' => 'Informasi penting dan pengumuman resmi.', 'sort_order' => 2],
            ['name' => 'Prestasi', 'description' => 'Pencapaian dan prestasi peserta didik serta tenaga pendidik.', 'sort_order' => 3],
            ['name' => 'Kegiatan', 'description' => 'Dokumentasi agenda dan kegiatan sekolah.', 'sort_order' => 4],
            ['name' => 'Artikel Edukasi', 'description' => 'Wawasan, opini, dan materi edukatif.', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
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
