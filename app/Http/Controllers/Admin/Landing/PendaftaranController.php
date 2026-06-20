<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of PSB registrations.
     */
    public function index(Request $request)
    {
        $query = Pendaftar::latest();

        // Search by Name or Parent
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$search}%")
                  ->orWhere('no_wa', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $registrations = $query->paginate(20)->withQueryString();

        return view('admin.landing.pendaftaran.index', compact('registrations'));
    }

    /**
     * Display the specified PSB registration details.
     */
    public function show($id)
    {
        $registration = Pendaftar::with('user')->findOrFail($id);
        return view('admin.landing.pendaftaran.show', compact('registration'));
    }

    /**
     * Update the registration status and internal notes.
     */
    public function updateStatus(Request $request, $id)
    {
        $registration = Pendaftar::findOrFail($id);

        $request->validate([
            'status' => 'required|in:baru,dihubungi,diterima,ditolak',
            'catatan_internal' => 'nullable|string|max:2000',
        ]);

        $registration->update([
            'status' => $request->status,
            'catatan_internal' => $request->catatan_internal,
        ]);

        return redirect()->route('admin.landing.pendaftaran.show', $id)
            ->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    /**
     * Redirect to the Admin add student form with pre-filled parameters.
     */
    public function terimaForm($id)
    {
        $registration = Pendaftar::findOrFail($id);

        if ($registration->status === 'diterima') {
            return redirect()->route('admin.landing.pendaftaran.show', $id)
                ->with('error', 'Pendaftaran ini sudah diterima sebelumnya.');
        }

        // Redirect to murid creation form with pre-filled queries
        return redirect()->route('admin.murid.create', [
            'prefill_nama' => $registration->nama_lengkap,
            'prefill_tempat_lahir' => $registration->tempat_lahir,
            'prefill_tanggal_lahir' => $registration->tanggal_lahir ? $registration->tanggal_lahir->format('Y-m-d') : null,
            'prefill_jenis_kelamin' => $registration->jenis_kelamin,
            'prefill_nama_ortu' => $registration->nama_orang_tua,
            'prefill_no_hp_ortu' => $registration->no_wa,
            'prefill_alamat' => $registration->alamat,
            'pendaftar_id' => $registration->id,
        ]);
    }
}
