<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use App\Models\UserLevelHistory;
use App\Models\Absensi;
use App\Models\PenilaianBaca;
use App\Models\PenilaianHafalan;
use App\Models\PenilaianTulis;
use App\Models\PenilaianPraktik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Models\Pendaftar;

class MuridController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('currentLevel')->latest();

        // Filter by Level
        if ($request->has('level_id') && $request->level_id !== '') {
            $query->where('current_level_id', $request->level_id);
        }

        // Filter by Status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Search by Name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(20)->withQueryString();
        $levels = Level::orderBy('urutan')->get();

        return view('admin.murid.index', compact('students', 'levels'));
    }

    public function create()
    {
        $levels = Level::orderBy('urutan')->get();
        return view('admin.murid.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nama_panggilan' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_masuk' => 'required|date',
            'current_level_id' => 'required|exists:levels,id',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'nama_orang_tua' => 'nullable|string|max:100',
            'no_hp_orang_tua' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('students', 'public');
            $fotoPath = '/storage/' . $fotoPath;
        }

        $student = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_masuk' => $request->tanggal_masuk,
            'current_level_id' => $request->current_level_id,
            'username' => strtolower(str_replace(' ', '', $request->username)),
            'password' => bcrypt($request->password),
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nama_orang_tua' => $request->nama_orang_tua,
            'no_hp_orang_tua' => $request->no_hp_orang_tua,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
            'is_active' => true,
        ]);

        // Assign Spatie Role 'murid' to student
        $role = Role::firstOrCreate(['name' => 'murid', 'guard_name' => 'web']);
        $student->assignRole($role);

        // Record Initial Level History
        UserLevelHistory::create([
            'user_id' => $student->id,
            'level_id' => $student->current_level_id,
            'level_sebelumnya_id' => null,
            'admin_id' => auth()->guard('admin')->id(),
            'tipe' => 'awal',
            'catatan' => 'Level awal saat pendaftaran santri baru',
        ]);

        // If created from PSB, link and update status
        if ($request->filled('pendaftar_id')) {
            $pendaftar = Pendaftar::find($request->pendaftar_id);
            if ($pendaftar) {
                $pendaftar->update([
                    'status' => 'diterima',
                    'user_id' => $student->id,
                ]);
            }
        }

        return redirect()->route('admin.murid.index')->with('success', 'Santri baru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $student = User::with(['currentLevel'])->findOrFail($id);

        // Attendance stats this month
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $attendanceRecords = Absensi::where('user_id', $student->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->get();

        $absensiStats = [
            'hadir' => $attendanceRecords->where('status', 'hadir')->count(),
            'izin' => $attendanceRecords->where('status', 'izin')->count(),
            'sakit' => $attendanceRecords->where('status', 'sakit')->count(),
            'alpha' => $attendanceRecords->where('status', 'alpha')->count(),
            'total' => $attendanceRecords->count(),
        ];

        // Level history
        $levelHistories = UserLevelHistory::with(['level', 'levelSebelumnya', 'admin'])
            ->where('user_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Latest Assessments
        $latestBaca = PenilaianBaca::where('user_id', $student->id)->latest()->first();
        $latestHafalan = PenilaianHafalan::where('user_id', $student->id)->latest()->first();
        $latestTulis = PenilaianTulis::where('user_id', $student->id)->latest()->first();
        $latestPraktik = PenilaianPraktik::with('komponenChecklist')->where('user_id', $student->id)->latest()->first();

        // Level navigation logic (find next/prev levels)
        $levels = Level::orderBy('urutan')->get();
        $currentUrutan = $student->currentLevel->urutan;
        
        $nextLevel = $levels->where('urutan', $currentUrutan + 1)->first();
        $prevLevel = $levels->where('urutan', $currentUrutan - 1)->first();

        return view('admin.murid.show', compact(
            'student', 
            'absensiStats', 
            'levelHistories',
            'latestBaca',
            'latestHafalan',
            'latestTulis',
            'latestPraktik',
            'nextLevel',
            'prevLevel'
        ));
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        $levels = Level::orderBy('urutan')->get();
        return view('admin.murid.edit', compact('student', 'levels'));
    }

    public function update(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nama_panggilan' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_masuk' => 'required|date',
            'username' => 'required|string|max:50|unique:users,username,' . $id,
            'foto' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'nama_orang_tua' => 'nullable|string|max:100',
            'no_hp_orang_tua' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        $updateData = [
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_masuk' => $request->tanggal_masuk,
            'username' => strtolower(str_replace(' ', '', $request->username)),
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nama_orang_tua' => $request->nama_orang_tua,
            'no_hp_orang_tua' => $request->no_hp_orang_tua,
            'alamat' => $request->alamat,
            'is_active' => $request->is_active,
        ];

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('students', 'public');
            $updateData['foto'] = '/storage/' . $fotoPath;
        }

        $student->update($updateData);

        return redirect()->route('admin.murid.show', $student->id)->with('success', 'Data santri berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $student = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $student->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.murid.show', $student->id)->with('success', 'Password santri ' . $student->nama_panggilan . ' berhasil direset.');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);
        $student->update(['is_active' => false]);

        return redirect()->route('admin.murid.index')->with('success', 'Santri berhasil dinonaktifkan.');
    }
}
