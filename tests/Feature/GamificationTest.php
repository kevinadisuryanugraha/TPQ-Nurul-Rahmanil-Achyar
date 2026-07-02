<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Badge;
use App\Models\FlashcardDeck;
use App\Models\Level;
use App\Models\User;
use App\Models\Absensi;
use App\Models\PenilaianTulis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private Level $level;
    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed levels and badges
        $this->level = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $this->student = User::factory()->create(['current_level_id' => $this->level->id]);
        
        $this->admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->seed(\Database\Seeders\BadgeSeeder::class);
    }

    public function test_student_gets_points_and_badge_on_flashcard_completion(): void
    {
        $deck = FlashcardDeck::create([
            'nama' => 'Hadist Akhlak',
            'source_type' => 'system_hadist',
            'level_target_id' => $this->level->id,
            'is_active' => true,
        ]);

        // Finish flashcard first time
        $response = $this->actingAs($this->student, 'web')
            ->postJson(route('murid.flashcard.finish', $deck->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'points_earned' => 10,
            ]);

        $this->student->refresh();
        $this->assertEquals(10, $this->student->points);
        $this->assertEquals(1, $this->student->flashcard_completions_count);

        $badge = Badge::where('nama', 'Penghafal Pemula')->first();
        $this->assertTrue($this->student->badges->contains($badge->id));
    }

    public function test_student_gets_hafidz_cilik_on_five_flashcard_completions(): void
    {
        $deck = FlashcardDeck::create([
            'nama' => 'Hadist Akhlak',
            'source_type' => 'system_hadist',
            'level_target_id' => $this->level->id,
            'is_active' => true,
        ]);

        // Set completions count to 4
        $this->student->update(['flashcard_completions_count' => 4]);

        $response = $this->actingAs($this->student, 'web')
            ->postJson(route('murid.flashcard.finish', $deck->id));

        $response->assertStatus(200);
        $this->student->refresh();
        
        $badge = Badge::where('nama', 'Hafidz Cilik')->first();
        $this->assertTrue($this->student->badges->contains($badge->id));
    }

    public function test_student_gets_attendance_badge_on_absensi(): void
    {
        // Add 5 attendance records
        for ($i = 1; $i <= 5; $i++) {
            Absensi::create([
                'user_id' => $this->student->id,
                'admin_id' => $this->admin->id,
                'tanggal' => now()->subDays($i)->format('Y-m-d'),
                'sesi' => 'Pagi',
                'status' => 'hadir',
            ]);
        }

        // Trigger badge check manually
        \App\Services\GamificationService::checkAndAwardBadges($this->student, 'absensi');
        
        $this->student->refresh();
        $badge = Badge::where('nama', 'Rajin Mengaji')->first();
        $this->assertTrue($this->student->badges->contains($badge->id));
    }

    public function test_student_gets_cerdas_badge_on_average_writing_score(): void
    {
        // Add write scores
        PenilaianTulis::create([
            'user_id' => $this->student->id,
            'admin_id' => $this->admin->id,
            'tanggal' => now()->format('Y-m-d'),
            'materi' => 'Menulis Hijaiyah',
            'nilai' => 90,
            'grade' => 'A',
        ]);

        // Trigger badge check
        \App\Services\GamificationService::checkAndAwardBadges($this->student, 'tulis');

        $this->student->refresh();
        $badge = Badge::where('nama', 'Santri Cerdas')->first();
        $this->assertTrue($this->student->badges->contains($badge->id));
    }
}
