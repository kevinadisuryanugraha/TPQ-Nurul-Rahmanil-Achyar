<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\AsmaulHusna;
use Illuminate\Http\Request;

class AsmaulHusnaController extends Controller
{
    public function index(Request $request)
    {
        $query = AsmaulHusna::where('is_active', true)->orderBy('urutan');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('latin', 'like', "%{$search}%")
                  ->orWhere('arti', 'like', "%{$search}%")
                  ->orWhere('arab', 'like', "%{$search}%");
            });
        }

        $names = $query->get();

        return view('murid.asmaul-husna.index', compact('names'));
    }
}
