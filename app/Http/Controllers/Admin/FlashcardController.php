<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashcardDeck;
use App\Models\FlashcardItem;
use App\Models\Level;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index()
    {
        $decks = FlashcardDeck::with('level')->get();
        return view('admin.landing.flashcard.index', compact('decks'));
    }

    public function create()
    {
        $levels = Level::orderBy('urutan')->get();
        return view('admin.landing.flashcard.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'source_type' => 'required|in:system_doa,system_hadist,system_quran,custom',
            'level_target_id' => 'nullable|exists:levels,id',
            'is_active' => 'required|boolean',
        ]);

        FlashcardDeck::create($validated);
        return redirect()->route('admin.konten.flashcard.index')->with('success', 'Dek Flashcard berhasil dibuat.');
    }

    public function edit($id)
    {
        $deck = FlashcardDeck::findOrFail($id);
        $levels = Level::orderBy('urutan')->get();
        return view('admin.landing.flashcard.edit', compact('deck', 'levels'));
    }

    public function update(Request $request, $id)
    {
        $deck = FlashcardDeck::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'level_target_id' => 'nullable|exists:levels,id',
            'is_active' => 'required|boolean',
        ]);

        $deck->update($validated);
        return redirect()->route('admin.konten.flashcard.index')->with('success', 'Dek Flashcard berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $deck = FlashcardDeck::findOrFail($id);
        $deck->delete();
        return redirect()->route('admin.konten.flashcard.index')->with('success', 'Dek Flashcard berhasil dihapus.');
    }

    public function itemsIndex($deck_id)
    {
        $deck = FlashcardDeck::findOrFail($deck_id);
        if ($deck->source_type !== 'custom') {
            return redirect()->route('admin.konten.flashcard.index')->with('error', 'Dek bawaan sistem tidak bisa diedit kartunya secara manual.');
        }
        $items = $deck->items;
        return view('admin.landing.flashcard.items', compact('deck', 'items'));
    }

    public function itemsStore(Request $request, $deck_id)
    {
        $deck = FlashcardDeck::findOrFail($deck_id);
        $validated = $request->validate([
            'front_content' => 'required|string',
            'back_content' => 'required|string',
            'urutan' => 'nullable|integer',
        ]);

        $deck->items()->create($validated);
        return redirect()->back()->with('success', 'Kartu berhasil ditambahkan.');
    }

    public function itemsUpdate(Request $request, $item_id)
    {
        $item = FlashcardItem::findOrFail($item_id);
        $validated = $request->validate([
            'front_content' => 'required|string',
            'back_content' => 'required|string',
            'urutan' => 'required|integer',
        ]);

        $item->update($validated);
        return redirect()->back()->with('success', 'Kartu berhasil diperbarui.');
    }

    public function itemsDestroy($item_id)
    {
        $item = FlashcardItem::findOrFail($item_id);
        $item->delete();
        return redirect()->back()->with('success', 'Kartu berhasil dihapus.');
    }
}
