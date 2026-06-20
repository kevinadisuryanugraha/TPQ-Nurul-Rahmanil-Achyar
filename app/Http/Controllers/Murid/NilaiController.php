<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\PenilaianBaca;
use App\Models\PenilaianHafalan;
use App\Models\PenilaianTulis;
use App\Models\PenilaianPraktik;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        $bacas = PenilaianBaca::where('user_id', $student->id)->latest()->get();
        $hafalans = PenilaianHafalan::where('user_id', $student->id)->latest()->get();
        $tulises = PenilaianTulis::where('user_id', $student->id)->latest()->get();
        $praktiks = PenilaianPraktik::with('komponenChecklist')->where('user_id', $student->id)->latest()->get();

        return view('murid.nilai.index', compact('student', 'bacas', 'hafalans', 'tulises', 'praktiks'));
    }
}
