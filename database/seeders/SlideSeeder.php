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
                'title' => [
                    'id' => 'Selamat Datang di Portal Resmi Sekolah',
                    'en' => 'Welcome to the Official School Portal',
                ],
                'description' => [
                    'id' => 'Mewujudkan generasi berprestasi, kompeten, dan siap kerja.',
                    'en' => 'Nurturing an accomplished, competent, and career-ready generation.',
                ],
                'image' => 'slides/slide1.jpg',
                'link_url' => '/pages/tentang-kami',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => [
                    'id' => 'Pendaftaran Siswa Baru (PPDB) Telah Dibuka',
                    'en' => 'New Student Registration (PPDB) is Now Open',
                ],
                'description' => [
                    'id' => 'Segera daftarkan diri Anda dan raih masa depan gemilang bersama kami.',
                    'en' => 'Register today and seize a brilliant future together with us.',
                ],
                'image' => 'slides/slide2.jpg',
                'link_url' => '/posts',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::firstOrCreate(
                ['link_url' => $slide['link_url']],
                $slide
            );
        }
    }
}
