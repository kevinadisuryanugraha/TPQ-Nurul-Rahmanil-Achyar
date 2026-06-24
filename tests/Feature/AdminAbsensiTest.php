<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Admin;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAbsensiTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Level $level;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $this->student = User::factory()->create(['current_level_id' => $this->level->id]);
        $this->admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_absensi_routes(): void
    {
        $this->get('/admin/absensi')->assertRedirect('/login');
        $this->get('/admin/absensi/create')->assertRedirect('/login');
        $this->get('/admin/absensi/1/edit')->assertRedirect('/login');
        $this->put('/admin/absensi/1')->assertRedirect('/login');
        $this->delete('/admin/absensi/1')->assertRedirect('/login');
        $this->get('/admin/absensi-rekap')->assertRedirect('/login');
    }

    public function test_admin_can_view_absensi_list(): void
    {
        Absensi::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'sesi' => 'pagi',
            'status' => 'hadir',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/absensi')
            ->assertStatus(200);
    }

    public function test_admin_can_view_create_form(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/absensi/create')
            ->assertStatus(200);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $absensi = Absensi::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'sesi' => 'pagi',
            'status' => 'hadir',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.absensi.edit', $absensi->id))
            ->assertStatus(200);
    }

    public function test_admin_can_update_absensi(): void
    {
        $absensi = Absensi::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'sesi' => 'pagi',
            'status' => 'hadir',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.absensi.update', $absensi->id), [
                'status' => 'izin',
                'catatan' => 'Izin acara keluarga',
            ])
            ->assertRedirect(route('admin.absensi.index'));

        $this->assertDatabaseHas('absensis', [
            'id' => $absensi->id,
            'status' => 'izin',
            'catatan' => 'Izin acara keluarga',
        ]);
    }

    public function test_admin_can_delete_absensi(): void
    {
        $absensi = Absensi::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'sesi' => 'pagi',
            'status' => 'hadir',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.absensi.destroy', $absensi->id))
            ->assertRedirect(route('admin.absensi.index'));

        $this->assertDatabaseMissing('absensis', ['id' => $absensi->id]);
    }

    public function test_admin_can_view_rekap(): void
    {
        Absensi::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => now(),
            'sesi' => 'pagi',
            'status' => 'hadir',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/absensi-rekap')
            ->assertStatus(200);
    }

    public function test_update_requires_valid_status(): void
    {
        $absensi = Absensi::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'sesi' => 'pagi',
            'status' => 'hadir',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.absensi.update', $absensi->id), [
                'status' => 'invalid_status',
            ])
            ->assertSessionHasErrors(['status']);
    }
}
