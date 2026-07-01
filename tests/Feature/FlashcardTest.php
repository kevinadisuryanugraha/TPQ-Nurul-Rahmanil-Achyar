<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\FlashcardDeck;
use App\Models\FlashcardItem;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashcardTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;
    private User $student;
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

        $this->student = User::factory()->create(['current_level_id' => $this->level->id]);
    }

    // =====================
    // ADMIN CMS TESTS
    // =====================

    public function test_admin_can_view_flashcard_decks_index(): void
    {
        FlashcardDeck::create([
            'nama' => 'Test Deck Hadist',
            'deskripsi' => 'Deskripsi Hadist',
            'source_type' => 'system_hadist',
            'level_target_id' => $this->level->id,
            'is_active' => true,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/konten/flashcard')
            ->assertStatus(200)
            ->assertSee('Test Deck Hadist');
    }

    public function test_admin_can_store_custom_flashcard_deck(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.flashcard.store'), [
                'nama' => 'Dek Kustom Baru',
                'deskripsi' => 'Deskripsi Kustom',
                'source_type' => 'custom',
                'level_target_id' => $this->level->id,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.konten.flashcard.index'));

        $this->assertDatabaseHas('flashcard_decks', [
            'nama' => 'Dek Kustom Baru',
            'source_type' => 'custom',
        ]);
    }

    public function test_admin_can_add_cards_to_custom_deck(): void
    {
        $deck = FlashcardDeck::create([
            'nama' => 'Dek Kustom',
            'source_type' => 'custom',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.flashcard.items.store', $deck->id), [
                'front_content' => 'Pertanyaan A',
                'back_content' => 'Jawaban A',
                'urutan' => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('flashcard_items', [
            'deck_id' => $deck->id,
            'front_content' => 'Pertanyaan A',
            'back_content' => 'Jawaban A',
        ]);
    }

    public function test_admin_cannot_add_cards_to_system_deck(): void
    {
        $deck = FlashcardDeck::create([
            'nama' => 'Dek Sistem Doa',
            'source_type' => 'system_doa',
            'is_active' => true,
        ]);

        // Attempting to visit custom items index of a system deck redirects to index
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.konten.flashcard.items.index', $deck->id))
            ->assertRedirect(route('admin.konten.flashcard.index'))
            ->assertSessionHas('error');
    }

    // =====================
    // STUDENT PORTAL TESTS
    // =====================

    public function test_student_can_view_flashcard_decks_index(): void
    {
        FlashcardDeck::create([
            'nama' => 'Dek Latihan Murid',
            'source_type' => 'custom',
            'is_active' => true,
        ]);

        $this->actingAs($this->student, 'web')
            ->get('/murid/flashcard')
            ->assertStatus(200)
            ->assertSee('Dek Latihan Murid');
    }

    public function test_student_can_view_flashcards_play_screen(): void
    {
        $deck = FlashcardDeck::create([
            'nama' => 'Dek Santri',
            'source_type' => 'custom',
            'is_active' => true,
        ]);

        FlashcardItem::create([
            'deck_id' => $deck->id,
            'front_content' => 'Soal 1',
            'back_content' => 'Jawaban 1',
            'urutan' => 1,
        ]);

        $this->actingAs($this->student, 'web')
            ->get(route('murid.flashcard.show', $deck->id))
            ->assertStatus(200)
            ->assertSee('Soal 1');
    }
}
