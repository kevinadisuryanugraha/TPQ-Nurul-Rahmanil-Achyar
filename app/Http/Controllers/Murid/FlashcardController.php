<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\FlashcardDeck;
use App\Models\Doa;
use App\Models\Hadist;
use App\Models\Surah;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $decks = FlashcardDeck::where('is_active', true)
            ->get()
            ->sortByDesc(function ($deck) use ($student) {
                return $deck->level_target_id == $student->current_level_id ? 2 : 1;
            });

        return view('murid.flashcard.index', compact('decks', 'student'));
    }

    public function show($id)
    {
        $deck = FlashcardDeck::where('id', $id)->where('is_active', true)->firstOrFail();
        $student = auth()->user();
        $cardsData = [];

        if ($deck->source_type === 'system_doa') {
            $doas = Doa::where('is_active', true)->orderBy('urutan')->get();
            foreach ($doas as $doa) {
                $cardsData[] = [
                    'front' => "Membaca Doa: " . $doa->judul . "\n\nArti:\n" . $doa->terjemahan,
                    'back' => $doa->teks_arab . "\n\n" . $doa->transliterasi,
                ];
            }
        } elseif ($deck->source_type === 'system_hadist') {
            $hadists = Hadist::where('is_active', true)->get();
            foreach ($hadists as $hadist) {
                $cardsData[] = [
                    'front' => "Arti Hadits:\n" . $hadist->terjemahan . "\n\nSumber: " . $hadist->sumber_kitab,
                    'back' => $hadist->teks_arab,
                ];
            }
        } elseif ($deck->source_type === 'system_quran') {
            // Get 10 surahs starting from short ones (Juz 30 surahs like Al-Fatihah, An-Nas, Al-Falaq)
            $surahs = Surah::orderBy('id', 'desc')->take(10)->get();
            foreach ($surahs as $surah) {
                $cardsData[] = [
                    'front' => "Surat " . $surah->nama_latin . " (" . $surah->nama_indonesia . ")\n\nArti Nama:\n" . $surah->arti,
                    'back' => $surah->nama_arab,
                ];
            }
        } else {
            $items = $deck->items;
            foreach ($items as $item) {
                $cardsData[] = [
                    'front' => $item->front_content,
                    'back' => $item->back_content,
                ];
            }
        }

        if (empty($cardsData)) {
            return redirect()->route('murid.flashcard.index')->with('error', 'Dek ini belum memiliki kartu untuk dimainkan.');
        }

        return view('murid.flashcard.show', compact('deck', 'cardsData'));
    }

    public function finish(Request $request, $id)
    {
        $deck = FlashcardDeck::where('id', $id)->where('is_active', true)->firstOrFail();
        $student = auth()->user();

        // 1. Increment completions
        $student->increment('flashcard_completions_count');

        // 2. Add 10 points
        \App\Services\GamificationService::addPoints($student, 10);

        // 3. Check for flashcard badges
        $newBadges = \App\Services\GamificationService::checkAndAwardBadges($student, 'flashcard');

        return response()->json([
            'success' => true,
            'points_earned' => 10,
            'total_points' => $student->points,
            'new_badges' => $newBadges,
        ]);
    }
}
