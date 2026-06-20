<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class SettingController extends Controller
{
    private $settingsFile = 'settings.json';

    public function getSettings()
    {
        if (!Storage::exists($this->settingsFile)) {
            $defaultSettings = [
                'nama_tpq' => 'TPQ Al-Istiqomah',
                'logo_tpq' => '/images/logo-default.png',
                'deskripsi_tpq' => 'Taman Pendidikan Al-Qur\'an Al-Istiqomah',
                'tahun_ajaran' => '2025/2026',
                'sesi' => ['Pagi', 'Sore', 'Malam']
            ];
            Storage::put($this->settingsFile, json_encode($defaultSettings, JSON_PRETTY_PRINT));
            return $defaultSettings;
        }

        return json_decode(Storage::get($this->settingsFile), true);
    }

    public function index()
    {
        $settings = $this->getSettings();
        return view('superadmin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_tpq' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:png,svg,webp,jpg,jpeg|max:2048',
            'deskripsi_tpq' => 'nullable|string|max:500',
            'tahun_ajaran' => 'required|string|max:20',
            'sesi' => 'required|string', // comma separated strings
        ]);

        $settings = $this->getSettings();
        $settings['nama_tpq'] = $request->nama_tpq;
        $settings['deskripsi_tpq'] = $request->deskripsi_tpq;
        $settings['tahun_ajaran'] = $request->tahun_ajaran;
        
        // Parse sessions
        $sesiArray = array_map('trim', explode(',', $request->sesi));
        $settings['sesi'] = array_values(array_filter($sesiArray));

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store file on public disk
            $file->storeAs('uploads', $filename, 'public');
            $settings['logo_tpq'] = '/storage/uploads/' . $filename;
        }

        Storage::put($this->settingsFile, json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->route('superadmin.settings')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
