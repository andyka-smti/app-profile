<?php

namespace Database\Seeders;

use App\Models\QuestionTopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            ['name' => 'Penerimaan Siswa Baru (PPDB)', 'description' => 'Pertanyaan seputar jalur masuk, berkas pendaftaran, dan biaya.', 'sort_order' => 1],
            ['name' => 'Akademik & Kurikulum', 'description' => 'Pertanyaan mengenai jadwal pelajaran, jurusan/kompetensi keahlian, dan PKL.', 'sort_order' => 2],
            ['name' => 'Administrasi & Legalisir', 'description' => 'Layanan legalisir ijazah, surat keterangan siswa aktif, dan administrasi lainnya.', 'sort_order' => 3],
            ['name' => 'Sarana & Prasarana', 'description' => 'Fasilitas gedung, laboratorium, dan lingkungan sekolah.', 'sort_order' => 4],
            ['name' => 'Lain-lain', 'description' => 'Pertanyaan umum dan informasi lainnya.', 'sort_order' => 5],
        ];

        foreach ($topics as $t) {
            QuestionTopic::firstOrCreate(
                ['slug' => Str::slug($t['name'])],
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
