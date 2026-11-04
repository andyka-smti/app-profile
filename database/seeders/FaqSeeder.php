<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\QuestionTopic;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ppdb = QuestionTopic::where('slug', 'penerimaan-siswa-baru-ppdb')->first();
        $adminTopic = QuestionTopic::where('slug', 'administrasi-legalisir')->first();

        $faqs = [
            [
                'topic_id' => $ppdb?->id,
                'question' => 'Kapan pendaftaran PPDB dibuka?',
                'answer' => 'Pendaftaran PPDB biasanya dibuka pada bulan Mei hingga Juni setiap tahunnya. Jadwal rinci dapat dipantau melalui portal pengumuman resmi.',
                'sort_order' => 1,
            ],
            [
                'topic_id' => $ppdb?->id,
                'question' => 'Apa saja dokumen yang harus disiapkan saat mendaftar?',
                'answer' => 'Dokumen yang dibutuhkan meliputi fotokopi akta kelahiran, kartu keluarga, surat keterangan lulus (SKL) / ijazah SMP, dan pas foto terbaru.',
                'sort_order' => 2,
            ],
            [
                'topic_id' => $adminTopic?->id,
                'question' => 'Bagaimana prosedur permohonan legalisir ijazah untuk alumni?',
                'answer' => 'Alumni dapat datang langsung ke ruang tata usaha pada jam kerja (Senin - Jumat, 08.00 - 15.00 WIB) dengan membawa ijazah asli dan fotokopi yang akan dilegalisir maksimal 10 lembar.',
                'sort_order' => 1,
            ],
        ];

        foreach ($faqs as $f) {
            Faq::firstOrCreate(
                ['question' => $f['question']],
                [
                    'topic_id' => $f['topic_id'],
                    'answer' => $f['answer'],
                    'sort_order' => $f['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
