<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;

class GamificationService
{
    public static function addPoints(User $user, int $amount): void
    {
        $user->increment('points', $amount);
    }

    public static function checkAndAwardBadges(User $user, string $type): array
    {
        $newlyAwarded = [];

        // 1. Get all badges matching the type
        $availableBadges = Badge::where('syarat_tipe', $type)->get();
        if ($availableBadges->isEmpty()) {
            return [];
        }

        // 2. Get user's current earned badge IDs
        $earnedBadgeIds = $user->badges()->pluck('badge_id')->toArray();

        // 3. Compute user's progress/metric for this type
        $userMetricValue = self::getUserMetricValue($user, $type);

        foreach ($availableBadges as $badge) {
            // If already earned, skip
            if (in_array($badge->id, $earnedBadgeIds)) {
                continue;
            }

            // Check if metric meets the requirement
            if ($userMetricValue >= $badge->syarat_jumlah) {
                // Award badge!
                $user->badges()->attach($badge->id);
                $newlyAwarded[] = $badge;
            }
        }

        return $newlyAwarded;
    }

    private static function getUserMetricValue(User $user, string $type): int
    {
        switch ($type) {
            case 'absensi':
                return $user->absensis()->where('status', 'hadir')->count();

            case 'flashcard':
                return $user->flashcard_completions_count;

            case 'tulis':
                $avg = $user->penilaianTulises()->avg('nilai');
                return $avg ? (int) round($avg) : 0;
        }

        return 0;
    }
}
