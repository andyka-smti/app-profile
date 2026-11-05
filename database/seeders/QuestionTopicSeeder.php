<?php

namespace Database\Seeders;

use App\Models\QuestionTopic;
use Illuminate\Database\Seeder;

class QuestionTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            [
                'name' => [
                    'id' => 'Penerimaan Siswa Baru (PPDB)',
                    'en' => 'New Student Admission (PPDB)',
                ],
                'slug' => 'penerimaan-siswa-baru-ppdb',
                'description' => [
                    'id' => 'Pertanyaan seputar jalur masuk, berkas pendaftaran, dan biaya.',
                    'en' => 'Questions concerning admission tracks, required documents, and fees.',
                ],
                'sort_order' => 1,
            ],
            [
                'name' => [
                    'id' => 'Akademik & Kurikulum',
                    'en' => 'Academic & Curriculum',
                ],
                'slug' => 'akademik-kurikulum',
                'description' => [
                    'id' => 'Pertanyaan mengenai jadwal pelajaran, jurusan/kompetensi keahlian, dan PKL.',
                    'en' => 'Inquiries on class schedules, vocational study majors, and internship programs.',
                ],
                'sort_order' => 2,
            ],
            [
                'name' => [
                    'id' => 'Administrasi & Legalisir',
                    'en' => 'Administration & Legalization',
                ],
                'slug' => 'administrasi-legalisir',
                'description' => [
                    'id' => 'Layanan legalisir ijazah, surat keterangan siswa aktif, dan administrasi lainnya.',
                    'en' => 'Certificate legalization, active student certificate requests, and general administration.',
                ],
                'sort_order' => 3,
            ],
            [
                'name' => [
                    'id' => 'Sarana & Prasarana',
                    'en' => 'Facilities & Infrastructure',
                ],
                'slug' => 'sarana-prasarana',
                'description' => [
                    'id' => 'Fasilitas gedung, laboratorium, dan lingkungan sekolah.',
                    'en' => 'Building amenities, laboratories, and school environmental facilities.',
                ],
                'sort_order' => 4,
            ],
            [
                'name' => [
                    'id' => 'Lain-lain',
                    'en' => 'Miscellaneous & General',
                ],
                'slug' => 'lain-lain',
                'description' => [
                    'id' => 'Pertanyaan umum dan informasi lainnya.',
                    'en' => 'General questions and miscellaneous information.',
                ],
                'sort_order' => 5,
            ],
        ];

        foreach ($topics as $t) {
            QuestionTopic::firstOrCreate(
                ['slug' => $t['slug']],
                [
                    'name' => $t['name'],
                    'description' => $t['description'],
                    'sort_order' => $t['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
