<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $student = auth()->user();

        // Get monthly stats
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $attendanceRecords = Absensi::where('user_id', $student->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal', 'desc')
            ->get();

        $absensiStats = [
            'hadir' => $attendanceRecords->where('status', 'hadir')->count(),
            'izin' => $attendanceRecords->where('status', 'izin')->count(),
            'sakit' => $attendanceRecords->where('status', 'sakit')->count(),
            'alpha' => $attendanceRecords->where('status', 'alpha')->count(),
            'total' => $attendanceRecords->count(),
        ];

        // All historical attendance logs
        $history = Absensi::where('user_id', $student->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(30);

        return view('murid.absensi.index', compact('student', 'attendanceRecords', 'absensiStats', 'history'));
    }
}
