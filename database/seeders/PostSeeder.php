<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
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

        $categories = Category::all();
        if ($categories->isEmpty()) {
            return;
        }

        $samplePosts = [
            [
                'title' => 'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran Baru Dibuka',
                'summary' => 'Informasi lengkap terkait jadwal, persyaratan, dan alur pendaftaran peserta didik baru.',
                'content' => '<p>Kami mengumumkan bahwa pendaftaran peserta didik baru resmi dibuka mulai bulan ini. Calon siswa diharapkan mempersiapkan dokumen persyaratan yang telah ditentukan, seperti rapor, akta kelahiran, dan kartu keluarga.</p><p>Pendaftaran dapat dilakukan secara online melalui portal resmi atau langsung mendatangi sekretariat panitia di gedung utama.</p>',
                'category' => 'Pengumuman',
                'is_featured' => true,
            ],
            [
                'title' => 'Siswa Raih Juara 1 Lomba Keterampilan Siswa (LKS) Tingkat Provinsi',
                'summary' => 'Prestasi membanggakan kembali diukir dalam bidang Web Technologies pada kompetisi LKS tahun ini.',
                'content' => '<p>Prestasi membanggakan kembali diraih oleh kontingen sekolah kita dalam ajang Lomba Keterampilan Siswa (LKS) tingkat provinsi. Melalui kerja keras dan bimbingan guru pembina, siswa kami sukses meraih medali emas pada mata lomba Web Technologies.</p><p>Kepala sekolah menyampaikan apresiasi setinggi-tingginya kepada seluruh pihak yang telah mendukung perjuangan kontingen.</p>',
                'category' => 'Prestasi',
                'is_featured' => true,
            ],
            [
                'title' => 'Kegiatan Workshop Kewirausahaan dan Kemitraan Industri',
                'summary' => 'Mempersiapkan lulusan yang siap kerja dan berjiwa wirausaha melalui kolaborasi bersama praktisi industri.',
                'content' => '<p>Sekolah menyelenggarakan workshop kewirausahaan yang dihadiri oleh praktisi dari berbagai industri ternama. Kegiatan ini bertujuan membekali siswa dengan pemahaman mendalam tentang ekosistem dunia kerja modern serta peluang membuka usaha mandiri.</p>',
                'category' => 'Kegiatan',
                'is_featured' => false,
            ],
            [
                'title' => 'Pentingnya Literasi Digital di Era Kecerdasan Buatan',
                'summary' => 'Menelaah bagaimana pemanfaatan teknologi cerdas dapat menunjang proses pembelajaran yang efektif.',
                'content' => '<p>Perkembangan teknologi kecerdasan buatan membawa dampak besar bagi dunia pendidikan. Para pendidik dan siswa dituntut untuk memiliki kemampuan berpikir kritis dalam menyaring dan memanfaatkan informasi secara bertanggung jawab.</p>',
                'category' => 'Artikel Edukasi',
                'is_featured' => false,
            ],
        ];

        foreach ($samplePosts as $data) {
            $category = $categories->firstWhere('name', $data['category']) ?? $categories->first();

            Post::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'user_id' => $admin->id,
                    'category_id' => $category?->id,
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'content' => $data['content'],
                    'status' => PostStatus::Published,
                    'published_at' => now(),
                    'view_count' => rand(25, 200),
                    'is_featured' => $data['is_featured'],
                ]
            );
        }
    }
}
