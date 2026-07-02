<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Absensi::with(['user', 'admin'])->latest('tanggal')->latest('sesi');

        // Filter by Student
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter by Date
        if ($request->has('tanggal') && $request->tanggal !== '') {
            $query->where('tanggal', $request->tanggal);
        }

        // Filter by Session
        if ($request->has('sesi') && $request->sesi !== '') {
            $query->where('sesi', $request->sesi);
        }

        // Filter by Status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $records = $query->paginate(20)->withQueryString();
        $students = User::where('is_active', true)->orderBy('nama_lengkap')->get();

        return view('admin.absensi.index', compact('records', 'students'));
    }

    public function create()
    {
        return view('admin.absensi.create');
    }

    public function store(Request $request)
    {
        // Handled entirely by the Livewire component!
    }

    public function show($id)
    {
        // Not needed for MVP
    }

    public function edit($id)
    {
        $record = Absensi::findOrFail($id);
        return view('admin.absensi.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = Absensi::findOrFail($id);

        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'catatan' => 'nullable|string|max:255',
        ]);

        $oldStatus = $record->status;

        $record->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'admin_id' => auth()->guard('admin')->id(), // Record who updated it
        ]);

        $student = $record->user;
        if ($student) {
            // Check & award absensi badges
            \App\Services\GamificationService::checkAndAwardBadges($student, 'absensi');

            // Send WhatsApp if changed to alpha
            if ($request->status === 'alpha' && $oldStatus !== 'alpha') {
                \App\Services\WhatsAppService::sendAbsenceNotification($student, $record->tanggal, $record->sesi);
            }
        }

        return redirect()->route('admin.absensi.index')->with('success', 'Catatan absensi berhasil dikoreksi.');
    }

    public function destroy($id)
    {
        $record = Absensi::findOrFail($id);
        $record->delete();

        return redirect()->route('admin.absensi.index')->with('success', 'Catatan absensi berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        // Generates structural attendance rates per student
        $students = User::where('is_active', true)->with('currentLevel')->orderBy('nama_lengkap')->get();
        
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendanceData = [];

        foreach ($students as $student) {
            $records = Absensi::where('user_id', $student->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->get();

            $hadir = $records->where('status', 'hadir')->count();
            $izin = $records->where('status', 'izin')->count();
            $sakit = $records->where('status', 'sakit')->count();
            $alpha = $records->where('status', 'alpha')->count();
            $total = $records->count();

            $hadirPercent = $total > 0 ? round(($hadir / $total) * 100) : 0;

            $attendanceData[] = [
                'student' => $student,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'total' => $total,
                'hadir_percent' => $hadirPercent
            ];
        }

        return view('admin.absensi.rekap', compact('attendanceData', 'month', 'year'));
    }
}
