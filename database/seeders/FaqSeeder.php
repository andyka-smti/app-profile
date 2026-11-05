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
                'question' => [
                    'id' => 'Kapan pendaftaran PPDB dibuka?',
                    'en' => 'When does PPDB admission registration open?',
                ],
                'answer' => [
                    'id' => 'Pendaftaran PPDB biasanya dibuka pada bulan Mei hingga Juni setiap tahunnya. Jadwal rinci dapat dipantau melalui portal pengumuman resmi.',
                    'en' => 'PPDB admission is customarily opened between May and June each year. Detailed schedules can be tracked via the official announcement portal.',
                ],
                'sort_order' => 1,
            ],
            [
                'topic_id' => $ppdb?->id,
                'question' => [
                    'id' => 'Apa saja dokumen yang harus disiapkan saat mendaftar?',
                    'en' => 'What documents must be prepared when registering?',
                ],
                'answer' => [
                    'id' => 'Dokumen yang dibutuhkan meliputi fotokopi akta kelahiran, kartu keluarga, surat keterangan lulus (SKL) / ijazah SMP, dan pas foto terbaru.',
                    'en' => 'Required documents include photocopies of birth certificate, family card, graduation certificate (SKL) / junior high diploma, and recent photographs.',
                ],
                'sort_order' => 2,
            ],
            [
                'topic_id' => $adminTopic?->id,
                'question' => [
                    'id' => 'Bagaimana prosedur permohonan legalisir ijazah untuk alumni?',
                    'en' => 'What is the procedure for diploma legalization requests by alumni?',
                ],
                'answer' => [
                    'id' => 'Alumni dapat datang langsung ke ruang tata usaha pada jam kerja (Senin - Jumat, 08.00 - 15.00 WIB) dengan membawa ijazah asli dan fotokopi yang akan dilegalisir maksimal 10 lembar.',
                    'en' => 'Alumni may visit the administration office during business hours (Monday - Friday, 08:00 - 15:00 WIB) with the original certificate and up to 10 copies for endorsement.',
                ],
                'sort_order' => 1,
            ],
        ];

        foreach ($faqs as $f) {
            Faq::firstOrCreate(
                ['topic_id' => $f['topic_id'], 'sort_order' => $f['sort_order']],
                [
                    'question' => $f['question'],
                    'answer' => $f['answer'],
                    'sort_order' => $f['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
