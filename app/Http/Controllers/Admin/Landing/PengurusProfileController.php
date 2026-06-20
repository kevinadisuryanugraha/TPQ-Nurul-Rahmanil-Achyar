<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\PengurusProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PengurusProfileController extends Controller
{
    /**
     * Display a listing of the organizational profiles.
     */
    public function index()
    {
        $pengurusList = PengurusProfile::orderBy('urutan')->orderBy('id', 'desc')->get();
        return view('admin.landing.pengurus.index', compact('pengurusList'));
    }

    /**
     * Show the form for creating a new profile.
     */
    public function create()
    {
        return view('admin.landing.pengurus.create');
    }

    /**
     * Store a newly created profile in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'pengurus_' . time() . '_' . uniqid() . '.webp';
            $path = 'uploads/pengurus/' . $filename;

            // Ensure directory exists
            if (!Storage::disk('public')->exists('uploads/pengurus')) {
                Storage::disk('public')->makeDirectory('uploads/pengurus');
            }

            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->decode($file->getRealPath());

                // Scale to square format
                $image->cover(300, 300);

                $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                Storage::disk('public')->put($path, (string) $encoded);
                $fotoPath = '/storage/' . $path;
            } catch (\Exception $e) {
                // Fallback to standard storage
                $fotoPath = '/storage/' . $file->store('uploads/pengurus', 'public');
            }
        }

        PengurusProfile::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'foto' => $fotoPath,
            'urutan' => $request->urutan ?? 99,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.landing.pengurus.index')->with('success', 'Profil pengurus berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit($id)
    {
        $pengurus = PengurusProfile::findOrFail($id);
        return view('admin.landing.pengurus.edit', compact('pengurus'));
    }

    /**
     * Update the specified profile in storage.
     */
    public function update(Request $request, $id)
    {
        $pengurus = PengurusProfile::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $fotoPath = $pengurus->foto;
        if ($request->hasFile('foto')) {
            // Delete old file
            if ($pengurus->foto) {
                $oldPath = str_replace('/storage/', '', $pengurus->foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $filename = 'pengurus_' . time() . '_' . uniqid() . '.webp';
            $path = 'uploads/pengurus/' . $filename;

            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->decode($file->getRealPath());
                $image->cover(300, 300);

                $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                Storage::disk('public')->put($path, (string) $encoded);
                $fotoPath = '/storage/' . $path;
            } catch (\Exception $e) {
                // Fallback to standard storage
                $fotoPath = '/storage/' . $file->store('uploads/pengurus', 'public');
            }
        }

        $pengurus->update([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'foto' => $fotoPath,
            'urutan' => $request->urutan ?? 99,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.landing.pengurus.index')->with('success', 'Profil pengurus berhasil diperbarui.');
    }

    /**
     * Remove the specified profile from storage.
     */
    public function destroy($id)
    {
        $pengurus = PengurusProfile::findOrFail($id);

        if ($pengurus->foto) {
            $filePath = str_replace('/storage/', '', $pengurus->foto);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $pengurus->delete();

        return redirect()->route('admin.landing.pengurus.index')->with('success', 'Profil pengurus berhasil dihapus.');
    }
}
