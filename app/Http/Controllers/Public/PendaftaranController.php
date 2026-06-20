<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\LandingSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class PendaftaranController extends Controller
{
    /**
     * Show the online registration form.
     */
    public function create()
    {
        $recaptchaSiteKey = env('RECAPTCHA_SITE_KEY');
        $noWa = LandingSetting::getValue('no_wa', '6281234567890');

        return view('public.daftar', compact('recaptchaSiteKey', 'noWa'));
    }

    /**
     * Store the student registration submission.
     */
    public function store(Request $request)
    {
        // 1. Honeypot check (website_url must be empty)
        if ($request->filled('website_url')) {
            // Silently discard or redirect to thank you page to trick the bot
            return redirect()->route('daftar.thankyou')->with([
                'nama' => $request->nama_lengkap,
                'is_spam' => true
            ]);
        }

        // 2. IP Rate Limiting (max 5 submissions per hour)
        $rateKey = 'psb-submit:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $seconds = RateLimiter::availableIn($rateKey);
            $minutes = ceil($seconds / 60);
            return redirect()->back()
                ->withInput()
                ->with('error', "Terlalu banyak percobaan pendaftaran dari IP Anda. Silakan coba lagi dalam {$minutes} menit.");
        }

        // Increment rate limit attempts
        RateLimiter::hit($rateKey, 3600); // 1 hour decay

        // 3. reCAPTCHA v3 verification (only if keys are configured)
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        if ($recaptchaSecret) {
            $token = $request->input('g-recaptcha-response');
            if (!$token) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Verifikasi keamanan (reCAPTCHA) tidak ditemukan.');
            }

            try {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $recaptchaSecret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

                $resData = $response->json();
                if (!($resData['success'] ?? false) || ($resData['score'] ?? 1.0) < 0.5) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Verifikasi keamanan mendeteksi aktivitas mencurigakan. Silakan coba lagi.');
                }
            } catch (\Exception $e) {
                // If Google API is down, log error and allow in local/fallback
                logger()->error('reCAPTCHA verification failed: ' . $e->getMessage());
            }
        }

        // 4. Validate Form Fields
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_orang_tua' => 'required|string|max:100',
            'no_wa' => 'required|string|max:20',
            'alamat' => 'required|string',
            'pernah_mengaji' => 'required|in:ya,tidak',
            'level_mengaji_sebelumnya' => 'nullable|required_if:pernah_mengaji,ya|string|max:100',
            'catatan_tambahan' => 'nullable|string|max:1000',
        ], [
            'level_mengaji_sebelumnya.required_if' => 'Mohon isi level mengaji terakhir jika pernah belajar mengaji.',
        ]);

        // 5. Soft Duplicate Check (name + no_wa in last 24 hours)
        $isDuplicate = Pendaftar::where('nama_lengkap', $request->nama_lengkap)
            ->where('no_wa', $request->no_wa)
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        // 6. Create registration record
        $pendaftar = Pendaftar::create([
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_orang_tua' => $request->nama_orang_tua,
            'no_wa' => $request->no_wa,
            'alamat' => $request->alamat,
            'pernah_mengaji' => $request->pernah_mengaji === 'ya',
            'level_mengaji_sebelumnya' => $request->pernah_mengaji === 'ya' ? $request->level_mengaji_sebelumnya : null,
            'catatan_tambahan' => $request->catatan_tambahan,
            'status' => 'baru',
        ]);

        // Clear rate limiter upon successful submission
        RateLimiter::clear($rateKey);

        return redirect()->route('daftar.thankyou')->with([
            'nama' => $pendaftar->nama_lengkap,
            'is_duplicate' => $isDuplicate,
        ]);
    }

    /**
     * Show the thank you / confirmation page.
     */
    public function thankyou()
    {
        $nama = session('nama');
        if (!$nama) {
            return redirect()->route('landing');
        }

        $isDuplicate = session('is_duplicate', false);
        $noWa = LandingSetting::getValue('no_wa', '6281234567890');
        
        // Prefilled message for manual follow up WhatsApp link
        $waMessage = urlencode("Assalamu'alaikum, saya " . session('nama_orang_tua', 'Orang Tua') . " dari Calon Santri " . $nama . ". Saya baru saja melakukan pendaftaran online di TPQ dan ingin melakukan konfirmasi. Terima kasih.");
        $waLink = "https://wa.me/{$noWa}?text={$waMessage}";

        return view('public.terima-kasih', compact('nama', 'isDuplicate', 'waLink'));
    }
}
