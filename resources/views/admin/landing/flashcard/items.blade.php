@extends('layouts.admin')
@section('title', 'Kelola Kartu')
@section('page_title')
    Kelola Kartu: {{ $deck->nama }}
@endsection
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Form Tambah Kartu -->
    <div class="lg:col-span-5 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden h-fit">
        <div class="h-1.5 bg-emerald-800"></div>
        <div class="p-6">
            <h3 class="font-bold text-gray-800 text-sm mb-4">Tambah Kartu Baru</h3>
            <form action="{{ route('admin.konten.flashcard.items.store', $deck->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="front_content" class="block text-xs font-bold text-gray-600 mb-1.5">Sisi Depan (Pertanyaan / Petunjuk)</label>
                    <textarea name="front_content" id="front_content" required rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-emerald-800 resize-none" placeholder="Masukkan tulisan depan..."></textarea>
                </div>
                <div>
                    <label for="back_content" class="block text-xs font-bold text-gray-600 mb-1.5">Sisi Belakang (Jawaban / Penjelasan)</label>
                    <textarea name="back_content" id="back_content" required rows="3" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-emerald-800 resize-none" placeholder="Masukkan tulisan belakang..."></textarea>
                </div>
                <div>
                    <label for="urutan" class="block text-xs font-bold text-gray-600 mb-1.5">Nomor Urutan</label>
                    <input type="number" name="urutan" id="urutan" value="0" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-emerald-800">
                </div>
                <button type="submit" class="w-full py-3 bg-emerald-800 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Kartu
                </button>
            </form>
        </div>
    </div>

    <!-- List Kartu Existing -->
    <div class="lg:col-span-7 space-y-4">
        <h3 class="font-bold text-gray-800 text-sm">Daftar Kartu yang Sudah Ada</h3>
        
        @if($items->isEmpty())
            <div class="bg-white p-12 text-center rounded-3xl border border-gray-100 shadow-sm text-gray-400">
                <i class="fa-solid fa-clone text-3xl mb-3 block text-gray-300"></i>
                <span class="text-xs">Belum ada kartu di dek ini. Tambahkan kartu pertama Anda di sebelah kiri.</span>
            </div>
        @else
            <div class="space-y-3">
                @foreach($items as $index => $item)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4" x-data="{ editing: false }">
                        
                        <!-- View Mode -->
                        <div x-show="!editing" class="flex justify-between items-start">
                            <div class="grid grid-cols-2 gap-4 w-10/12">
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Sisi Depan</span>
                                    <p class="text-xs text-gray-700 mt-1 font-medium whitespace-pre-line">{{ $item->front_content }}</p>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Sisi Belakang</span>
                                    <p class="text-xs text-gray-700 mt-1 font-medium whitespace-pre-line">{{ $item->back_content }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1.5">
                                <button @click="editing = true" class="p-1 text-gray-500 hover:text-emerald-800 transition"><i class="fa-solid fa-pen text-xs"></i></button>
                                <form action="{{ route('admin.konten.flashcard.items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kartu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <form x-show="editing" style="display:none;" action="{{ route('admin.konten.flashcard.items.update', $item->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Edit Sisi Depan</label>
                                <textarea name="front_content" required rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none resize-none">{{ $item->front_content }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Edit Sisi Belakang</label>
                                <textarea name="back_content" required rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none resize-none">{{ $item->back_content }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Urutan</label>
                                    <input type="number" name="urutan" value="{{ $item->urutan }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none">
                                </div>
                                <div class="flex gap-2 justify-end items-end">
                                    <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-200 transition">Batal</button>
                                    <button type="submit" class="px-4 py-2 bg-emerald-800 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition">Simpan</button>
                                </div>
                            </div>
                        </form>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
