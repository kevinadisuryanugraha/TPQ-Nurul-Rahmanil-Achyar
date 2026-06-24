<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Level;
use App\Models\User;
use App\Models\UserLevelHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMuridTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Level $level1;
    private Level $level2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->level1 = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $this->level2 = Level::factory()->create(['urutan' => 2, 'nama' => 'Iqra 1']);

        $this->admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_admin_murid_routes(): void
    {
        $this->get('/admin/murid')->assertRedirect('/login');
        $this->get('/admin/murid/create')->assertRedirect('/login');
        $this->get('/admin/murid/1')->assertRedirect('/login');
        $this->get('/admin/murid/1/edit')->assertRedirect('/login');
        $this->post('/admin/murid')->assertRedirect('/login');
        $this->put('/admin/murid/1')->assertRedirect('/login');
    }

    public function test_admin_can_view_students_list(): void
    {
        User::factory()->create([
            'nama_lengkap' => 'Ahmad Test',
            'current_level_id' => $this->level1->id,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/murid')
            ->assertStatus(200)
            ->assertSee('Ahmad Test');
    }

    public function test_admin_can_view_create_form(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/murid/create')
            ->assertStatus(200);
    }

    public function test_admin_can_store_student(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.store'), [
                'nama_lengkap' => 'Santri Baru',
                'nama_panggilan' => 'Santri',
                'jenis_kelamin' => 'L',
                'tanggal_masuk' => '2026-07-01',
                'current_level_id' => $this->level1->id,
                'username' => 'santribaru',
                'password' => 'rahasia123',
            ])
            ->assertRedirect(route('admin.murid.index'));

        $this->assertDatabaseHas('users', [
            'nama_lengkap' => 'Santri Baru',
            'username' => 'santribaru',
            'is_active' => true,
        ]);
    }

    public function test_student_creation_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.store'), [])
            ->assertSessionHasErrors([
                'nama_lengkap', 'nama_panggilan', 'jenis_kelamin',
                'tanggal_masuk', 'current_level_id', 'username', 'password',
            ]);
    }

    public function test_admin_can_view_student_detail(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level1->id]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.murid.show', $student->id))
            ->assertStatus(200);
    }

    public function test_admin_can_edit_student(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level1->id]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.murid.edit', $student->id))
            ->assertStatus(200)
            ->assertSee($student->nama_lengkap);
    }

    public function test_admin_can_update_student(): void
    {
        $student = User::factory()->create([
            'nama_lengkap' => 'Nama Lama',
            'current_level_id' => $this->level1->id,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.murid.update', $student->id), [
                'nama_lengkap' => 'Nama Baru',
                'nama_panggilan' => 'Panggilan Baru',
                'jenis_kelamin' => 'P',
                'tanggal_masuk' => '2026-06-15',
                'username' => $student->username,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.murid.show', $student->id));

        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'nama_lengkap' => 'Nama Baru',
        ]);
    }

    public function test_admin_can_reset_password(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level1->id]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.reset-password', $student->id), [
                'password' => 'newpassword123',
            ])
            ->assertRedirect(route('admin.murid.show', $student->id));
    }

    public function test_admin_can_naik_level(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level1->id]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.naik-level', $student->id))
            ->assertRedirect(route('admin.murid.show', $student->id));

        $this->assertEquals($this->level2->id, $student->fresh()->current_level_id);
    }

    public function test_admin_can_turun_level(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level2->id]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.turun-level', $student->id))
            ->assertRedirect(route('admin.murid.show', $student->id));

        $this->assertEquals($this->level1->id, $student->fresh()->current_level_id);
    }

    public function test_naik_level_fails_at_highest(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level2->id]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.naik-level', $student->id))
            ->assertRedirect();

        // Level should remain the same
        $this->assertEquals($this->level2->id, $student->fresh()->current_level_id);
    }

    public function test_turun_level_fails_at_lowest(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level1->id]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.turun-level', $student->id))
            ->assertRedirect();

        $this->assertEquals($this->level1->id, $student->fresh()->current_level_id);
    }

    public function test_level_history_is_created_on_naik(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level1->id]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.naik-level', $student->id));

        $this->assertDatabaseHas('user_level_histories', [
            'user_id' => $student->id,
            'level_id' => $this->level2->id,
            'level_sebelumnya_id' => $this->level1->id,
            'admin_id' => $this->admin->id,
            'tipe' => 'naik',
        ]);
    }

    public function test_admin_can_deactivate_student(): void
    {
        $student = User::factory()->create([
            'current_level_id' => $this->level1->id,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.murid.destroy', $student->id))
            ->assertRedirect(route('admin.murid.index'));

        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'is_active' => false,
        ]);
    }
}
