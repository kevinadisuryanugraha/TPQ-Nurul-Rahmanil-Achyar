<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\CeritaKisah;
use App\Models\PanduanPraktik;
use App\Models\UserLevelHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $adminCount = Admin::where('is_active', true)->count();
        $studentCount = User::where('is_active', true)->count();
        
        $publishedStories = CeritaKisah::where('status', 'published')->count();
        $publishedGuides = PanduanPraktik::where('status', 'published')->count();
        $contentCount = $publishedStories + $publishedGuides;

        // Fetch latest 10 system actions (from level promotion/demotion histories)
        $activities = UserLevelHistory::with(['user', 'level', 'levelSebelumnya', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        return view('superadmin.dashboard', compact('adminCount', 'studentCount', 'contentCount', 'activities'));
    }
}
