<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private Level $level1;
    private Level $level2;
    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->level1 = Level::factory()->create(['urutan' => 1, 'nama' => 'Pra-Iqra']);
        $this->level2 = Level::factory()->create(['urutan' => 2, 'nama' => 'Iqra 1']);
        
        $this->student = User::factory()->create([
            'current_level_id' => $this->level1->id,
            'no_hp_orang_tua' => '081234567890'
        ]);
        
        $this->admin = Admin::create([
            'nama' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Clean WhatsApp log file before testing
        $logPath = storage_path('logs/whatsapp.log');
        if (file_exists($logPath)) {
            unlink($logPath);
        }
    }

    public function test_whatsapp_notification_sent_on_level_promotion(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.murid.naik-level', $this->student->id), [
                'catatan' => 'Naik tingkat karena lancar'
            ])
            ->assertRedirect(route('admin.murid.show', $this->student->id));

        $this->student->refresh();
        $this->assertEquals($this->level2->id, $this->student->current_level_id);

        $logPath = storage_path('logs/whatsapp.log');
        $this->assertTrue(file_exists($logPath));
        
        $logContent = file_get_contents($logPath);
        $this->assertStringContainsString('6281234567890', $logContent);
        $this->assertStringContainsString('naik ke level', $logContent);
    }

    public function test_whatsapp_notification_sent_on_attendance_alpha(): void
    {
        $success = \App\Services\WhatsAppService::sendAbsenceNotification($this->student, '2026-07-02', 'Pagi');
        $this->assertTrue($success);

        $logPath = storage_path('logs/whatsapp.log');
        $this->assertTrue(file_exists($logPath));
        
        $logContent = file_get_contents($logPath);
        $this->assertStringContainsString('6281234567890', $logContent);
        $this->assertStringContainsString('Tidak Hadir (Tanpa Keterangan/Alpha)', $logContent);
    }
}
