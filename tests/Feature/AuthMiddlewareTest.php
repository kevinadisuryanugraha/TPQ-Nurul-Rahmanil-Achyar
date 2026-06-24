<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_routes_redirect_guest(): void
    {
        $this->get('/superadmin/dashboard')->assertRedirect('/login');
    }

    public function test_admin_routes_redirect_guest(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_murid_routes_redirect_guest(): void
    {
        $this->get('/murid/dashboard')->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->get('/admin/dashboard')
            ->assertStatus(200);
    }

    public function test_student_can_access_murid_dashboard(): void
    {
        $level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $user = User::factory()->create(['current_level_id' => $level->id]);

        $this->actingAs($user, 'web')
            ->get('/murid/dashboard')
            ->assertStatus(200);
    }

    public function test_student_cannot_access_admin_routes(): void
    {
        $level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $user = User::factory()->create(['current_level_id' => $level->id]);

        $this->actingAs($user, 'web')
            ->get('/admin/dashboard')
            ->assertRedirect('/login');
    }

    public function test_admin_cannot_access_superadmin_routes(): void
    {
        $admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'admin')
            ->get('/superadmin/dashboard')
            ->assertStatus(403);
    }
}
