<?php

namespace Tests\Feature;

use App\Models\User;
use BezhanSalleh\LanguageSwitch\Http\Livewire\LanguageSwitchComponent;
use Filament\Support\Enums\Width;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_filament_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');

        $loginResponse = $this->get('/admin/login');
        $loginResponse->assertOk();
        $loginResponse->assertSeeLivewire(LanguageSwitchComponent::class);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSeeLivewire(LanguageSwitchComponent::class);
    }

    public function test_editor_can_access_dashboard(): void
    {
        $editor = User::factory()->editor()->create();

        $response = $this->actingAs($editor)->get('/admin');

        $response->assertOk();
    }

    public function test_viewer_is_forbidden_from_admin_panel(): void
    {
        $viewer = User::factory()->viewer()->create();

        $response = $this->actingAs($viewer)->get('/admin');

        $response->assertForbidden();
    }

    public function test_admin_can_view_resource_indexes(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/posts')->assertOk();
        $this->actingAs($admin)->get('/admin/pages')->assertOk();
        $this->actingAs($admin)->get('/admin/categories')->assertOk();
        $this->actingAs($admin)->get('/admin/slides')->assertOk();
        $this->actingAs($admin)->get('/admin/menus')->assertOk();
        $this->actingAs($admin)->get('/admin/settings')->assertOk();
        $this->actingAs($admin)->get('/admin/questions')->assertOk();
        $this->actingAs($admin)->get('/admin/question-topics')->assertOk();
        $this->actingAs($admin)->get('/admin/faqs')->assertOk();
        $this->actingAs($admin)->get('/admin/users')->assertOk();
    }

    public function test_language_switch_can_switch_locale(): void
    {
        Livewire::test(LanguageSwitchComponent::class)
            ->call('changeLocale', 'en')
            ->assertSessionHas('locale', 'en');

        $this->assertTrue(cookie()->hasQueued('filament_language_switch_locale'));

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/admin')
            ->assertOk();

        $this->assertSame('en', app()->getLocale());
    }

    public function test_default_locale_is_indonesian(): void
    {
        $this->assertSame('id', config('app.locale'));
        $this->assertSame('id', app()->getLocale());
    }

    public function test_english_locale_translations(): void
    {
        app()->setLocale('en');

        $this->assertSame('Posts', __('Posts'));
        $this->assertSame('CMS Content', __('CMS Content'));
        $this->assertSame('Public Services', __('Public Services'));
        $this->assertSame('Settings', __('Settings'));

        app()->setLocale('id');

        $this->assertSame('Artikel & Berita', __('Posts'));
        $this->assertSame('Konten CMS', __('CMS Content'));
        $this->assertSame('Layanan Masyarakat', __('Public Services'));
        $this->assertSame('Pengaturan', __('Settings'));
    }

    public function test_admin_panel_renders_indonesian_labels(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/admin');

        $response->assertOk();
        $response->assertSee('Konten CMS');
        $response->assertSee('Artikel &amp; Berita', false);
    }

    public function test_admin_panel_renders_english_labels(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'en'])
            ->get('/admin');

        $response->assertOk();
        $response->assertSee('CMS Content');
        $response->assertSee('Posts');
    }

    public function test_admin_panel_uses_full_max_content_width(): void
    {
        $panel = filament()->getCurrentOrDefaultPanel();

        $this->assertEquals(Width::Full, $panel->getMaxContentWidth());
    }

    public function test_admin_can_access_create_pages(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/posts/create')->assertOk();
        $this->actingAs($admin)->get('/admin/pages/create')->assertOk();
        $this->actingAs($admin)->get('/admin/categories/create')->assertOk();
        $this->actingAs($admin)->get('/admin/settings/create')->assertOk();
        $this->actingAs($admin)->get('/admin/faqs/create')->assertOk();
        $this->actingAs($admin)->get('/admin/users/create')->assertOk();
    }
}
