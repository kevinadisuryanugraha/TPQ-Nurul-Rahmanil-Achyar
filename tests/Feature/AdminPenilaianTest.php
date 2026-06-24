<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Level;
use App\Models\PenilaianBaca;
use App\Models\PenilaianHafalan;
use App\Models\PenilaianTulis;
use App\Models\PenilaianPraktik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPenilaianTest extends TestCase
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

    // === Main Index ===

    public function test_admin_can_view_penilaian_index(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/penilaian')
            ->assertStatus(200);
    }

    // === Baca (Reading) Assessment ===

    public function test_admin_can_view_baca_page(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/penilaian/baca')
            ->assertStatus(200);
    }

    public function test_admin_can_store_baca(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.baca.store'), [
                'user_id' => $this->student->id,
                'tanggal' => '2026-06-22',
                'jenis_bacaan' => 'iqra',
                'jilid_juz' => 1,
                'halaman_ayat' => 5,
                'kelancaran' => 'lancar',
            ])
            ->assertRedirect(route('admin.penilaian.baca', ['user_id' => $this->student->id]));

        $this->assertDatabaseHas('penilaian_bacas', [
            'user_id' => $this->student->id,
            'jenis_bacaan' => 'iqra',
            'kelancaran' => 'lancar',
        ]);
    }

    public function test_baca_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.baca.store'), [])
            ->assertSessionHasErrors(['user_id', 'tanggal', 'jenis_bacaan', 'kelancaran']);
    }

    public function test_admin_can_delete_baca(): void
    {
        $baca = PenilaianBaca::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'jenis_bacaan' => 'iqra',
            'kelancaran' => 'lancar',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.penilaian.baca.delete', $baca->id))
            ->assertRedirect(route('admin.penilaian.baca', ['user_id' => $this->student->id]));

        $this->assertDatabaseMissing('penilaian_bacas', ['id' => $baca->id]);
    }

    // === Hafalan (Memorization) Assessment ===

    public function test_admin_can_view_hafalan_page(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/penilaian/hafalan')
            ->assertStatus(200);
    }

    public function test_admin_can_store_hafalan(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.hafalan.store'), [
                'user_id' => $this->student->id,
                'tanggal' => '2026-06-22',
                'jenis_hafalan' => 'surat',
                'nama_item' => 'Al-Fatihah',
                'status' => 'hafal_sempurna',
            ])
            ->assertRedirect(route('admin.penilaian.hafalan', ['user_id' => $this->student->id]));

        $this->assertDatabaseHas('penilaian_hafalans', [
            'user_id' => $this->student->id,
            'jenis_hafalan' => 'surat',
            'nama_item' => 'Al-Fatihah',
            'status' => 'hafal_sempurna',
        ]);
    }

    public function test_hafalan_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.hafalan.store'), [])
            ->assertSessionHasErrors(['user_id', 'tanggal', 'jenis_hafalan', 'nama_item', 'status']);
    }

    public function test_admin_can_delete_hafalan(): void
    {
        $hafalan = PenilaianHafalan::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'jenis_hafalan' => 'doa',
            'nama_item' => 'Doa Sebelum Tidur',
            'status' => 'hafal_dengan_kesalahan',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.penilaian.hafalan.delete', $hafalan->id))
            ->assertRedirect(route('admin.penilaian.hafalan', ['user_id' => $this->student->id]));

        $this->assertDatabaseMissing('penilaian_hafalans', ['id' => $hafalan->id]);
    }

    // === Tulis (Writing) Assessment ===

    public function test_admin_can_view_tulis_page(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/penilaian/tulis')
            ->assertStatus(200);
    }

    public function test_admin_can_store_tulis(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.tulis.store'), [
                'user_id' => $this->student->id,
                'tanggal' => '2026-06-22',
                'materi' => 'Menulis Al-Fatihah',
                'nilai' => 85,
            ])
            ->assertRedirect(route('admin.penilaian.tulis', ['user_id' => $this->student->id]));

        $this->assertDatabaseHas('penilaian_tulises', [
            'user_id' => $this->student->id,
            'materi' => 'Menulis Al-Fatihah',
            'nilai' => 85,
            'grade' => 'B',
        ]);
    }

    public function test_tulis_store_calculates_grade_correctly(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.tulis.store'), [
                'user_id' => $this->student->id,
                'tanggal' => '2026-06-22',
                'materi' => 'Test Grade A',
                'nilai' => 95,
            ]);

        $this->assertDatabaseHas('penilaian_tulises', [
            'materi' => 'Test Grade A',
            'grade' => 'A',
        ]);
    }

    public function test_tulis_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.tulis.store'), [])
            ->assertSessionHasErrors(['user_id', 'tanggal', 'materi', 'nilai']);
    }

    public function test_admin_can_delete_tulis(): void
    {
        $tulis = PenilaianTulis::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'materi' => 'Menulis',
            'nilai' => 80,
            'grade' => 'B',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.penilaian.tulis.delete', $tulis->id))
            ->assertRedirect(route('admin.penilaian.tulis', ['user_id' => $this->student->id]));

        $this->assertDatabaseMissing('penilaian_tulises', ['id' => $tulis->id]);
    }

    // === Praktik (Practice) Assessment ===

    public function test_admin_can_view_praktik_page(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/penilaian/praktik')
            ->assertStatus(200);
    }

    public function test_admin_can_store_praktik(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.praktik.store'), [
                'user_id' => $this->student->id,
                'tanggal' => '2026-06-22',
                'jenis_praktik' => 'wudhu',
                'komponen' => [
                    'Niat Wudhu' => '1',
                    'Membasuh Muka' => '1',
                    'Membasuh Tangan' => '0',
                ],
            ])
            ->assertRedirect(route('admin.penilaian.praktik', ['user_id' => $this->student->id]));

        $this->assertDatabaseHas('penilaian_praktiks', [
            'user_id' => $this->student->id,
            'jenis_praktik' => 'wudhu',
        ]);

        $this->assertDatabaseHas('penilaian_praktik_komponens', [
            'nama_komponen' => 'Niat Wudhu',
            'is_terpenuhi' => 1,
        ]);

        $this->assertDatabaseHas('penilaian_praktik_komponens', [
            'nama_komponen' => 'Membasuh Muka',
            'is_terpenuhi' => 1,
        ]);

        $this->assertDatabaseHas('penilaian_praktik_komponens', [
            'nama_komponen' => 'Membasuh Tangan',
            'is_terpenuhi' => 0,
        ]);
    }

    public function test_praktik_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.penilaian.praktik.store'), [])
            ->assertSessionHasErrors(['user_id', 'tanggal', 'jenis_praktik', 'komponen']);
    }

    public function test_admin_can_delete_praktik(): void
    {
        $praktik = PenilaianPraktik::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => '2026-06-22',
            'jenis_praktik' => 'sholat_fardhu',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.penilaian.praktik.delete', $praktik->id))
            ->assertRedirect(route('admin.penilaian.praktik', ['user_id' => $this->student->id]));

        $this->assertDatabaseMissing('penilaian_praktiks', ['id' => $praktik->id]);
    }

    // === Guest Access ===

    public function test_guest_cannot_access_penilaian_routes(): void
    {
        $this->get('/admin/penilaian')->assertRedirect('/login');
        $this->get('/admin/penilaian/baca')->assertRedirect('/login');
        $this->get('/admin/penilaian/hafalan')->assertRedirect('/login');
        $this->get('/admin/penilaian/tulis')->assertRedirect('/login');
        $this->get('/admin/penilaian/praktik')->assertRedirect('/login');
        $this->post('/admin/penilaian/baca')->assertRedirect('/login');
        $this->post('/admin/penilaian/hafalan')->assertRedirect('/login');
        $this->post('/admin/penilaian/tulis')->assertRedirect('/login');
        $this->post('/admin/penilaian/praktik')->assertRedirect('/login');
        $this->delete('/admin/penilaian/baca/1')->assertRedirect('/login');
        $this->delete('/admin/penilaian/hafalan/1')->assertRedirect('/login');
        $this->delete('/admin/penilaian/tulis/1')->assertRedirect('/login');
        $this->delete('/admin/penilaian/praktik/1')->assertRedirect('/login');
    }
}
