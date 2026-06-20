<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doa;
use App\Models\Hadist;
use App\Models\PenilaianBaca;
use App\Models\PenilaianHafalan;
use App\Models\PenilaianPraktik;
use App\Models\PenilaianPraktikKomponen;
use App\Models\PenilaianTulis;
use App\Models\Surah;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenilaianController extends Controller
{
    public function index()
    {
        // Fetch last 10 overall evaluations of all types for the logs table
        $bacaLogs = PenilaianBaca::with(['user', 'admin'])->latest()->take(5)->get()->map(function($item) {
            $item->type_label = 'Bacaan';
            $item->desc = $item->jenis_bacaan . ' (Jilid/Juz: ' . $item->jilid_juz . ')';
            return $item;
        });

        $hafalanLogs = PenilaianHafalan::with(['user', 'admin'])->latest()->take(5)->get()->map(function($item) {
            $item->type_label = 'Hafalan';
            $item->desc = strtoupper($item->jenis_hafalan) . ': ' . $item->nama_item;
            return $item;
        });

        $tulisLogs = PenilaianTulis::with(['user', 'admin'])->latest()->take(5)->get()->map(function($item) {
            $item->type_label = 'Menulis';
            $item->desc = $item->materi . ' (Nilai: ' . $item->nilai . ', Grade: ' . $item->grade . ')';
            return $item;
        });

        $praktikLogs = PenilaianPraktik::with(['user', 'admin'])->latest()->take(5)->get()->map(function($item) {
            $item->type_label = 'Praktik';
            $item->desc = str_replace('_', ' ', strtoupper($item->jenis_praktik));
            return $item;
        });

        $allLogs = collect()
            ->concat($bacaLogs)
            ->concat($hafalanLogs)
            ->concat($tulisLogs)
            ->concat($praktikLogs)
            ->sortByDesc('created_at')
            ->take(10);

        return view('admin.penilaian.index', compact('allLogs'));
    }

    public function baca(Request $request)
    {
        $students = User::where('is_active', true)->orderBy('nama_lengkap')->get();
        $selectedUserId = $request->query('user_id');
        
        $history = [];
        if ($selectedUserId) {
            $history = PenilaianBaca::with('admin')
                ->where('user_id', $selectedUserId)
                ->latest()
                ->take(10)
                ->get();
        }

        return view('admin.penilaian.baca', compact('students', 'selectedUserId', 'history'));
    }

    public function storeBaca(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jenis_bacaan' => 'required|in:iqra,alquran,tilawah',
            'jilid_juz' => 'nullable|integer|min:1|max:30',
            'halaman_ayat' => 'nullable|integer|min:1|max:1000',
            'keterangan_posisi' => 'nullable|string|max:100',
            'kelancaran' => 'required|in:lancar,cukup,perlu_latihan',
            'catatan_tajwid' => 'nullable|string|max:255',
            'catatan_umum' => 'nullable|string|max:255',
        ]);

        PenilaianBaca::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->guard('admin')->id(),
            'tanggal' => $request->tanggal,
            'jenis_bacaan' => $request->jenis_bacaan,
            'jilid_juz' => $request->jilid_juz,
            'halaman_ayat' => $request->halaman_ayat,
            'keterangan_posisi' => $request->keterangan_posisi,
            'kelancaran' => $request->kelancaran,
            'catatan_tajwid' => $request->catatan_tajwid,
            'catatan_umum' => $request->catatan_umum,
        ]);

        return redirect()->route('admin.penilaian.baca', ['user_id' => $request->user_id])
            ->with('success', 'Penilaian bacaan santri berhasil disimpan.');
    }

    public function deleteBaca($id)
    {
        $log = PenilaianBaca::findOrFail($id);
        $userId = $log->user_id;
        $log->delete();

        return redirect()->route('admin.penilaian.baca', ['user_id' => $userId])
            ->with('success', 'Catatan penilaian bacaan berhasil dihapus.');
    }

    public function hafalan(Request $request)
    {
        $students = User::where('is_active', true)->orderBy('nama_lengkap')->get();
        $selectedUserId = $request->query('user_id');

        $surahs = Surah::orderBy('id')->get();
        $duas = Doa::where('is_active', true)->orderBy('judul')->get();
        $hadiths = Hadist::where('is_active', true)->get();

        $history = [];
        if ($selectedUserId) {
            $history = PenilaianHafalan::with('admin')
                ->where('user_id', $selectedUserId)
                ->latest()
                ->take(10)
                ->get();
        }

        return view('admin.penilaian.hafalan', compact('students', 'selectedUserId', 'surahs', 'duas', 'hadiths', 'history'));
    }

    public function storeHafalan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jenis_hafalan' => 'required|in:surat,hadist,doa',
            'nama_item' => 'required|string|max:150',
            'status' => 'required|in:hafal_sempurna,hafal_dengan_kesalahan,perlu_diulang',
            'catatan' => 'nullable|string|max:255',
        ]);

        PenilaianHafalan::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->guard('admin')->id(),
            'tanggal' => $request->tanggal,
            'jenis_hafalan' => $request->jenis_hafalan,
            'nama_item' => $request->nama_item,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.penilaian.hafalan', ['user_id' => $request->user_id])
            ->with('success', 'Penilaian hafalan santri berhasil disimpan.');
    }

    public function deleteHafalan($id)
    {
        $log = PenilaianHafalan::findOrFail($id);
        $userId = $log->user_id;
        $log->delete();

        return redirect()->route('admin.penilaian.hafalan', ['user_id' => $userId])
            ->with('success', 'Catatan penilaian hafalan berhasil dihapus.');
    }

    public function tulis(Request $request)
    {
        $students = User::where('is_active', true)->orderBy('nama_lengkap')->get();
        $selectedUserId = $request->query('user_id');

        $history = [];
        if ($selectedUserId) {
            $history = PenilaianTulis::with('admin')
                ->where('user_id', $selectedUserId)
                ->latest()
                ->take(10)
                ->get();
        }

        return view('admin.penilaian.tulis', compact('students', 'selectedUserId', 'history'));
    }

    public function storeTulis(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:200',
            'nilai' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string|max:255',
        ]);

        $nilai = $request->nilai;
        if ($nilai >= 90) {
            $grade = 'A';
        } elseif ($nilai >= 75) {
            $grade = 'B';
        } elseif ($nilai >= 60) {
            $grade = 'C';
        } else {
            $grade = 'D';
        }

        PenilaianTulis::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->guard('admin')->id(),
            'tanggal' => $request->tanggal,
            'materi' => $request->materi,
            'nilai' => $nilai,
            'grade' => $grade,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.penilaian.tulis', ['user_id' => $request->user_id])
            ->with('success', 'Penilaian tulis santri berhasil disimpan.');
    }

    public function deleteTulis($id)
    {
        $log = PenilaianTulis::findOrFail($id);
        $userId = $log->user_id;
        $log->delete();

        return redirect()->route('admin.penilaian.tulis', ['user_id' => $userId])
            ->with('success', 'Catatan penilaian menulis berhasil dihapus.');
    }

    public function praktik(Request $request)
    {
        $students = User::where('is_active', true)->orderBy('nama_lengkap')->get();
        $selectedUserId = $request->query('user_id');

        $history = [];
        if ($selectedUserId) {
            $history = PenilaianPraktik::with(['admin', 'komponenChecklist'])
                ->where('user_id', $selectedUserId)
                ->latest()
                ->take(10)
                ->get();
        }

        // Define default checklists inside array mapping for Fiqh
        $checklists = [
            'wudhu' => [
                'Niat Wudhu', 'Membaca Bismillah', 'Membasuh Kedua Telapak Tangan', 'Berkumur-kumur',
                'Membasuh/Menghirup Air ke Hidung', 'Membasuh Muka', 'Membasuh Tangan Kanan hingga Siku',
                'Membasuh Tangan Kiri hingga Siku', 'Mengusap Kepala', 'Mengusap Kedua Daun Telinga',
                'Membasuh Kaki Kanan hingga Mata Kaki', 'Membasuh Kaki Kiri hingga Mata Kaki',
                'Tertib (Berurutan)', 'Doa Setelah Wudhu'
            ],
            'sholat_fardhu' => [
                'Niat Sholat', 'Takbiratul Ihram', 'Doa Iftitah', 'Membaca Al-Fatihah', 'Membaca Surat/Ayat',
                'Ruku\' dengan Benar + Membaca Tasbih Ruku', 'I\'tidal + Doa I\'tidal', 'Sujud dengan Benar + Membaca Tasbih Sujud',
                'Duduk antara Dua Sujud + Doa', 'Tasyahud Awal', 'Tasyahud Akhir + Shalawat', 'Salam', 'Tertib'
            ],
            'sholat_sunnah' => [
                'Niat Sholat Sunnah', 'Takbiratul Ihram', 'Membaca Al-Fatihah', 'Membaca Surat/Ayat',
                'Ruku\' dengan Benar', 'I\'tidal', 'Sujud dengan Benar', 'Duduk antara Dua Sujud',
                'Tasyahud Akhir + Shalawat', 'Salam', 'Tertib'
            ],
            'tayamum' => [
                'Niat Tayamum', 'Menenempelkan Tangan ke Debu Suci', 'Meniup Debu Ringan',
                'Mengusap Muka', 'Mengusap Kedua Tangan hingga Siku', 'Tertib'
            ],
            'membaca_doa' => [
                'Membaca dengan Tartil', 'Adab Berdoa (Tengadah Tangan)', 'Kekhusyukan', 'Kebenaran Lafaz'
            ]
        ];

        return view('admin.penilaian.praktik', compact('students', 'selectedUserId', 'checklists', 'history'));
    }

    public function storePraktik(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jenis_praktik' => 'required|in:wudhu,sholat_fardhu,sholat_sunnah,tayamum,membaca_doa',
            'komponen' => 'required|array',
            'catatan' => 'nullable|string|max:255',
        ]);

        $praktik = PenilaianPraktik::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->guard('admin')->id(),
            'tanggal' => $request->tanggal,
            'jenis_praktik' => $request->jenis_praktik,
            'catatan' => $request->catatan,
        ]);

        foreach ($request->komponen as $nama => $val) {
            PenilaianPraktikKomponen::create([
                'penilaian_praktik_id' => $praktik->id,
                'nama_komponen' => $nama,
                'is_terpenuhi' => $val === '1',
            ]);
        }

        return redirect()->route('admin.penilaian.praktik', ['user_id' => $request->user_id])
            ->with('success', 'Penilaian praktik ibadah berhasil disimpan.');
    }

    public function deletePraktik($id)
    {
        $log = PenilaianPraktik::findOrFail($id);
        $userId = $log->user_id;
        $log->delete(); // Cascades components

        return redirect()->route('admin.penilaian.praktik', ['user_id' => $userId])
            ->with('success', 'Catatan penilaian praktik berhasil dihapus.');
    }
}
