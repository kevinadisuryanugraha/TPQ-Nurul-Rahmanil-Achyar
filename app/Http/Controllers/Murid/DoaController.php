<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Doa;
use Illuminate\Http\Request;

class DoaController extends Controller
{
    public function index(Request $request)
    {
        $query = Doa::where('is_active', true)->orderBy('kategori')->orderBy('urutan');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('terjemahan', 'like', "%{$search}%");
            });
        }

        if ($request->has('kategori') && $request->kategori !== '') {
            $query->where('kategori', $request->kategori);
        }

        $doas = $query->get();
        $categories = Doa::where('is_active', true)->select('kategori')->distinct()->pluck('kategori');

        return view('murid.doa.index', compact('doas', 'categories'));
    }
}
