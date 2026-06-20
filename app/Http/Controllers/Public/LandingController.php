<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use App\Models\Galeri;
use App\Models\Testimoni;
use App\Models\PengurusProfile;

class LandingController extends Controller
{
    /**
     * Display the public landing page.
     */
    public function index()
    {
        // Retrieve all landing settings keys and values
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

        $landingSettings = [];
        foreach ($settingsKeys as $key) {
            $landingSettings[$key] = LandingSetting::getValue($key);
        }

        // Get galleries, testimonials, and organizational profiles
        $galleries = Galeri::active()->orderBy('urutan')->orderBy('id')->get();
        $testimonials = Testimoni::active()->orderBy('urutan')->orderBy('id')->get();
        $pengurusList = PengurusProfile::active()->orderBy('urutan')->orderBy('id')->get();

        return view('public.landing', compact('landingSettings', 'galleries', 'testimonials', 'pengurusList'));
    }
}
