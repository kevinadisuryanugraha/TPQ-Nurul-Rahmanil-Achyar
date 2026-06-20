<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use App\Models\Absensi;
use App\Models\PenilaianBaca;
use App\Models\PenilaianHafalan;
use App\Models\PenilaianTulis;
use App\Models\PenilaianPraktik;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MuridExport;
use App\Exports\KelasRecapExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $levels = Level::orderBy('urutan')->get();
        return view('admin.laporan.index', compact('levels'));
    }

    public function murid(Request $request)
    {
        $query = User::with('currentLevel')->latest();

        if ($request->has('level_id') && $request->level_id !== '') {
            $query->where('current_level_id', $request->level_id);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(20)->withQueryString();
        $levels = Level::orderBy('urutan')->get();

        return view('admin.laporan.murid', compact('students', 'levels'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $student = User::with('currentLevel')->findOrFail($request->user_id);

        // Fetch latest assessment entries
        $latestBaca = PenilaianBaca::where('user_id', $student->id)->latest()->first();
        $latestHafalan = PenilaianHafalan::where('user_id', $student->id)->latest()->first();
        $latestTulis = PenilaianTulis::where('user_id', $student->id)->latest()->first();
        $latestPraktik = PenilaianPraktik::with('komponenChecklist')->where('user_id', $student->id)->latest()->first();

        // Calculate attendance statistics (all time or current academic year)
        $attendanceRecords = Absensi::where('user_id', $student->id)->get();
        $attendanceStats = [
            'hadir' => $attendanceRecords->where('status', 'hadir')->count(),
            'izin' => $attendanceRecords->where('status', 'izin')->count(),
            'sakit' => $attendanceRecords->where('status', 'sakit')->count(),
            'alpha' => $attendanceRecords->where('status', 'alpha')->count(),
            'total' => $attendanceRecords->count(),
        ];

        // System configuration composer variables are available globally
        $pdf = Pdf::loadView('admin.laporan.pdf_rapor', compact(
            'student',
            'latestBaca',
            'latestHafalan',
            'latestTulis',
            'latestPraktik',
            'attendanceStats'
        ));

        return $pdf->download('Rapor_Santri_' . str_replace(' ', '_', $student->nama_panggilan) . '.pdf');
    }

    public function exportExcelMurid(Request $request)
    {
        $levelId = $request->query('level_id');
        $fileName = 'Data_Santri_' . ($levelId ? 'Kelas_' . $levelId : 'Semua') . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new MuridExport($levelId), $fileName);
    }

    public function exportExcelKelas(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
        ]);

        $level = Level::findOrFail($request->level_id);
        $fileName = 'Rekap_Kelas_' . str_replace(' ', '_', $level->nama) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new KelasRecapExport($request->level_id), $fileName);
    }
}
