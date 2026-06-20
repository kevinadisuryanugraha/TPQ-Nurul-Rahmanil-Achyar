<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\PenilaianBaca;
use App\Models\PenilaianHafalan;
use App\Models\PenilaianTulis;
use App\Models\PenilaianPraktik;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        // Latest scores
        $latestBaca = PenilaianBaca::where('user_id', $student->id)->latest()->first();
        $latestHafalan = PenilaianHafalan::where('user_id', $student->id)->latest()->first();
        $latestTulis = PenilaianTulis::where('user_id', $student->id)->latest()->first();
        $latestPraktik = PenilaianPraktik::where('user_id', $student->id)->latest()->first();

        // Attendance stats this month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $attendanceRecords = Absensi::where('user_id', $student->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->get();

        $attendanceStats = [
            'hadir' => $attendanceRecords->where('status', 'hadir')->count(),
            'izin' => $attendanceRecords->where('status', 'izin')->count(),
            'sakit' => $attendanceRecords->where('status', 'sakit')->count(),
            'alpha' => $attendanceRecords->where('status', 'alpha')->count(),
            'total' => $attendanceRecords->count(),
        ];

        // Active Announcements
        $announcements = Pengumuman::where('status', 'published')
            ->where('tanggal_mulai', '<=', now())
            ->where(function($q) {
                $q->whereNull('tanggal_berakhir')->orWhere('tanggal_berakhir', '>=', now());
            })
            ->where(function($q) use ($student) {
                $q->where('target_semua', true)
                  ->orWhere('level_target_id', $student->current_level_id);
            })
            ->latest()
            ->take(3)
            ->get();

        return view('murid.dashboard', compact(
            'student', 
            'latestBaca', 
            'latestHafalan', 
            'latestTulis', 
            'latestPraktik', 
            'attendanceStats', 
            'announcements'
        ));
    }
}
