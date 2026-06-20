<?php

namespace App\Http\Controllers\Admin\Landing;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use Illuminate\Http\Request;

class LandingSettingController extends Controller
{
    /**
     * Show the form for editing landing settings.
     */
    public function edit()
    {
        $settingsKeys = [
            'hero_headline',
            'hero_subheadline',
            'tentang_kami',
            'visi',
            'misi',
            'poin_keunggulan',
            'alamat',
            'maps_embed_url',
            'jam_operasional',
            'no_wa',
            'no_telpon',
            'email',
            'instagram_url',
            'facebook_url'
        ];

        $settings = [];
        foreach ($settingsKeys as $key) {
            $settings[$key] = LandingSetting::getValue($key);
        }

        return view('admin.landing.pengaturan', compact('settings'));
    }

    /**
     * Update the landing settings in the database.
     */
    public function update(Request $request)
    {
        $request->validate([
            'hero_headline' => 'required|string|max:200',
            'hero_subheadline' => 'required|string|max:500',
            'tentang_kami' => 'required|string|max:1000',
            'visi' => 'required|string|max:500',
            'misi' => 'required|string', // text area, split by line break
            'alamat' => 'required|string|max:500',
            'maps_embed_url' => 'required|string|max:2000',
            'jam_operasional' => 'required|string|max:200',
            'no_wa' => 'required|string|max:20',
            'no_telpon' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
            'instagram_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            // Poin keunggulan validation
            'keunggulan_title_1' => 'required|string|max:100',
            'keunggulan_desc_1' => 'required|string|max:200',
            'keunggulan_title_2' => 'nullable|string|max:100',
            'keunggulan_desc_2' => 'nullable|required_with:keunggulan_title_2|string|max:200',
            'keunggulan_title_3' => 'nullable|string|max:100',
            'keunggulan_desc_3' => 'nullable|required_with:keunggulan_title_3|string|max:200',
            'keunggulan_title_4' => 'nullable|string|max:100',
            'keunggulan_desc_4' => 'nullable|required_with:keunggulan_title_4|string|max:200',
        ]);

        // 1. Process basic key-value settings
        $keys = [
            'hero_headline',
            'hero_subheadline',
            'tentang_kami',
            'visi',
            'alamat',
            'maps_embed_url',
            'jam_operasional',
            'no_wa',
            'no_telpon',
            'email',
            'instagram_url',
            'facebook_url'
        ];

        foreach ($keys as $key) {
            LandingSetting::setValue($key, $request->input($key));
        }

        // 2. Process Misi (split by newlines and trim)
        $misiText = $request->input('misi');
        $misiLines = explode("\n", $misiText);
        $misiArray = array_values(array_filter(array_map('trim', $misiLines)));
        LandingSetting::setValue('misi', $misiArray);

        // 3. Process Poin Keunggulan
        $keunggulan = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->filled("keunggulan_title_$i")) {
                $keunggulan[] = [
                    'title' => $request->input("keunggulan_title_$i"),
                    'desc' => $request->input("keunggulan_desc_$i"),
                ];
            }
        }
        LandingSetting::setValue('poin_keunggulan', $keunggulan);

        return redirect()->route('admin.landing.pengaturan.edit')->with('success', 'Pengaturan Landing Page berhasil diperbarui.');
    }
}
