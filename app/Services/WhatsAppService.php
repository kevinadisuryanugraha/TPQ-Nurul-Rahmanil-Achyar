<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public static function send(string $phone, string $message): bool
    {
        // 1. Clean phone number format
        $phone = self::formatPhoneNumber($phone);
        if (!$phone) {
            return false;
        }

        // 2. Fetch config from env
        $token = env('WA_API_TOKEN');
        $device = env('WA_DEVICE');

        // 3. Simulation / Logging in workspace
        $logMessage = sprintf(
            "[%s] WHATSAPP SENT TO: %s | MESSAGE: %s",
            now()->toDateTimeString(),
            $phone,
            $message
        );
        
        // Log to workspace log
        Log::channel('single')->info($logMessage);
        
        // Write to storage/logs/whatsapp.log specifically for audit/manual review
        $logPath = storage_path('logs/whatsapp.log');
        
        // Ensure folder exists
        $dir = dirname($logPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($logPath, $logMessage . PHP_EOL, FILE_APPEND);

        // 4. Real Gateway Post if config exists (e.g. Fonnte API)
        if ($token && $device) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $phone,
                    'message' => $message,
                    'country' => '62', // default Indonesia
                ]);

                return $response->successful();
            } catch (\Exception $e) {
                Log::error("Failed to send WhatsApp via Fonnte: " . $e->getMessage());
                return false;
            }
        }

        return true;
    }

    public static function sendAbsenceNotification(User $student, string $date, string $session): bool
    {
        if (!$student->no_hp_orang_tua) {
            return false;
        }

        $formattedDate = \Carbon\Carbon::parse($date)->translatedFormat('d M Y');
        $message = sprintf(
            "Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\nInformasi Kehadiran *TPQ Nurul Rahmanil Achyar*:\n\nMenginfokan bahwa pada tanggal *%s* Sesi *%s*, ananda *%s* tercatat *Tidak Hadir (Tanpa Keterangan/Alpha)*.\n\nMohon konfirmasi kehadirannya jika terdapat kekeliruan atau halangan. Terima kasih.",
            $formattedDate,
            $session,
            $student->nama_lengkap
        );

        return self::send($student->no_hp_orang_tua, $message);
    }

    public static function sendLevelUpNotification(User $student, string $newLevelName): bool
    {
        if (!$student->no_hp_orang_tua) {
            return false;
        }

        $message = sprintf(
            "Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\nKabar gembira dari *TPQ Nurul Rahmanil Achyar*!\n\nAlhamdulillah, ananda *%s* hari ini telah berhasil naik ke level *%s*.\n\nMari kita dampingi dan terus beri motivasi belajarnya di rumah. Jazakumullah khairan.",
            $student->nama_lengkap,
            $newLevelName
        );

        return self::send($student->no_hp_orang_tua, $message);
    }

    public static function sendLevelDownNotification(User $student, string $newLevelName): bool
    {
        if (!$student->no_hp_orang_tua) {
            return false;
        }

        $message = sprintf(
            "Assalamu'alaikum Warahmatullahi Wabarakatuh.\n\nInformasi Kurikulum dari *TPQ Nurul Rahmanil Achyar*:\n\nMenginfokan bahwa tingkat level belajar ananda *%s* disesuaikan kembali ke *%s* untuk penguatan materi dasar.\n\nMohon bantu pantau belajarnya di rumah agar materi dasar dapat dikuasai dengan lebih matang. Terima kasih.",
            $student->nama_lengkap,
            $newLevelName
        );

        return self::send($student->no_hp_orang_tua, $message);
    }

    private static function formatPhoneNumber(string $phone): ?string
    {
        // Remove spaces, dashes, parentheses
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($phone)) {
            return null;
        }

        // Convert leading 0 to 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Ensure starts with 62
        if (!str_starts_with($phone, '62')) {
            if (str_starts_with($phone, '8')) {
                $phone = '62' . $phone;
            } else {
                return null; // invalid indonesian phone format
            }
        }

        return $phone;
    }
}
