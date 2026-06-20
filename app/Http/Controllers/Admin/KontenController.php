<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doa;
use App\Models\Hadist;
use App\Models\CeritaKisah;
use App\Models\PanduanPraktik;
use App\Models\LangkahPanduan;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class KontenController extends Controller
{
    // ==========================================
    // DOA MANAGEMENT
    // ==========================================
    public function doaIndex(Request $request)
    {
        $query = Doa::orderBy('kategori')->orderBy('urutan');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('terjemahan', 'like', "%{$search}%");
            });
        }

        if ($request->has('kategori') && $request->kategori !== '') {
            $query->where('kategori', $request->kategori);
        }

        $doas = $query->paginate(15)->withQueryString();
        $categories = Doa::select('kategori')->distinct()->pluck('kategori');

        return view('admin.konten.doa.index', compact('doas', 'categories'));
    }

    public function doaStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:200',
            'teks_arab' => 'required|string',
            'transliterasi' => 'required|string',
            'terjemahan' => 'required|string',
            'kategori' => 'required|string|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        Doa::create([
            'judul' => $request->judul,
            'teks_arab' => $request->teks_arab,
            'transliterasi' => $request->transliterasi,
            'terjemahan' => $request->terjemahan,
            'kategori' => $request->kategori,
            'urutan' => $request->urutan,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.konten.doa.index')->with('success', 'Doa berhasil ditambahkan.');
    }

    public function doaUpdate(Request $request, $id)
    {
        $doa = Doa::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:200',
            'teks_arab' => 'required|string',
            'transliterasi' => 'required|string',
            'terjemahan' => 'required|string',
            'kategori' => 'required|string|max:100',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $doa->update([
            'judul' => $request->judul,
            'teks_arab' => $request->teks_arab,
            'transliterasi' => $request->transliterasi,
            'terjemahan' => $request->terjemahan,
            'kategori' => $request->kategori,
            'urutan' => $request->urutan,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('admin.konten.doa.index')->with('success', 'Doa berhasil diperbarui.');
    }

    public function doaDestroy($id)
    {
        $doa = Doa::findOrFail($id);
        $doa->delete();

        return redirect()->route('admin.konten.doa.index')->with('success', 'Doa berhasil dihapus.');
    }

    // ==========================================
    // HADIST MANAGEMENT
    // ==========================================
    public function hadistIndex(Request $request)
    {
        $query = Hadist::latest();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('teks_arab', 'like', "%{$search}%")
                  ->orWhere('terjemahan', 'like', "%{$search}%")
                  ->orWhere('sumber_kitab', 'like', "%{$search}%")
                  ->orWhere('perawi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $hadists = $query->paginate(15)->withQueryString();

        return view('admin.konten.hadist.index', compact('hadists'));
    }

    public function hadistStore(Request $request)
    {
        $request->validate([
            'teks_arab' => 'required|string',
            'terjemahan' => 'required|string',
            'sumber_kitab' => 'required|string|max:100',
            'perawi' => 'nullable|string|max:200',
            'kategori' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        Hadist::create([
            'teks_arab' => $request->teks_arab,
            'terjemahan' => $request->terjemahan,
            'sumber_kitab' => $request->sumber_kitab,
            'perawi' => $request->perawi,
            'kategori' => $request->kategori,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.konten.hadist.index')->with('success', 'Hadist berhasil ditambahkan.');
    }

    public function hadistUpdate(Request $request, $id)
    {
        $hadist = Hadist::findOrFail($id);

        $request->validate([
            'teks_arab' => 'required|string',
            'terjemahan' => 'required|string',
            'sumber_kitab' => 'required|string|max:100',
            'perawi' => 'nullable|string|max:200',
            'kategori' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $hadist->update([
            'teks_arab' => $request->teks_arab,
            'terjemahan' => $request->terjemahan,
            'sumber_kitab' => $request->sumber_kitab,
            'perawi' => $request->perawi,
            'kategori' => $request->kategori,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('admin.konten.hadist.index')->with('success', 'Hadist berhasil diperbarui.');
    }

    public function hadistDestroy($id)
    {
        $hadist = Hadist::findOrFail($id);
        $hadist->delete();

        return redirect()->route('admin.konten.hadist.index')->with('success', 'Hadist berhasil dihapus.');
    }

    // ==========================================
    // CERITA KISAH MANAGEMENT
    // ==========================================
    public function index(Request $request)
    {
        $query = CeritaKisah::with(['levelTarget', 'admin'])->latest();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($request->has('kategori') && $request->kategori !== '') {
            $query->where('kategori', $request->kategori);
        }

        $ceritas = $query->paginate(10)->withQueryString();

        return view('admin.konten.cerita.index', compact('ceritas'));
    }

    public function create()
    {
        $levels = Level::orderBy('urutan')->get();
        return view('admin.konten.cerita.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:kisah_nabi,kisah_sahabat,islami_lainnya',
            'level_target_id' => 'nullable|exists:levels,id',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('stories', 'public');
            $thumbnailPath = '/storage/' . $thumbnailPath;
        }

        // Clean HTML editor input
        $cleanContent = class_exists(Purifier::class) ? Purifier::clean($request->konten) : $request->konten;

        CeritaKisah::create([
            'admin_id' => auth()->guard('admin')->id(),
            'judul' => $request->judul,
            'konten' => $cleanContent,
            'kategori' => $request->kategori,
            'level_target_id' => $request->level_target_id,
            'status' => $request->status,
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()->route('admin.konten.cerita.index')->with('success', 'Cerita Kisah berhasil disimpan.');
    }

    public function edit($id)
    {
        $cerita = CeritaKisah::findOrFail($id);
        $levels = Level::orderBy('urutan')->get();
        return view('admin.konten.cerita.edit', compact('cerita', 'levels'));
    }

    public function update(Request $request, $id)
    {
        $cerita = CeritaKisah::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:kisah_nabi,kisah_sahabat,islami_lainnya',
            'level_target_id' => 'nullable|exists:levels,id',
            'status' => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $updateData = [
            'judul' => $request->judul,
            'konten' => class_exists(Purifier::class) ? Purifier::clean($request->konten) : $request->konten,
            'kategori' => $request->kategori,
            'level_target_id' => $request->level_target_id,
            'status' => $request->status,
        ];

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($cerita->thumbnail) {
                $oldPath = str_replace('/storage/', 'public/', $cerita->thumbnail);
                Storage::delete($oldPath);
            }

            $thumbnailPath = $request->file('thumbnail')->store('stories', 'public');
            $updateData['thumbnail'] = '/storage/' . $thumbnailPath;
        }

        $cerita->update($updateData);

        return redirect()->route('admin.konten.cerita.index')->with('success', 'Cerita Kisah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $cerita = CeritaKisah::findOrFail($id);

        if ($cerita->thumbnail) {
            $oldPath = str_replace('/storage/', 'public/', $cerita->thumbnail);
            Storage::delete($oldPath);
        }

        $cerita->delete();

        return redirect()->route('admin.konten.cerita.index')->with('success', 'Cerita Kisah berhasil dihapus.');
    }

    // ==========================================
    // PANDUAN PRAKTIK MANAGEMENT
    // ==========================================
    public function panduanIndex(Request $request)
    {
        $query = PanduanPraktik::with(['levelTarget', 'admin', 'langkahs'])->latest();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        $panduans = $query->paginate(10)->withQueryString();

        return view('admin.konten.panduan.index', compact('panduans'));
    }

    public function panduanCreate()
    {
        $levels = Level::orderBy('urutan')->get();
        return view('admin.konten.panduan.create', compact('levels'));
    }

    public function panduanStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_praktik' => 'required|string|max:100', // e.g. Wudhu, Shalat
            'level_target_id' => 'nullable|exists:levels,id',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('guides', 'public');
            $coverPath = '/storage/' . $coverPath;
        }

        $panduan = PanduanPraktik::create([
            'admin_id' => auth()->guard('admin')->id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis_praktik' => $request->jenis_praktik,
            'level_target_id' => $request->level_target_id,
            'status' => $request->status,
            'cover_image' => $coverPath,
        ]);

        return redirect()->route('admin.konten.panduan.show', $panduan->id)->with('success', 'Panduan berhasil disimpan. Silakan tambahkan langkah-langkah di bawah ini.');
    }

    public function show($id)
    {
        $panduan = PanduanPraktik::with(['langkahs', 'levelTarget'])->findOrFail($id);
        return view('admin.konten.panduan.show', compact('panduan'));
    }

    public function panduanEdit($id)
    {
        $panduan = PanduanPraktik::findOrFail($id);
        $levels = Level::orderBy('urutan')->get();
        return view('admin.konten.panduan.edit', compact('panduan', 'levels'));
    }

    public function panduanUpdate(Request $request, $id)
    {
        $panduan = PanduanPraktik::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis_praktik' => 'required|string|max:100',
            'level_target_id' => 'nullable|exists:levels,id',
            'status' => 'required|in:draft,published',
            'cover_image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        $updateData = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'jenis_praktik' => $request->jenis_praktik,
            'level_target_id' => $request->level_target_id,
            'status' => $request->status,
        ];

        if ($request->hasFile('cover_image')) {
            if ($panduan->cover_image) {
                $oldPath = str_replace('/storage/', 'public/', $panduan->cover_image);
                Storage::delete($oldPath);
            }

            $coverPath = $request->file('cover_image')->store('guides', 'public');
            $updateData['cover_image'] = '/storage/' . $coverPath;
        }

        $panduan->update($updateData);

        return redirect()->route('admin.konten.panduan.show', $panduan->id)->with('success', 'Metadata panduan berhasil diperbarui.');
    }

    public function panduanDestroy($id)
    {
        $panduan = PanduanPraktik::with('langkahs')->findOrFail($id);

        // Delete cover image
        if ($panduan->cover_image) {
            $oldPath = str_replace('/storage/', 'public/', $panduan->cover_image);
            Storage::delete($oldPath);
        }

        // Delete langkah images
        foreach ($panduan->langkahs as $langkah) {
            if ($langkah->gambar) {
                $oldLangkahPath = str_replace('/storage/', 'public/', $langkah->gambar);
                Storage::delete($oldLangkahPath);
            }
        }

        $panduan->delete(); // Cascades deletes to langkahs due to DB cascade

        return redirect()->route('admin.konten.panduan.index')->with('success', 'Panduan Praktik beserta langkahnya berhasil dihapus.');
    }

    // ==========================================
    // LANGKAH PANDUAN CRUD
    // ==========================================
    public function langkahStore(Request $request, $panduanId)
    {
        $panduan = PanduanPraktik::findOrFail($panduanId);

        $request->validate([
            'nomor_urut' => 'required|integer|min:1',
            'judul_langkah' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        // Check if unique constraint is violated
        $exists = LangkahPanduan::where('panduan_praktik_id', $panduanId)
            ->where('nomor_urut', $request->nomor_urut)
            ->exists();

        if ($exists) {
            return back()->withErrors(['nomor_urut' => 'Nomor urut langkah sudah digunakan. Silakan gunakan nomor urut yang berbeda.'])->withInput();
        }

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('steps', 'public');
            $gambarPath = '/storage/' . $gambarPath;
        }

        LangkahPanduan::create([
            'panduan_praktik_id' => $panduanId,
            'nomor_urut' => $request->nomor_urut,
            'judul_langkah' => $request->judul_langkah,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.konten.panduan.show', $panduanId)->with('success', 'Langkah panduan berhasil ditambahkan.');
    }

    public function langkahUpdate(Request $request, $id)
    {
        $langkah = LangkahPanduan::findOrFail($id);

        $request->validate([
            'nomor_urut' => 'required|integer|min:1',
            'judul_langkah' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        // Check unique constraint if nomor_urut changed
        if ($langkah->nomor_urut != $request->nomor_urut) {
            $exists = LangkahPanduan::where('panduan_praktik_id', $langkah->panduan_praktik_id)
                ->where('nomor_urut', $request->nomor_urut)
                ->exists();

            if ($exists) {
                return back()->withErrors(['nomor_urut' => 'Nomor urut langkah sudah digunakan. Silakan gunakan nomor urut yang berbeda.'])->withInput();
            }
        }

        $updateData = [
            'nomor_urut' => $request->nomor_urut,
            'judul_langkah' => $request->judul_langkah,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('gambar')) {
            if ($langkah->gambar) {
                $oldPath = str_replace('/storage/', 'public/', $langkah->gambar);
                Storage::delete($oldPath);
            }

            $gambarPath = $request->file('gambar')->store('steps', 'public');
            $updateData['gambar'] = '/storage/' . $gambarPath;
        }

        $langkah->update($updateData);

        return redirect()->route('admin.konten.panduan.show', $langkah->panduan_praktik_id)->with('success', 'Langkah panduan berhasil diperbarui.');
    }

    public function langkahDestroy($id)
    {
        $langkah = LangkahPanduan::findOrFail($id);
        $panduanId = $langkah->panduan_praktik_id;

        if ($langkah->gambar) {
            $oldPath = str_replace('/storage/', 'public/', $langkah->gambar);
            Storage::delete($oldPath);
        }

        $langkah->delete();

        return redirect()->route('admin.konten.panduan.show', $panduanId)->with('success', 'Langkah panduan berhasil dihapus.');
    }
}
