<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GaleriController extends Controller
{
    /**
     * Display a listing of the galleries.
     */
    public function index()
    {
        $galleries = Galeri::orderBy('urutan')->orderBy('id', 'desc')->get();
        return view('admin.landing.galeri.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery.
     */
    public function create()
    {
        return view('admin.landing.galeri.create');
    }

    /**
     * Store a newly created gallery in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'nullable|string|max:150',
            'gambar' => 'required|image|mimes:jpeg,png,webp,jpg|max:2048',
            'kategori' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = 'gallery_' . time() . '_' . uniqid() . '.webp';
            $path = 'uploads/gallery/' . $filename;

            // Ensure directory exists
            if (!Storage::disk('public')->exists('uploads/gallery')) {
                Storage::disk('public')->makeDirectory('uploads/gallery');
            }

            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->decode($file->getRealPath());

                // Scale down if width is greater than 1200px
                if ($image->width() > 1200) {
                    $image->scale(width: 1200);
                }

                // Intervention Image v4: encodeUsingFileExtension
                $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                Storage::disk('public')->put($path, (string) $encoded);
                $gambarPath = '/storage/' . $path;
            } catch (\Exception $e) {
                // Fallback to standard storage
                $gambarPath = '/storage/' . $file->store('uploads/gallery', 'public');
            }
        }

        Galeri::create([
            'judul' => $request->judul,
            'gambar' => $gambarPath,
            'kategori' => $request->kategori ?: 'Umum',
            'urutan' => $request->urutan ?? 99,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.landing.galeri.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified gallery.
     */
    public function edit($id)
    {
        $gallery = Galeri::findOrFail($id);
        return view('admin.landing.galeri.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery in storage.
     */
    public function update(Request $request, $id)
    {
        $gallery = Galeri::findOrFail($id);

        $request->validate([
            'judul' => 'nullable|string|max:150',
            'gambar' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'kategori' => 'nullable|string|max:100',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $gambarPath = $gallery->gambar;
        if ($request->hasFile('gambar')) {
            // Delete old file if exists
            $oldPath = str_replace('/storage/', '', $gallery->gambar);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('gambar');
            $filename = 'gallery_' . time() . '_' . uniqid() . '.webp';
            $path = 'uploads/gallery/' . $filename;

            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->decode($file->getRealPath());

                if ($image->width() > 1200) {
                    $image->scale(width: 1200);
                }

                // Intervention Image v4: encodeUsingFileExtension
                $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                Storage::disk('public')->put($path, (string) $encoded);
                $gambarPath = '/storage/' . $path;
            } catch (\Exception $e) {
                // Fallback to standard storage
                $gambarPath = '/storage/' . $file->store('uploads/gallery', 'public');
            }
        }

        $gallery->update([
            'judul' => $request->judul,
            'gambar' => $gambarPath,
            'kategori' => $request->kategori ?: 'Umum',
            'urutan' => $request->urutan ?? 99,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.landing.galeri.index')->with('success', 'Foto galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified gallery from storage.
     */
    public function destroy($id)
    {
        $gallery = Galeri::findOrFail($id);

        // Delete file from disk
        $filePath = str_replace('/storage/', '', $gallery->gambar);
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $gallery->delete();

        return redirect()->route('admin.landing.galeri.index')->with('success', 'Foto galeri berhasil dihapus.');
    }
}
