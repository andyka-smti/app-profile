<?php

namespace Tests\Feature;

use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Enums\QuestionAnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiV1Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_published_posts(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => PostStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        Post::factory()->draft()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $response = $this->getJson('/api/v1/posts');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_can_get_post_by_slug_and_increment_views(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'slug' => 'test-post-slug',
            'status' => PostStatus::Published,
            'published_at' => now()->subDay(),
            'view_count' => 5,
        ]);

        $response = $this->getJson('/api/v1/posts/test-post-slug');

        $response->assertOk()
            ->assertJsonPath('data.slug', 'test-post-slug')
            ->assertJsonPath('data.view_count', 6);

        $this->assertEquals(6, $post->fresh()->view_count);
    }

    public function test_can_get_page_by_slug(): void
    {
        $user = User::factory()->create();

        Page::create([
            'user_id' => $user->id,
            'title' => 'Visi Misi',
            'slug' => 'visi-misi',
            'content' => 'Isi Visi Misi',
            'status' => PageStatus::Published,
        ]);

        $response = $this->getJson('/api/v1/pages/visi-misi');

        $response->assertOk()
            ->assertJsonPath('data.slug', 'visi-misi')
            ->assertJsonPath('data.title', 'Visi Misi');
    }

    public function test_can_get_categories(): void
    {
        Category::factory()->create(['name' => 'Berita Sekolah', 'is_active' => true]);
        Category::factory()->create(['name' => 'Tidak Aktif', 'is_active' => false]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_can_get_active_slides(): void
    {
        Slide::create([
            'title' => 'Slide 1',
            'image' => 'slides/1.jpg',
            'is_active' => true,
        ]);

        Slide::create([
            'title' => 'Slide Nonaktif',
            'image' => 'slides/2.jpg',
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/v1/slides');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_can_get_menu_by_location(): void
    {
        $menu = Menu::create([
            'name' => 'Menu Header',
            'location' => 'header',
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => 'Beranda',
            'url' => '/',
            'type' => 'custom',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/v1/menus/header');

        $response->assertOk()
            ->assertJsonPath('data.location', 'header')
            ->assertJsonCount(1, 'data.items');
    }

    public function test_can_get_settings(): void
    {
        Setting::create([
            'group' => 'general',
            'key' => 'site_name',
            'value' => 'SMK Unggulan',
            'type' => 'text',
        ]);

        $response = $this->getJson('/api/v1/settings?group=general');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.key', 'site_name');

        // Test map format
        $mapResponse = $this->getJson('/api/v1/settings?format=map');
        $mapResponse->assertOk()
            ->assertJsonPath('data.site_name', 'SMK Unggulan');
    }

    public function test_can_get_question_topics_and_faqs(): void
    {
        $topic = QuestionTopic::create([
            'name' => 'PPDB',
            'slug' => 'ppdb',
            'is_active' => true,
        ]);

        Faq::create([
            'topic_id' => $topic->id,
            'question' => 'Kapan pendaftaran dimulai?',
            'answer' => 'Mulai Mei.',
            'is_active' => true,
        ]);

        $this->getJson('/api/v1/question-topics')->assertOk()->assertJsonCount(1, 'data');
        $this->getJson('/api/v1/faqs')->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_public_can_submit_question(): void
    {
        $topic = QuestionTopic::create([
            'name' => 'Informasi Umum',
            'slug' => 'informasi-umum',
            'is_active' => true,
        ]);

        $payload = [
            'topic_id' => $topic->id,
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '08123456789',
            'subject' => 'Tanya Jadwal Masuk Sekolah',
            'body' => 'Kapan hari pertama masuk sekolah untuk siswa baru?',
        ];

        $response = $this->postJson('/api/v1/questions', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('questions', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_question_submission_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/questions', [
            'name' => '',
            'email' => 'bukan-email',
            'subject' => '',
            'body' => 'pendek',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'subject', 'body']);
    }

    public function test_only_answered_questions_with_published_answer_appear_in_public_list(): void
    {
        $user = User::factory()->create();

        // 1. Pending question (not in public list)
        Question::create([
            'name' => 'Penanya 1',
            'email' => 'p1@example.com',
            'subject' => 'Pertanyaan Baru',
            'body' => 'Pertanyaan belum dijawab',
            'status' => QuestionStatus::Pending,
        ]);

        // 2. Answered question with published answer
        $q2 = Question::create([
            'name' => 'Penanya 2',
            'email' => 'p2@example.com',
            'subject' => 'Pertanyaan Terjawab',
            'body' => 'Pertanyaan sudah dijawab',
            'status' => QuestionStatus::Answered,
        ]);

        QuestionAnswer::create([
            'question_id' => $q2->id,
            'user_id' => $user->id,
            'body' => '<p>Jawaban resmi dari petugas.</p>',
            'status' => QuestionAnswerStatus::Published,
            'is_published' => true,
            'answered_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/questions');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.subject', 'Pertanyaan Terjawab')
            ->assertJsonPath('data.0.answer.officer_name', $user->name);
    }
}
