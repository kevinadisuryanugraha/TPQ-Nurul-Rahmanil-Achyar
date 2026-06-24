<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Level;
use App\Models\Pendaftar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PSBFlowTest extends TestCase
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

    public function test_daftar_page_loads(): void
    {
        $this->get('/daftar')->assertStatus(200);
    }

    public function test_public_can_submit_registration(): void
    {
        $response = $this->post('/daftar', [
            'nama_lengkap' => 'Ahmad Rijal',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2015-06-15',
            'jenis_kelamin' => 'L',
            'nama_orang_tua' => 'Bapak Rijal',
            'no_wa' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 1',
            'pernah_mengaji' => 'ya',
            'level_mengaji_sebelumnya' => 'Iqra 2',
        ]);

        $response->assertRedirect(route('daftar.thankyou'));

        $this->assertDatabaseHas('pendaftars', [
            'nama_lengkap' => 'Ahmad Rijal',
            'status' => 'baru',
            'pernah_mengaji' => 1,
        ]);
    }

    public function test_honeypot_field_silently_redirects(): void
    {
        $response = $this->post('/daftar', [
            'website_url' => 'spam-bot',
            'nama_lengkap' => 'Bot User',
            'no_wa' => '08123456789',
        ]);

        $response->assertRedirect(route('daftar.thankyou'));

        $this->assertDatabaseMissing('pendaftars', [
            'nama_lengkap' => 'Bot User',
        ]);
    }

    public function test_validation_errors_for_empty_submission(): void
    {
        $response = $this->post('/daftar', []);

        $response->assertSessionHasErrors([
            'nama_lengkap', 'tanggal_lahir', 'jenis_kelamin',
            'nama_orang_tua', 'no_wa', 'alamat', 'pernah_mengaji',
        ]);
    }

    public function test_duplicate_registration_is_detected(): void
    {
        $this->post('/daftar', [
            'nama_lengkap' => 'Siti Aminah',
            'tanggal_lahir' => '2016-03-20',
            'jenis_kelamin' => 'P',
            'nama_orang_tua' => 'Ibu Aminah',
            'no_wa' => '08129876543',
            'alamat' => 'Jl. Melati No. 5',
            'pernah_mengaji' => 'tidak',
        ]);

        $response = $this->post('/daftar', [
            'nama_lengkap' => 'Siti Aminah',
            'tanggal_lahir' => '2016-03-20',
            'jenis_kelamin' => 'P',
            'nama_orang_tua' => 'Ibu Aminah',
            'no_wa' => '08129876543',
            'alamat' => 'Jl. Melati No. 5',
            'pernah_mengaji' => 'tidak',
        ]);

        $response->assertRedirect(route('daftar.thankyou'));
        $response->assertSessionHas('is_duplicate', true);
    }

    public function test_thankyou_page_requires_session(): void
    {
        $this->get('/daftar/terima-kasih')->assertRedirect(route('landing'));
    }

    public function test_thankyou_page_shows_with_session(): void
    {
        $this->withSession(['nama' => 'Test Santri', 'is_duplicate' => false])
            ->get('/daftar/terima-kasih')
            ->assertStatus(200);
    }

    public function test_admin_can_view_registrations_list(): void
    {
        $this->post('/daftar', [
            'nama_lengkap' => 'List Test Santri',
            'tanggal_lahir' => '2015-01-01',
            'jenis_kelamin' => 'L',
            'nama_orang_tua' => 'List Parent',
            'no_wa' => '08111111111',
            'alamat' => 'List Alamat',
            'pernah_mengaji' => 'tidak',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/landing/pendaftaran')
            ->assertStatus(200)
            ->assertSee('List Test Santri');
    }

    public function test_admin_can_view_registration_detail(): void
    {
        $pendaftar = Pendaftar::create([
            'nama_lengkap' => 'Detail Test Santri',
            'tanggal_lahir' => '2015-05-10',
            'jenis_kelamin' => 'P',
            'nama_orang_tua' => 'Detail Parent',
            'no_wa' => '08222222222',
            'alamat' => 'Detail Alamat',
            'pernah_mengaji' => false,
            'status' => 'baru',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.landing.pendaftaran.show', $pendaftar->id))
            ->assertStatus(200)
            ->assertSee('Detail Test Santri');
    }

    public function test_admin_can_update_registration_status(): void
    {
        $pendaftar = Pendaftar::create([
            'nama_lengkap' => 'Status Test',
            'tanggal_lahir' => '2015-07-20',
            'jenis_kelamin' => 'L',
            'nama_orang_tua' => 'Status Parent',
            'no_wa' => '08333333333',
            'alamat' => 'Status Alamat',
            'pernah_mengaji' => false,
            'status' => 'baru',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.landing.pendaftaran.update-status', $pendaftar->id), [
                'status' => 'dihubungi',
                'catatan_internal' => 'Sudah dihubungi via WA.',
            ])
            ->assertRedirect(route('admin.landing.pendaftaran.show', $pendaftar->id));

        $this->assertDatabaseHas('pendaftars', [
            'id' => $pendaftar->id,
            'status' => 'dihubungi',
            'catatan_internal' => 'Sudah dihubungi via WA.',
        ]);
    }

    public function test_terima_form_redirects_to_create_student(): void
    {
        $pendaftar = Pendaftar::create([
            'nama_lengkap' => 'Terima Test',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2014-11-15',
            'jenis_kelamin' => 'P',
            'nama_orang_tua' => 'Terima Parent',
            'no_wa' => '08444444444',
            'alamat' => 'Terima Alamat',
            'pernah_mengaji' => true,
            'level_mengaji_sebelumnya' => 'Al-Quran',
            'status' => 'baru',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.landing.pendaftaran.terima', $pendaftar->id))
            ->assertRedirect(route('admin.murid.create', [
                'prefill_nama' => 'Terima Test',
                'prefill_tempat_lahir' => 'Bandung',
                'prefill_tanggal_lahir' => '2014-11-15',
                'prefill_jenis_kelamin' => 'P',
                'prefill_nama_ortu' => 'Terima Parent',
                'prefill_no_hp_ortu' => '08444444444',
                'prefill_alamat' => 'Terima Alamat',
                'pendaftar_id' => $pendaftar->id,
            ]));
    }

    public function test_terima_form_rejects_already_accepted(): void
    {
        $pendaftar = Pendaftar::create([
            'nama_lengkap' => 'Already Accepted',
            'tanggal_lahir' => '2013-01-01',
            'jenis_kelamin' => 'L',
            'nama_orang_tua' => 'Accepted Parent',
            'no_wa' => '08555555555',
            'alamat' => 'Accepted Alamat',
            'pernah_mengaji' => false,
            'status' => 'diterima',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.landing.pendaftaran.terima', $pendaftar->id))
            ->assertRedirect(route('admin.landing.pendaftaran.show', $pendaftar->id))
            ->assertSessionHas('error');
    }

    public function test_full_psb_flow_creates_student_from_registration(): void
    {
        // Step 1: Public registration
        $this->post('/daftar', [
            'nama_lengkap' => 'Full Flow Santri',
            'tempat_lahir' => 'Bogor',
            'tanggal_lahir' => '2016-08-25',
            'jenis_kelamin' => 'L',
            'nama_orang_tua' => 'Full Flow Parent',
            'no_wa' => '08666666666',
            'alamat' => 'Full Flow Alamat',
            'pernah_mengaji' => 'tidak',
        ]);

        $pendaftar = Pendaftar::where('nama_lengkap', 'Full Flow Santri')->first();
        $this->assertNotNull($pendaftar);
        $this->assertEquals('baru', $pendaftar->status);

        // Step 2: Admin updates status to "dihubungi"
        $this->actingAs($this->admin, 'admin')
            ->patch(route('admin.landing.pendaftaran.update-status', $pendaftar->id), [
                'status' => 'dihubungi',
            ]);

        $this->assertDatabaseHas('pendaftars', [
            'id' => $pendaftar->id,
            'status' => 'dihubungi',
        ]);

        // Step 3: Admin creates student account linked to this registration
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.store'), [
                'nama_lengkap' => 'Full Flow Santri',
                'nama_panggilan' => 'FullFlow',
                'jenis_kelamin' => 'L',
                'tanggal_masuk' => '2026-07-01',
                'current_level_id' => $this->level->id,
                'username' => 'fullflow',
                'password' => 'rahasia123',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir' => '2016-08-25',
                'nama_orang_tua' => 'Full Flow Parent',
                'no_hp_orang_tua' => '08666666666',
                'alamat' => 'Full Flow Alamat',
                'pendaftar_id' => $pendaftar->id,
            ])
            ->assertRedirect(route('admin.murid.index'));

        // Step 4: Verify student created
        $student = User::where('username', 'fullflow')->first();
        $this->assertNotNull($student);
        $this->assertEquals('Full Flow Santri', $student->nama_lengkap);

        // Step 5: Verify pendaftar linked and status updated
        $pendaftar->refresh();
        $this->assertEquals('diterima', $pendaftar->status);
        $this->assertEquals($student->id, $pendaftar->user_id);
    }

    public function test_guest_cannot_access_admin_psb_routes(): void
    {
        $this->get('/admin/landing/pendaftaran')->assertRedirect('/login');
        $this->get('/admin/landing/pendaftaran/1')->assertRedirect('/login');
        $this->patch('/admin/landing/pendaftaran/1/status')->assertRedirect('/login');
    }
}
