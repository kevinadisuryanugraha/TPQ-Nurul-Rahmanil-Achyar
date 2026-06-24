<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MuridPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private Level $level;

    protected function setUp(): void
    {
        parent::setUp();

        $this->level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $this->student = User::factory()->create(['current_level_id' => $this->level->id]);
    }

    public function test_quran_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/quran')
            ->assertStatus(200);
    }

    public function test_doa_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/doa')
            ->assertStatus(200);
    }

    public function test_hadist_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/hadist')
            ->assertStatus(200);
    }

    public function test_cerita_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/cerita')
            ->assertStatus(200);
    }

    public function test_panduan_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/panduan')
            ->assertStatus(200);
    }

    public function test_nilai_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/nilai')
            ->assertStatus(200);
    }

    public function test_absensi_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/absensi')
            ->assertStatus(200);
    }

    public function test_pengumuman_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/pengumuman')
            ->assertStatus(200);
    }

    public function test_asmaul_husna_index_loads(): void
    {
        $this->actingAs($this->student, 'web')
            ->get('/murid/asmaul-husna')
            ->assertStatus(200);
    }
}
