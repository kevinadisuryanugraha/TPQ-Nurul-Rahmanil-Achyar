<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Level;
use App\Models\Pengumuman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPengumumanTest extends TestCase
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

    public function test_guest_cannot_access_pengumuman_routes(): void
    {
        $this->get('/admin/pengumuman')->assertRedirect('/login');
        $this->get('/admin/pengumuman/create')->assertRedirect('/login');
        $this->get('/admin/pengumuman/1/edit')->assertRedirect('/login');
        $this->post('/admin/pengumuman')->assertRedirect('/login');
        $this->put('/admin/pengumuman/1')->assertRedirect('/login');
        $this->delete('/admin/pengumuman/1')->assertRedirect('/login');
    }

    public function test_admin_can_view_pengumuman_index(): void
    {
        Pengumuman::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Pengumuman Libur',
            'isi' => 'Libur Idul Fitri',
            'target_semua' => true,
            'tanggal_mulai' => '2026-07-01',
            'status' => 'published',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/pengumuman')
            ->assertStatus(200)
            ->assertSee('Pengumuman Libur');
    }

    public function test_admin_can_view_create_form(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.pengumuman.create'))
            ->assertStatus(200);
    }

    public function test_admin_can_store_pengumuman(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengumuman.store'), [
                'judul' => 'Pengumuman Baru',
                'isi' => 'Isi pengumuman tes',
                'target_semua' => '1',
                'tanggal_mulai' => '2026-07-01',
                'status' => 'draft',
            ])
            ->assertRedirect(route('admin.pengumuman.index'));

        $this->assertDatabaseHas('pengumumans', [
            'judul' => 'Pengumuman Baru',
            'target_semua' => true,
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_store_pengumuman_with_level_target(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengumuman.store'), [
                'judul' => 'Pengumuman Per Level',
                'isi' => 'Khusus level tertentu',
                'target_semua' => '0',
                'level_target_id' => $this->level->id,
                'tanggal_mulai' => '2026-07-01',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.pengumuman.index'));

        $this->assertDatabaseHas('pengumumans', [
            'judul' => 'Pengumuman Per Level',
            'target_semua' => false,
            'level_target_id' => $this->level->id,
        ]);
    }

    public function test_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.pengumuman.store'), [])
            ->assertSessionHasErrors(['judul', 'isi', 'target_semua', 'tanggal_mulai', 'status']);
    }

    public function test_admin_can_edit_pengumuman(): void
    {
        $pengumuman = Pengumuman::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Pengumuman Edit',
            'isi' => 'Akan diedit',
            'target_semua' => true,
            'tanggal_mulai' => '2026-07-01',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.pengumuman.edit', $pengumuman->id))
            ->assertStatus(200)
            ->assertSee('Pengumuman Edit');
    }

    public function test_admin_can_update_pengumuman(): void
    {
        $pengumuman = Pengumuman::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Judul Lama',
            'isi' => 'Isi lama',
            'target_semua' => true,
            'tanggal_mulai' => '2026-07-01',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.pengumuman.update', $pengumuman->id), [
                'judul' => 'Judul Baru',
                'isi' => 'Isi baru',
                'target_semua' => '1',
                'tanggal_mulai' => '2026-08-01',
                'tanggal_berakhir' => '2026-08-15',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.pengumuman.index'));

        $this->assertDatabaseHas('pengumumans', [
            'id' => $pengumuman->id,
            'judul' => 'Judul Baru',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_pengumuman(): void
    {
        $pengumuman = Pengumuman::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Pengumuman Dihapus',
            'isi' => 'Akan dihapus',
            'target_semua' => true,
            'tanggal_mulai' => '2026-07-01',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.pengumuman.destroy', $pengumuman->id))
            ->assertRedirect(route('admin.pengumuman.index'));

        $this->assertDatabaseMissing('pengumumans', ['id' => $pengumuman->id]);
    }
}
