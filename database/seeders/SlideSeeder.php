<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Selamat Datang di Portal Resmi Sekolah',
                'description' => 'Mewujudkan generasi berprestasi, kompeten, dan siap kerja.',
                'image' => 'slides/slide1.jpg',
                'link_url' => '/pages/tentang-kami',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Pendaftaran Siswa Baru (PPDB) Telah Dibuka',
                'description' => 'Segera daftarkan diri Anda dan raih masa depan gemilang bersama kami.',
                'image' => 'slides/slide2.jpg',
                'link_url' => '/posts',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::firstOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }
    }
}
