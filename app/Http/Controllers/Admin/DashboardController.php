<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Level;
use App\Models\Pengumuman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Total active students
        $totalSantri = User::where('is_active', true)->count();

        // Absensi Hari Ini
        $presentToday = Absensi::where('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        $absentNotInputToday = User::where('is_active', true)
            ->whereDoesntHave('absensis', function ($query) use ($today) {
                $query->where('tanggal', $today);
            })
            ->count();

        // Reminder Penilaian: Santri who haven't received any assessment in the last 7 days
        $sevenDaysAgo = Carbon::today()->subDays(7);
        
        $reminderSantri = User::where('is_active', true)
            ->where(function ($query) use ($sevenDaysAgo) {
                $query->whereDoesntHave('penilaianBacas', function ($q) use ($sevenDaysAgo) {
                    $q->where('tanggal', '>=', $sevenDaysAgo);
                })
                ->whereDoesntHave('penilaianHafalans', function ($q) use ($sevenDaysAgo) {
                    $q->where('tanggal', '>=', $sevenDaysAgo);
                })
                ->whereDoesntHave('penilaianTulises', function ($q) use ($sevenDaysAgo) {
                    $q->where('tanggal', '>=', $sevenDaysAgo);
                })
                ->whereDoesntHave('penilaianPraktiks', function ($q) use ($sevenDaysAgo) {
                    $q->where('tanggal', '>=', $sevenDaysAgo);
                });
            })
            ->take(5)
            ->get();

        // Latest announcement
        $latestAnnouncement = Pengumuman::where('status', 'published')
            ->where('tanggal_mulai', '<=', $today)
            ->where(function($q) use ($today) {
                $q->where('tanggal_berakhir', '>=', $today)
                  ->orWhereNull('tanggal_berakhir');
            })
            ->latest()
            ->first();

        // Students per level breakdown
        $levels = Level::withCount(['users' => function ($q) {
            $q->where('is_active', true);
        }])->orderBy('urutan', 'asc')->get();

        return view('admin.dashboard', compact(
            'totalSantri',
            'presentToday',
            'absentNotInputToday',
            'reminderSantri',
            'latestAnnouncement',
            'levels'
        ));
    }
}
