<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Surah;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    public function index(Request $request)
    {
        $query = Surah::orderBy('id', 'asc');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_latin', 'like', "%{$search}%")
                  ->orWhere('nama_indonesia', 'like', "%{$search}%");
            });
        }

        $surahs = $query->get();

        return view('murid.quran.index', compact('surahs'));
    }

    public function show($id)
    {
        $surah = Surah::with(['ayats' => function($q) {
            $q->orderBy('nomor_ayat', 'asc');
        }])->findOrFail($id);

        return view('murid.quran.show', compact('surah'));
    }
}
