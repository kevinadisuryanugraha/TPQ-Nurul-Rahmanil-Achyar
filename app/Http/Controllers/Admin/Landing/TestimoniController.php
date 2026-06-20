<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TestimoniController extends Controller
{
    /**
     * Display a listing of the testimonials.
     */
    public function index()
    {
        $testimonials = Testimoni::orderBy('urutan')->orderBy('id', 'desc')->get();
        return view('admin.landing.testimoni.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        return view('admin.landing.testimoni.create');
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'isi' => 'required|string|max:500',
            'rating' => 'required|integer|between:1,5',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'testi_' . time() . '_' . uniqid() . '.webp';
            $path = 'uploads/testimonials/' . $filename;

            // Ensure directory exists
            if (!Storage::disk('public')->exists('uploads/testimonials')) {
                Storage::disk('public')->makeDirectory('uploads/testimonials');
            }

            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->decode($file->getRealPath());

                // Scale to a standard square avatar size (e.g. 200x200)
                $image->cover(200, 200);

                $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                Storage::disk('public')->put($path, (string) $encoded);
                $fotoPath = '/storage/' . $path;
            } catch (\Exception $e) {
                // Fallback to standard storage
                $fotoPath = '/storage/' . $file->store('uploads/testimonials', 'public');
            }
        }

        Testimoni::create([
            'nama' => $request->nama,
            'role' => $request->role,
            'foto' => $fotoPath,
            'isi' => $request->isi,
            'rating' => $request->rating,
            'urutan' => $request->urutan ?? 99,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.landing.testimoni.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit($id)
    {
        $testimonial = Testimoni::findOrFail($id);
        return view('admin.landing.testimoni.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(Request $request, $id)
    {
        $testimonial = Testimoni::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
            'isi' => 'required|string|max:500',
            'rating' => 'required|integer|between:1,5',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $fotoPath = $testimonial->foto;
        if ($request->hasFile('foto')) {
            // Delete old file
            if ($testimonial->foto) {
                $oldPath = str_replace('/storage/', '', $testimonial->foto);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('foto');
            $filename = 'testi_' . time() . '_' . uniqid() . '.webp';
            $path = 'uploads/testimonials/' . $filename;

            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->decode($file->getRealPath());
                $image->cover(200, 200);

                $encoded = $image->encodeUsingFileExtension('webp', quality: 80);
                Storage::disk('public')->put($path, (string) $encoded);
                $fotoPath = '/storage/' . $path;
            } catch (\Exception $e) {
                // Fallback to standard storage
                $fotoPath = '/storage/' . $file->store('uploads/testimonials', 'public');
            }
        }

        $testimonial->update([
            'nama' => $request->nama,
            'role' => $request->role,
            'foto' => $fotoPath,
            'isi' => $request->isi,
            'rating' => $request->rating,
            'urutan' => $request->urutan ?? 99,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.landing.testimoni.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy($id)
    {
        $testimonial = Testimoni::findOrFail($id);

        if ($testimonial->foto) {
            $filePath = str_replace('/storage/', '', $testimonial->foto);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $testimonial->delete();

        return redirect()->route('admin.landing.testimoni.index')->with('success', 'Testimoni berhasil dihapus.');
    }
}
