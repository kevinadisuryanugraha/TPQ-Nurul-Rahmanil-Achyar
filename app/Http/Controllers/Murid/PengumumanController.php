<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        $pengumumans = Pengumuman::with('admin')
            ->where('status', 'published')
            ->where('tanggal_mulai', '<=', now())
            ->where(function($q) {
                $q->whereNull('tanggal_berakhir')->orWhere('tanggal_berakhir', '>=', now());
            })
            ->where(function($q) use ($student) {
                $q->where('target_semua', true)
                  ->orWhere('level_target_id', $student->current_level_id);
            })
            ->latest()
            ->get();

        return view('murid.pengumuman.index', compact('pengumumans'));
    }
}
