<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doa;
use App\Models\Hadist;
use App\Models\CeritaKisah;
use App\Models\PanduanPraktik;
use App\Models\LangkahPanduan;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKontenTest extends TestCase
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

    // =====================
    // DOA MANAGEMENT
    // =====================

    public function test_admin_can_view_doa_index(): void
    {
        Doa::create([
            'judul' => 'Doa Sebelum Tidur',
            'teks_arab' => 'بِسْمِكَ اللَّهُمَّ',
            'transliterasi' => 'Bismikallahumma',
            'terjemahan' => 'Dengan nama-Mu ya Allah',
            'kategori' => 'Doa Harian',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/konten/doa')
            ->assertStatus(200)
            ->assertSee('Doa Sebelum Tidur');
    }

    public function test_admin_can_store_doa(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.doa.store'), [
                'judul' => 'Doa Baru',
                'teks_arab' => 'رَبَّنَا آتِنَا',
                'transliterasi' => 'Rabbana atina',
                'terjemahan' => 'Ya Tuhan kami, berilah kami',
                'kategori' => 'Doa Harian',
            ])
            ->assertRedirect(route('admin.konten.doa.index'));

        $this->assertDatabaseHas('duas', [
            'judul' => 'Doa Baru',
            'is_active' => true,
        ]);
    }

    public function test_doa_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.doa.store'), [])
            ->assertSessionHasErrors(['judul', 'teks_arab', 'transliterasi', 'terjemahan', 'kategori']);
    }

    public function test_admin_can_update_doa(): void
    {
        $doa = Doa::create([
            'judul' => 'Doa Lama',
            'teks_arab' => 'الْحَمْدُ لِلَّهِ',
            'transliterasi' => 'Alhamdulillah',
            'terjemahan' => 'Segala puji bagi Allah',
            'kategori' => 'Doa Harian',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.konten.doa.update', $doa->id), [
                'judul' => 'Doa Diperbarui',
                'teks_arab' => 'سُبْحَانَ اللَّهِ',
                'transliterasi' => 'Subhanallah',
                'terjemahan' => 'Maha Suci Allah',
                'kategori' => 'Dzikir',
                'is_active' => false,
            ])
            ->assertRedirect(route('admin.konten.doa.index'));

        $this->assertDatabaseHas('duas', [
            'id' => $doa->id,
            'judul' => 'Doa Diperbarui',
            'kategori' => 'Dzikir',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_doa(): void
    {
        $doa = Doa::create([
            'judul' => 'Doa Dihapus',
            'teks_arab' => 'آمِينَ',
            'transliterasi' => 'Aamiin',
            'terjemahan' => 'Kabulkanlah',
            'kategori' => 'Doa Harian',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.konten.doa.destroy', $doa->id))
            ->assertRedirect(route('admin.konten.doa.index'));

        $this->assertDatabaseMissing('duas', ['id' => $doa->id]);
    }

    // =====================
    // HADIST MANAGEMENT
    // =====================

    public function test_admin_can_view_hadist_index(): void
    {
        Hadist::create([
            'teks_arab' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ',
            'terjemahan' => 'Sesungguhnya amal itu tergantung niatnya',
            'sumber_kitab' => 'Bukhari',
            'perawi' => 'Umar bin Khattab',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/konten/hadist')
            ->assertStatus(200)
            ->assertSee('Bukhari');
    }

    public function test_admin_can_store_hadist(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.hadist.store'), [
                'teks_arab' => 'خَيْرُكُمْ خَيْرُكُمْ لأَهْلِهِ',
                'terjemahan' => 'Sebaik-baik kalian adalah yang terbaik bagi keluarganya',
                'sumber_kitab' => 'Tirmidzi',
                'perawi' => 'Aisyah',
                'kategori' => 'Akhlak',
            ])
            ->assertRedirect(route('admin.konten.hadist.index'));

        $this->assertDatabaseHas('hadiths', [
            'sumber_kitab' => 'Tirmidzi',
            'is_active' => true,
        ]);
    }

    public function test_hadist_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.hadist.store'), [])
            ->assertSessionHasErrors(['teks_arab', 'terjemahan', 'sumber_kitab']);
    }

    public function test_admin_can_update_hadist(): void
    {
        $hadist = Hadist::create([
            'teks_arab' => 'Hadist Lama',
            'terjemahan' => 'Terjemahan lama',
            'sumber_kitab' => 'Bukhari',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.konten.hadist.update', $hadist->id), [
                'teks_arab' => 'Hadist Baru',
                'terjemahan' => 'Terjemahan baru',
                'sumber_kitab' => 'Muslim',
                'is_active' => false,
            ])
            ->assertRedirect(route('admin.konten.hadist.index'));

        $this->assertDatabaseHas('hadiths', [
            'id' => $hadist->id,
            'sumber_kitab' => 'Muslim',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_hadist(): void
    {
        $hadist = Hadist::create([
            'teks_arab' => 'Hadist Dihapus',
            'terjemahan' => 'Terjemahan',
            'sumber_kitab' => 'Abu Dawud',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.konten.hadist.destroy', $hadist->id))
            ->assertRedirect(route('admin.konten.hadist.index'));

        $this->assertDatabaseMissing('hadiths', ['id' => $hadist->id]);
    }

    // =====================
    // CERITA KISAH MANAGEMENT
    // =====================

    public function test_admin_can_view_cerita_index(): void
    {
        CeritaKisah::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Kisah Nabi Nuh',
            'konten' => '<p>Kisah Nabi Nuh AS</p>',
            'kategori' => 'kisah_nabi',
            'status' => 'published',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get('/admin/konten/cerita')
            ->assertStatus(200)
            ->assertSee('Kisah Nabi Nuh');
    }

    public function test_admin_can_view_cerita_create_form(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.konten.cerita.create'))
            ->assertStatus(200);
    }

    public function test_admin_can_store_cerita(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.cerita.store'), [
                'judul' => 'Kisah Baru',
                'konten' => '<p>Cerita baru</p>',
                'kategori' => 'kisah_sahabat',
                'level_target_id' => $this->level->id,
                'status' => 'draft',
            ])
            ->assertRedirect(route('admin.konten.cerita.index'));

        $this->assertDatabaseHas('cerita_kisahs', [
            'judul' => 'Kisah Baru',
            'kategori' => 'kisah_sahabat',
            'status' => 'draft',
        ]);
    }

    public function test_cerita_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.cerita.store'), [])
            ->assertSessionHasErrors(['judul', 'konten', 'kategori', 'status']);
    }

    public function test_admin_can_edit_cerita(): void
    {
        $cerita = CeritaKisah::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Cerita Lama',
            'konten' => '<p>Lama</p>',
            'kategori' => 'kisah_nabi',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.konten.cerita.edit', $cerita->id))
            ->assertStatus(200)
            ->assertSee('Cerita Lama');
    }

    public function test_admin_can_update_cerita(): void
    {
        $cerita = CeritaKisah::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Cerita Lama',
            'konten' => '<p>Konten lama</p>',
            'kategori' => 'kisah_nabi',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.konten.cerita.update', $cerita->id), [
                'judul' => 'Cerita Diperbarui',
                'konten' => '<p>Konten baru</p>',
                'kategori' => 'islami_lainnya',
                'status' => 'published',
                'level_target_id' => $this->level->id,
            ])
            ->assertRedirect(route('admin.konten.cerita.index'));

        $this->assertDatabaseHas('cerita_kisahs', [
            'id' => $cerita->id,
            'judul' => 'Cerita Diperbarui',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_cerita(): void
    {
        $cerita = CeritaKisah::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Cerita Dihapus',
            'konten' => '<p>Dihapus</p>',
            'kategori' => 'kisah_nabi',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.konten.cerita.destroy', $cerita->id))
            ->assertRedirect(route('admin.konten.cerita.index'));

        $this->assertDatabaseMissing('cerita_kisahs', ['id' => $cerita->id]);
    }

    // =====================
    // PANDUAN PRAKTIK MANAGEMENT
    // =====================

    public function test_admin_can_view_panduan_index(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/admin/konten/panduan-praktik')
            ->assertStatus(200);
    }

    public function test_admin_can_view_panduan_create_form(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.konten.panduan.create'))
            ->assertStatus(200);
    }

    public function test_admin_can_store_panduan(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.panduan.store'), [
                'judul' => 'Panduan Wudhu',
                'deskripsi' => 'Langkah-langkah wudhu yang benar',
                'jenis_praktik' => 'wudhu',
                'level_target_id' => $this->level->id,
                'status' => 'draft',
            ])
            ->assertRedirect(route('admin.konten.panduan.show', PanduanPraktik::first()));

        $this->assertDatabaseHas('panduan_praktiks', [
            'judul' => 'Panduan Wudhu',
            'jenis_praktik' => 'wudhu',
        ]);
    }

    public function test_panduan_store_requires_required_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.panduan.store'), [])
            ->assertSessionHasErrors(['judul', 'deskripsi', 'jenis_praktik', 'status']);
    }

    public function test_admin_can_show_panduan(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan Sholat',
            'deskripsi' => 'Panduan lengkap',
            'jenis_praktik' => 'sholat_fardhu',
            'status' => 'published',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.konten.panduan.show', $panduan->id))
            ->assertStatus(200);
    }

    public function test_admin_can_edit_panduan(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan Lama',
            'deskripsi' => 'Deskripsi lama',
            'jenis_praktik' => 'wudhu',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.konten.panduan.edit', $panduan->id))
            ->assertStatus(200);
    }

    public function test_admin_can_update_panduan(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan Lama',
            'deskripsi' => 'Deskripsi lama',
            'jenis_praktik' => 'wudhu',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.konten.panduan.update', $panduan->id), [
                'judul' => 'Panduan Baru',
                'deskripsi' => 'Deskripsi baru',
                'jenis_praktik' => 'sholat_fardhu',
                'level_target_id' => $this->level->id,
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.konten.panduan.show', $panduan->id));

        $this->assertDatabaseHas('panduan_praktiks', [
            'id' => $panduan->id,
            'judul' => 'Panduan Baru',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_panduan(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan Dihapus',
            'deskripsi' => 'Dihapus',
            'jenis_praktik' => 'tayamum',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.konten.panduan.destroy', $panduan->id))
            ->assertRedirect(route('admin.konten.panduan.index'));

        $this->assertDatabaseMissing('panduan_praktiks', ['id' => $panduan->id]);
    }

    // =====================
    // LANGKAH PANDUAN MANAGEMENT
    // =====================

    public function test_admin_can_store_langkah(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan Wudhu',
            'deskripsi' => 'Langkah wudhu',
            'jenis_praktik' => 'wudhu',
            'status' => 'published',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.langkah.store', $panduan->id), [
                'nomor_urut' => 1,
                'judul_langkah' => 'Niat',
                'deskripsi' => 'Membaca niat wudhu',
            ])
            ->assertRedirect(route('admin.konten.panduan.show', $panduan->id));

        $this->assertDatabaseHas('langkah_panduans', [
            'panduan_praktik_id' => $panduan->id,
            'nomor_urut' => 1,
            'judul_langkah' => 'Niat',
        ]);
    }

    public function test_langkah_store_requires_required_fields(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan',
            'deskripsi' => 'Test',
            'jenis_praktik' => 'wudhu',
            'status' => 'draft',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.langkah.store', $panduan->id), [])
            ->assertSessionHasErrors(['nomor_urut', 'judul_langkah', 'deskripsi']);
    }

    public function test_admin_can_update_langkah(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan',
            'deskripsi' => 'Test',
            'jenis_praktik' => 'wudhu',
            'status' => 'draft',
        ]);

        $langkah = LangkahPanduan::create([
            'panduan_praktik_id' => $panduan->id,
            'nomor_urut' => 1,
            'judul_langkah' => 'Langkah Lama',
            'deskripsi' => 'Deskripsi lama',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.konten.langkah.update', $langkah->id), [
                'nomor_urut' => 2,
                'judul_langkah' => 'Langkah Baru',
                'deskripsi' => 'Deskripsi baru',
            ])
            ->assertRedirect(route('admin.konten.panduan.show', $panduan->id));

        $this->assertDatabaseHas('langkah_panduans', [
            'id' => $langkah->id,
            'nomor_urut' => 2,
            'judul_langkah' => 'Langkah Baru',
        ]);
    }

    public function test_admin_can_delete_langkah(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan',
            'deskripsi' => 'Test',
            'jenis_praktik' => 'wudhu',
            'status' => 'draft',
        ]);

        $langkah = LangkahPanduan::create([
            'panduan_praktik_id' => $panduan->id,
            'nomor_urut' => 1,
            'judul_langkah' => 'Langkah Awal',
            'deskripsi' => 'Deskripsi',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.konten.langkah.destroy', $langkah->id))
            ->assertRedirect(route('admin.konten.panduan.show', $panduan->id));

        $this->assertDatabaseMissing('langkah_panduans', ['id' => $langkah->id]);
    }

    public function test_langkah_unique_nomor_urut_per_panduan(): void
    {
        $panduan = PanduanPraktik::create([
            'admin_id' => $this->admin->id,
            'judul' => 'Panduan',
            'deskripsi' => 'Test',
            'jenis_praktik' => 'wudhu',
            'status' => 'draft',
        ]);

        LangkahPanduan::create([
            'panduan_praktik_id' => $panduan->id,
            'nomor_urut' => 1,
            'judul_langkah' => 'Pertama',
            'deskripsi' => 'Pertama',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.konten.langkah.store', $panduan->id), [
                'nomor_urut' => 1,
                'judul_langkah' => 'Duplikat',
                'deskripsi' => 'Duplikat nomor urut',
            ])
            ->assertSessionHasErrors(['nomor_urut']);
    }

    // =====================
    // GUEST ACCESS
    // =====================

    public function test_guest_cannot_access_konten_routes(): void
    {
        $this->get('/admin/konten/doa')->assertRedirect('/login');
        $this->get('/admin/konten/hadist')->assertRedirect('/login');
        $this->get('/admin/konten/cerita')->assertRedirect('/login');
        $this->get('/admin/konten/panduan-praktik')->assertRedirect('/login');
        $this->post('/admin/konten/doa')->assertRedirect('/login');
        $this->post('/admin/konten/hadist')->assertRedirect('/login');
        $this->post('/admin/konten/cerita')->assertRedirect('/login');
        $this->post('/admin/konten/panduan-praktik')->assertRedirect('/login');
    }
}
