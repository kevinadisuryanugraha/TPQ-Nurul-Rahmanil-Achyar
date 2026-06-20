<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Hadist;
use Illuminate\Http\Request;

class HadistController extends Controller
{
    public function index(Request $request)
    {
        $query = Hadist::where('is_active', true)->latest();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('terjemahan', 'like', "%{$search}%")
                  ->orWhere('sumber_kitab', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $hadists = $query->get();

        return view('murid.hadist.index', compact('hadists'));
    }
}
