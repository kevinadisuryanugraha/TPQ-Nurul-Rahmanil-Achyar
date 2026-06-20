<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengumuman::with(['levelTarget', 'admin'])->latest();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%");
        }

        $pengumumans = $query->paginate(15)->withQueryString();

        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    public function create()
    {
        $levels = Level::orderBy('urutan')->get();
        return view('admin.pengumuman.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_semua' => 'required|boolean',
            'level_target_id' => 'nullable|required_if:target_semua,0|exists:levels,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:draft,published',
        ]);

        Pengumuman::create([
            'admin_id' => auth()->guard('admin')->id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'target_semua' => (bool)$request->target_semua,
            'level_target_id' => $request->target_semua ? null : $request->level_target_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman baru berhasil dibuat.');
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $levels = Level::orderBy('urutan')->get();
        return view('admin.pengumuman.edit', compact('pengumuman', 'levels'));
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'target_semua' => 'required|boolean',
            'level_target_id' => 'nullable|required_if:target_semua,0|exists:levels,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:draft,published',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'target_semua' => (bool)$request->target_semua,
            'level_target_id' => $request->target_semua ? null : $request->level_target_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
