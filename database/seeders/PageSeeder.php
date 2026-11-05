<?php

namespace Database\Seeders;

use App\Enums\PageStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        if (! $admin) {
            return;
        }

        $pages = [
            [
                'title' => [
                    'id' => 'Tentang Kami',
                    'en' => 'About Us',
                ],
                'slug' => 'tentang-kami',
                'content' => [
                    'id' => '<h2>Profil Singkat</h2><p>SMK Negeri 1 Contoh didirikan untuk menghasilkan lulusan yang berakhlak mulia, kompeten, dan siap bersaing di dunia usaha maupun industri (DUDI).</p><p>Dengan dukungan tenaga pengajar profesional dan fasilitas laboratorium berstandar industri, kami berkomitmen menyelenggarakan pendidikan vokasi berkualitas.</p>',
                    'en' => '<h2>Short Profile</h2><p>State Vocational High School 1 Example was established to produce graduates who possess noble morals, competence, and readiness to compete in the business and industrial sector (DUDI).</p><p>Supported by professional educators and industry-grade laboratory facilities, we are committed to delivering high-quality vocational education.</p>',
                ],
                'sort_order' => 1,
                'show_in_menu' => true,
            ],
            [
                'title' => [
                    'id' => 'Visi dan Misi',
                    'en' => 'Vision and Mission',
                ],
                'slug' => 'visi-dan-misi',
                'content' => [
                    'id' => '<h3>Visi</h3><p>Menjadi lembaga pendidikan kejuruan unggul, berkarakter, dan berwawasan lingkungan pada tahun 2030.</p><h3>Misi</h3><ul><li>Menyelenggarakan pembelajaran berbasis kompetensi dan teknologi industri.</li><li>Membina karakter peserta didik yang religius, disiplin, dan bertanggung jawab.</li><li>Memperluas jejaring kemitraan dengan dunia usaha dan industri bertaraf nasional maupun internasional.</li></ul>',
                    'en' => '<h3>Vision</h3><p>To become an outstanding, character-driven, and environmentally conscious vocational education institution by 2030.</p><h3>Mission</h3><ul><li>Conduct competency-based learning integrated with industrial technology.</li><li>Cultivate student character that is religious, disciplined, and responsible.</li><li>Expand partnership networks with the business and industry sector both nationally and internationally.</li></ul>',
                ],
                'sort_order' => 2,
                'show_in_menu' => true,
            ],
            [
                'title' => [
                    'id' => 'Struktur Organisasi',
                    'en' => 'Organizational Structure',
                ],
                'slug' => 'struktur-organisasi',
                'content' => [
                    'id' => '<p>Manajemen sekolah dipimpin oleh Kepala Sekolah, dibantu oleh Wakil Kepala Sekolah bidang Kurikulum, Kesiswaan, Hubungan Industri, dan Sarana Prasarana, serta Dewan Komite Sekolah.</p>',
                    'en' => '<p>School governance is led by the Principal, supported by Vice Principals for Curriculum, Student Affairs, Industrial Relations, and Facilities, alongside the School Committee Council.</p>',
                ],
                'sort_order' => 3,
                'show_in_menu' => true,
            ],
            [
                'title' => [
                    'id' => 'Sarana dan Fasilitas',
                    'en' => 'Facilities & Infrastructure',
                ],
                'slug' => 'sarana-dan-fasilitas',
                'content' => [
                    'id' => '<p>Fasilitas sekolah meliputi laboratorium komputer modern, bengkel praktik kejuruan, perpustakaan digital, sarana olahraga, dan aula serbaguna.</p>',
                    'en' => '<p>School facilities comprise modern computer laboratories, vocational practice workshops, digital library, sports grounds, and a multipurpose hall.</p>',
                ],
                'sort_order' => 4,
                'show_in_menu' => false,
            ],
        ];

        foreach ($pages as $p) {
            Page::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'user_id' => $admin->id,
                    'title' => $p['title'],
                    'content' => $p['content'],
                    'status' => PageStatus::Published,
                    'sort_order' => $p['sort_order'],
                    'show_in_menu' => $p['show_in_menu'],
                ]
            );
        }
    }
}
