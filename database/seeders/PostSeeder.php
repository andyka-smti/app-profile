<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

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
                'title' => [
                    'id' => 'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran Baru Dibuka',
                    'en' => 'New Student Admission (PPDB) for the New Academic Year is Now Open',
                ],
                'slug' => 'penerimaan-peserta-didik-baru-ppdb-tahun-ajaran-baru-dibuka',
                'summary' => [
                    'id' => 'Informasi lengkap terkait jadwal, persyaratan, dan alur pendaftaran peserta didik baru.',
                    'en' => 'Comprehensive information regarding schedules, requirements, and enrollment flow for new students.',
                ],
                'content' => [
                    'id' => '<p>Kami mengumumkan bahwa pendaftaran peserta didik baru resmi dibuka mulai bulan ini. Calon siswa diharapkan mempersiapkan dokumen persyaratan yang telah ditentukan, seperti rapor, akta kelahiran, dan kartu keluarga.</p><p>Pendaftaran dapat dilakukan secara online melalui portal resmi atau langsung mendatangi sekretariat panitia di gedung utama.</p>',
                    'en' => '<p>We are pleased to announce that enrollment for new students is officially open starting this month. Prospective students are expected to prepare the required documents, such as report cards, birth certificates, and family cards.</p><p>Registration can be completed online via the official portal or in person at the admissions secretariat in the main building.</p>',
                ],
                'category' => 'Pengumuman',
                'is_featured' => true,
            ],
            [
                'title' => [
                    'id' => 'Siswa Raih Juara 1 Lomba Keterampilan Siswa (LKS) Tingkat Provinsi',
                    'en' => 'Student Wins 1st Place at the Provincial Student Competency Competition (LKS)',
                ],
                'slug' => 'siswa-raih-juara-1-lomba-keterampilan-siswa-lks-tingkat-provinsi',
                'summary' => [
                    'id' => 'Prestasi membanggakan kembali diukir dalam bidang Web Technologies pada kompetisi LKS tahun ini.',
                    'en' => 'A proud achievement was once again earned in Web Technologies at this year’s LKS competition.',
                ],
                'content' => [
                    'id' => '<p>Prestasi membanggakan kembali diraih oleh kontingen sekolah kita dalam ajang Lomba Keterampilan Siswa (LKS) tingkat provinsi. Melalui kerja keras dan bimbingan guru pembina, siswa kami sukses meraih medali emas pada mata lomba Web Technologies.</p><p>Kepala sekolah menyampaikan apresiasi setinggi-tingginya kepada seluruh pihak yang telah mendukung perjuangan kontingen.</p>',
                    'en' => '<p>A proud achievement was once again recorded by our school contingent at the provincial Student Competency Competition (LKS). Through hard work and dedicated teacher mentoring, our student successfully secured the gold medal in Web Technologies.</p><p>The principal expressed highest appreciation to all parties supporting the team.</p>',
                ],
                'category' => 'Prestasi',
                'is_featured' => true,
            ],
            [
                'title' => [
                    'id' => 'Kegiatan Workshop Kewirausahaan dan Kemitraan Industri',
                    'en' => 'Entrepreneurship and Industrial Partnership Workshop Event',
                ],
                'slug' => 'kegiatan-workshop-kewirausahaan-dan-kemitraan-industri',
                'summary' => [
                    'id' => 'Mempersiapkan lulusan yang siap kerja dan berjiwa wirausaha melalui kolaborasi bersama praktisi industri.',
                    'en' => 'Preparing career-ready and entrepreneurial graduates through direct collaboration with industry practitioners.',
                ],
                'content' => [
                    'id' => '<p>Sekolah menyelenggarakan workshop kewirausahaan yang dihadiri oleh praktisi dari berbagai industri ternama. Kegiatan ini bertujuan membekali siswa dengan pemahaman mendalam tentang ekosistem dunia kerja modern serta peluang membuka usaha mandiri.</p>',
                    'en' => '<p>The school organized an entrepreneurship workshop attended by distinguished practitioners from top industries. This event aims to equip students with in-depth insights into the modern workplace ecosystem and self-enterprise opportunities.</p>',
                ],
                'category' => 'Kegiatan',
                'is_featured' => false,
            ],
            [
                'title' => [
                    'id' => 'Pentingnya Literasi Digital di Era Kecerdasan Buatan',
                    'en' => 'The Vital Importance of Digital Literacy in the AI Era',
                ],
                'slug' => 'pentingnya-literasi-digital-di-era-kecerdasan-buatan',
                'summary' => [
                    'id' => 'Menelaah bagaimana pemanfaatan teknologi cerdas dapat menunjang proses pembelajaran yang efektif.',
                    'en' => 'Examining how intelligent technologies can bolster effective learning processes.',
                ],
                'content' => [
                    'id' => '<p>Perkembangan teknologi kecerdasan buatan membawa dampak besar bagi dunia pendidikan. Para pendidik dan siswa dituntut untuk memiliki kemampuan berpikir kritis dalam menyaring dan memanfaatkan informasi secara bertanggung jawab.</p>',
                    'en' => '<p>The swift rise of artificial intelligence significantly impacts education. Educators and learners alike must foster critical thinking to filter and utilize information responsibly.</p>',
                ],
                'category' => 'Artikel Edukasi',
                'is_featured' => false,
            ],
        ];

        foreach ($samplePosts as $data) {
            $category = $categories->first(fn ($c) => $c->getTranslation('name', 'id') === $data['category']) ?? $categories->first();

            Post::firstOrCreate(
                ['slug' => $data['slug']],
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
