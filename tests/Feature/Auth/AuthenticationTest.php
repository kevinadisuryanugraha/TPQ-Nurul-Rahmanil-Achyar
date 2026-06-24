<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_student_can_login_with_username(): void
    {
        $level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $user = User::factory()->create([
            'username' => 'teststudent',
            'password' => bcrypt('password'),
            'current_level_id' => $level->id,
        ]);

        $response = $this->post('/login', [
            'login' => 'teststudent',
            'password' => 'password',
        ]);

        $response->assertRedirect('/murid/dashboard');
        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_student_cannot_login_with_invalid_password(): void
    {
        $level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        User::factory()->create([
            'username' => 'teststudent',
            'password' => bcrypt('password'),
            'current_level_id' => $level->id,
        ]);

        $this->post('/login', [
            'login' => 'teststudent',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_can_login_with_email(): void
    {
        $admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_user_can_logout(): void
    {
        $level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $user = User::factory()->create([
            'username' => 'teststudent',
            'password' => bcrypt('password'),
            'current_level_id' => $level->id,
        ]);

        $response = $this->actingAs($user, 'web')->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
