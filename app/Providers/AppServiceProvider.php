<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            $settingsFile = 'settings.json';
            $settings = [
                'nama_tpq' => 'TPQ Al-Istiqomah',
                'logo_tpq' => '/images/logo-default.png',
                'deskripsi_tpq' => 'Taman Pendidikan Al-Qur\'an Al-Istiqomah',
                'tahun_ajaran' => '2025/2026',
                'sesi' => ['Pagi', 'Sore', 'Malam']
            ];
            if (\Illuminate\Support\Facades\Storage::exists($settingsFile)) {
                try {
                    $settings = json_decode(\Illuminate\Support\Facades\Storage::get($settingsFile), true) ?: $settings;
                } catch (\Exception $e) {
                    // fallback to defaults
                }
            }
            $view->with('appSettings', $settings);
        });
    }
}
