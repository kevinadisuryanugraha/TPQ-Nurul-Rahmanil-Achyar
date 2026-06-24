<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private Level $level;

    protected function setUp(): void
    {
        parent::setUp();

        $this->level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);

        $this->admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_laporan_routes(): void
    {
        $this->get('/admin/laporan')->assertRedirect('/login');
        $this->get('/admin/laporan/murid')->assertRedirect('/login');
        $this->get('/admin/laporan/export-pdf')->assertRedirect('/login');
        $this->get('/admin/laporan/export-excel-murid')->assertRedirect('/login');
        $this->get('/admin/laporan/export-excel-kelas')->assertRedirect('/login');
    }

    public function test_admin_can_view_laporan_index(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/laporan')
            ->assertStatus(200);
    }

    public function test_admin_can_view_laporan_murid(): void
    {
        User::factory()->create([
            'nama_lengkap' => 'Santri Laporan',
            'current_level_id' => $this->level->id,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/laporan/murid')
            ->assertStatus(200)
            ->assertSee('Santri Laporan');
    }

    public function test_admin_can_export_pdf(): void
    {
        $student = User::factory()->create(['current_level_id' => $this->level->id]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.laporan.export-pdf', ['user_id' => $student->id]))
            ->assertStatus(200);
    }

    public function test_export_pdf_requires_valid_user(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.laporan.export-pdf', ['user_id' => 999]))
            ->assertSessionHasErrors(['user_id']);
    }

    public function test_admin_can_export_excel_murid(): void
    {
        User::factory()->create(['current_level_id' => $this->level->id]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/laporan/export-excel-murid')
            ->assertStatus(200);
    }

    public function test_admin_can_export_excel_murid_filtered_by_level(): void
    {
        User::factory()->create([
            'nama_lengkap' => 'Santri Level 1',
            'current_level_id' => $this->level->id,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.laporan.export-excel-murid', ['level_id' => $this->level->id]))
            ->assertStatus(200);
    }

    public function test_admin_can_export_excel_kelas(): void
    {
        User::factory()->create(['current_level_id' => $this->level->id]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.laporan.export-excel-kelas', ['level_id' => $this->level->id]))
            ->assertStatus(200);
    }

    public function test_export_excel_kelas_requires_level(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/laporan/export-excel-kelas')
            ->assertSessionHasErrors(['level_id']);
    }
}
