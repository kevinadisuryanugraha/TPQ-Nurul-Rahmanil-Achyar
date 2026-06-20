<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\PanduanPraktik;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user();
        $userUrutan = $student->currentLevel->urutan;

        $query = PanduanPraktik::with('langkahs')
            ->where('status', 'published')
            ->where(function($q) use ($userUrutan) {
                $q->whereNull('level_target_id')
                  ->orWhereHas('levelTarget', function($sub) use ($userUrutan) {
                      $sub->where('urutan', '<=', $userUrutan);
                  });
            });

        if ($request->has('search') && $request->search !== '') {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        $panduans = $query->latest()->get();

        return view('murid.panduan.index', compact('panduans'));
    }

    public function show($id)
    {
        $student = auth()->user();
        $userUrutan = $student->currentLevel->urutan;

        $panduan = PanduanPraktik::with(['langkahs' => function($q) {
                $q->orderBy('nomor_urut', 'asc');
            }])
            ->where('status', 'published')
            ->where(function($q) use ($userUrutan) {
                $q->whereNull('level_target_id')
                  ->orWhereHas('levelTarget', function($sub) use ($userUrutan) {
                      $sub->where('urutan', '<=', $userUrutan);
                  });
            })
            ->findOrFail($id);

        return view('murid.panduan.show', compact('panduan'));
    }
}
