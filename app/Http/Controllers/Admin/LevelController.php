<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use App\Models\UserLevelHistory;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        // Lists all levels and displays counts of students in each level
        $levels = Level::withCount(['users' => function($q) {
            $q->where('is_active', true);
        }])->orderBy('urutan')->get();

        return view('admin.murid.level', compact('levels'));
    }

    public function naikLevel(Request $request, $id)
    {
        $student = User::findOrFail($id);
        $levels = Level::orderBy('urutan')->get();
        $currentUrutan = $student->currentLevel->urutan;

        $nextLevel = $levels->where('urutan', $currentUrutan + 1)->first();

        if (!$nextLevel) {
            return back()->with('error', 'Santri sudah berada di tingkat level tertinggi (' . $student->currentLevel->nama . ').');
        }

        $levelSebelumnyaId = $student->current_level_id;

        // Perform update
        $student->update([
            'current_level_id' => $nextLevel->id,
        ]);

        // Create log entry
        UserLevelHistory::create([
            'user_id' => $student->id,
            'level_id' => $nextLevel->id,
            'level_sebelumnya_id' => $levelSebelumnyaId,
            'admin_id' => auth()->guard('admin')->id(),
            'tipe' => 'naik',
            'catatan' => $request->catatan ?: 'Dinaikkan tingkat oleh ' . auth()->guard('admin')->user()->nama,
        ]);

        // Send WhatsApp level up notification
        \App\Services\WhatsAppService::sendLevelUpNotification($student, $nextLevel->nama);

        return redirect()->route('admin.murid.show', $student->id)
            ->with('success', 'Tingkat level ' . $student->nama_panggilan . ' berhasil dinaikkan ke ' . $nextLevel->nama . '.');
    }

    public function turunLevel(Request $request, $id)
    {
        $student = User::findOrFail($id);
        $levels = Level::orderBy('urutan')->get();
        $currentUrutan = $student->currentLevel->urutan;

        $prevLevel = $levels->where('urutan', $currentUrutan - 1)->first();

        if (!$prevLevel) {
            return back()->with('error', 'Santri sudah berada di tingkat level terendah (' . $student->currentLevel->nama . ').');
        }

        $levelSebelumnyaId = $student->current_level_id;

        // Perform update
        $student->update([
            'current_level_id' => $prevLevel->id,
        ]);

        // Create log entry
        UserLevelHistory::create([
            'user_id' => $student->id,
            'level_id' => $prevLevel->id,
            'level_sebelumnya_id' => $levelSebelumnyaId,
            'admin_id' => auth()->guard('admin')->id(),
            'tipe' => 'turun',
            'catatan' => $request->catatan ?: 'Diturunkan tingkat oleh ' . auth()->guard('admin')->user()->nama,
        ]);

        // Send WhatsApp level down notification
        \App\Services\WhatsAppService::sendLevelDownNotification($student, $prevLevel->nama);

        return redirect()->route('admin.murid.show', $student->id)
            ->with('success', 'Tingkat level ' . $student->nama_panggilan . ' berhasil diturunkan ke ' . $prevLevel->nama . '.');
    }
}
