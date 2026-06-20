<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\CeritaKisah;
use Illuminate\Http\Request;

class CeritaController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user();
        $userUrutan = $student->currentLevel->urutan;

        $query = CeritaKisah::where('status', 'published')
            ->where(function($q) use ($userUrutan) {
                $q->whereNull('level_target_id')
                  ->orWhereHas('levelTarget', function($sub) use ($userUrutan) {
                      $sub->where('urutan', '<=', $userUrutan);
                  });
            });

        if ($request->has('search') && $request->search !== '') {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        $ceritas = $query->latest()->get();

        return view('murid.cerita.index', compact('ceritas'));
    }

    public function show($id)
    {
        $student = auth()->user();
        $userUrutan = $student->currentLevel->urutan;

        // Fetch story and ensure it is published and accessible
        $cerita = CeritaKisah::where('status', 'published')
            ->where(function($q) use ($userUrutan) {
                $q->whereNull('level_target_id')
                  ->orWhereHas('levelTarget', function($sub) use ($userUrutan) {
                      $sub->where('urutan', '<=', $userUrutan);
                  });
            })
            ->findOrFail($id);

        return view('murid.cerita.show', compact('cerita'));
    }
}
