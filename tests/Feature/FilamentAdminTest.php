<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_filament_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
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
}
