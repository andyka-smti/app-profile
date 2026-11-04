<?php

namespace Database\Seeders;

use App\Enums\PageStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'title' => 'Tentang Kami',
                'content' => '<h2>Profil Singkat</h2><p>SMK Negeri 1 Contoh didirikan untuk menghasilkan lulusan yang berakhlak mulia, kompeten, dan siap bersaing di dunia usaha maupun industri (DUDI).</p><p>Dengan dukungan tenaga pengajar profesional dan fasilitas laboratorium berstandar industri, kami berkomitmen menyelenggarakan pendidikan vokasi berkualitas.</p>',
                'sort_order' => 1,
                'show_in_menu' => true,
            ],
            [
                'title' => 'Visi dan Misi',
                'content' => '<h3>Visi</h3><p>Menjadi lembaga pendidikan kejuruan unggul, berkarakter, dan berwawasan lingkungan pada tahun 2030.</p><h3>Misi</h3><ul><li>Menyelenggarakan pembelajaran berbasis kompetensi dan teknologi industri.</li><li>Membina karakter peserta didik yang religius, disiplin, dan bertanggung jawab.</li><li>Memperluas jejaring kemitraan dengan dunia usaha dan industri bertaraf nasional maupun internasional.</li></ul>',
                'sort_order' => 2,
                'show_in_menu' => true,
            ],
            [
                'title' => 'Struktur Organisasi',
                'content' => '<p>Manajemen sekolah dipimpin oleh Kepala Sekolah, dibantu oleh Wakil Kepala Sekolah bidang Kurikulum, Kesiswaan, Hubungan Industri, dan Sarana Prasarana, serta Dewan Komite Sekolah.</p>',
                'sort_order' => 3,
                'show_in_menu' => true,
            ],
            [
                'title' => 'Sarana dan Fasilitas',
                'content' => '<p>Fasilitas sekolah meliputi laboratorium komputer modern, bengkel praktik kejuruan, perpustakaan digital, sarana olahraga, dan aula serbaguna.</p>',
                'sort_order' => 4,
                'show_in_menu' => false,
            ],
        ];

        foreach ($pages as $p) {
            Page::firstOrCreate(
                ['slug' => Str::slug($p['title'])],
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
