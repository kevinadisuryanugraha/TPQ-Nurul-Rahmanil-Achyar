<?php

namespace App\Livewire\Admin;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AbsensiInput extends Component
{
    public $tanggal;
    public $sesi;
    public $studentsList = [];
    public $attendance = []; // user_id => status
    public $catatan = [];    // user_id => notes
    public $appSessions = [];
    public $isEdit = false;

    public function mount()
    {
        $this->tanggal = Carbon::today()->format('Y-m-d');
        
        // Load sessions from global appSettings
        $settingsFile = 'settings.json';
        $settings = [
            'sesi' => ['Pagi', 'Sore', 'Malam']
        ];
        if (\Illuminate\Support\Facades\Storage::exists($settingsFile)) {
            try {
                $settings = json_decode(\Illuminate\Support\Facades\Storage::get($settingsFile), true) ?: $settings;
            } catch (\Exception $e) {}
        }
        $this->appSessions = $settings['sesi'];
        $this->sesi = $this->appSessions[0] ?? 'Pagi';

        $this->loadStudents();
    }

    public function updatedTanggal()
    {
        $this->loadStudents();
    }

    public function updatedSesi()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $activeStudents = User::where('is_active', true)->orderBy('nama_lengkap')->get();
        $this->studentsList = $activeStudents;

        // Check if attendance already exists for this date and session
        $existing = Absensi::where('tanggal', $this->tanggal)
            ->where('sesi', $this->sesi)
            ->get();

        if ($existing->isNotEmpty()) {
            $this->isEdit = true;
            $this->attendance = [];
            $this->catatan = [];
            
            foreach ($activeStudents as $student) {
                $record = $existing->where('user_id', $student->id)->first();
                if ($record) {
                    $this->attendance[$student->id] = $record->status;
                    $this->catatan[$student->id] = $record->catatan ?? '';
                } else {
                    $this->attendance[$student->id] = 'alpha'; // default if student added later
                    $this->catatan[$student->id] = '';
                }
            }
        } else {
            $this->isEdit = false;
            $this->attendance = [];
            $this->catatan = [];
            
            foreach ($activeStudents as $student) {
                $this->attendance[$student->id] = 'alpha'; // PRD requirement: default to Alpha
                $this->catatan[$student->id] = '';
            }
        }
    }

    public function save()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'sesi' => 'required|string',
        ]);

        foreach ($this->attendance as $studentId => $status) {
            $oldRecord = Absensi::where('user_id', $studentId)
                ->where('tanggal', $this->tanggal)
                ->where('sesi', $this->sesi)
                ->first();

            Absensi::updateOrCreate(
                [
                    'user_id' => $studentId,
                    'tanggal' => $this->tanggal,
                    'sesi' => $this->sesi,
                ],
                [
                    'admin_id' => auth()->guard('admin')->id(),
                    'status' => $status,
                    'catatan' => $this->catatan[$studentId] ?? null,
                ]
            );

            $student = User::find($studentId);
            if ($student) {
                // Check & award absensi badges
                \App\Services\GamificationService::checkAndAwardBadges($student, 'absensi');

                // Send WhatsApp if newly alpha
                if ($status === 'alpha' && (!$oldRecord || $oldRecord->status !== 'alpha')) {
                    \App\Services\WhatsAppService::sendAbsenceNotification($student, $this->tanggal, $this->sesi);
                }
            }
        }

        session()->flash('success', 'Data absensi tanggal ' . Carbon::parse($this->tanggal)->format('d M Y') . ' Sesi ' . $this->sesi . ' berhasil disimpan.');
        return redirect()->route('admin.absensi.index');
    }

    public function render()
    {
        return view('livewire.admin.absensi-input');
    }
}
