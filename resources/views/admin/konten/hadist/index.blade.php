@extends('layouts.admin')

@section('title', 'Manajemen Hadist')
@section('page_title', 'Manajemen Hadist Pilihan')

@section('content')
<div x-data="{ 
    isAddOpen: false, 
    isEditOpen: false,
    editHadist: { id: '', teks_arab: '', terjemahan: '', sumber_kitab: '', perawi: '', kategori: '', is_active: true }
}">
    <!-- Top Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Search and Filter -->
        <form action="{{ route('admin.konten.hadist.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[240px]">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari hadist (terjemahan, perawi, kitab)..." 
                    class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>

            @if(request('search'))
                <a href="{{ route('admin.konten.hadist.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">
                    Reset Filter
                </a>
            @endif
        </form>

        <!-- Add Button -->
        <button @click="isAddOpen = true" 
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition duration-200 shadow-sm flex items-center justify-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Hadist Baru</span>
        </button>
    </div>

    <!-- Hadist Table List -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hadist</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sanad & Kategori</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($hadists as $hadist)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="text-right text-emerald-950 font-semibold text-lg arabic-text mb-2">{{ $hadist->teks_arab }}</div>
                                <div class="text-xs text-gray-600 max-w-2xl">"{{ $hadist->terjemahan }}"</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-gray-900">HR. {{ $hadist->sumber_kitab }}</div>
                                @if($hadist->perawi)
                                    <div class="text-xs text-gray-500">Dari: {{ $hadist->perawi }}</div>
                                @endif
                                @if($hadist->kategori)
                                    <span class="mt-1 inline-block bg-amber-50 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-100 uppercase tracking-wider">
                                        {{ $hadist->kategori }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($hadist->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-1 whitespace-nowrap">
                                <button 
                                    @click="
                                        editHadist = { 
                                            id: '{{ $hadist->id }}', 
                                            teks_arab: '{{ addslashes($hadist->teks_arab) }}', 
                                            terjemahan: '{{ addslashes($hadist->terjemahan) }}', 
                                            sumber_kitab: '{{ addslashes($hadist->sumber_kitab) }}', 
                                            perawi: '{{ addslashes($hadist->perawi) }}', 
                                            kategori: '{{ addslashes($hadist->kategori) }}', 
                                            is_active: {{ $hadist->is_active ? 'true' : 'false' }}
                                        }; 
                                        isEditOpen = true;
                                    "
                                    class="text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.konten.hadist.destroy', $hadist->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hadist ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-book-quran text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm">Tidak ada data hadist ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($hadists->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $hadists->links() }}
            </div>
        @endif
    </div>

    <!-- ================= MODAL ADD ================= -->
    <div x-show="isAddOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 overflow-y-auto" style="display: none;">
        <div @click.away="isAddOpen = false" class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative my-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fa-solid fa-plus text-emerald-600 mr-2"></i>
                Tambah Hadist Baru
            </h3>
            
            <form action="{{ route('admin.konten.hadist.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Teks Arab *</label>
                        <textarea name="teks_arab" required rows="3" dir="rtl" placeholder="مَنْ سَلَكَ طَرِيقًا..." 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-lg font-semibold arabic-text text-right focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Terjemahan *</label>
                        <textarea name="terjemahan" required rows="3" placeholder="Artinya: Siapa yang menempuh jalan..." 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sumber Kitab *</label>
                            <input type="text" name="sumber_kitab" required placeholder="Contoh: Bukhari, Muslim" 
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Perawi (Optional)</label>
                            <input type="text" name="perawi" placeholder="Contoh: Abu Hurairah" 
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kategori (Optional)</label>
                            <input type="text" name="kategori" placeholder="Contoh: Akhlak, Menuntut Ilmu" 
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="flex items-center pt-6">
                            <input type="checkbox" id="is_active_add" name="is_active" value="1" checked
                                class="h-4.5 w-4.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="is_active_add" class="ml-2 text-sm text-gray-700 font-semibold">Tampilkan ke Santri</label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="isAddOpen = false" 
                        class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                        Simpan Hadist
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    <div x-show="isEditOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 overflow-y-auto" style="display: none;">
        <div @click.away="isEditOpen = false" class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative my-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fa-solid fa-pen-to-square text-amber-600 mr-2"></i>
                Edit Hadist
            </h3>
            
            <form :action="'{{ url('/admin/konten/hadist') }}/' + editHadist.id" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Teks Arab *</label>
                        <textarea name="teks_arab" required rows="3" dir="rtl" x-model="editHadist.teks_arab"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-lg font-semibold arabic-text text-right focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Terjemahan *</label>
                        <textarea name="terjemahan" required rows="3" x-model="editHadist.terjemahan"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Sumber Kitab *</label>
                            <input type="text" name="sumber_kitab" required x-model="editHadist.sumber_kitab"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Perawi (Optional)</label>
                            <input type="text" name="perawi" x-model="editHadist.perawi"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kategori (Optional)</label>
                            <input type="text" name="kategori" x-model="editHadist.kategori"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="flex items-center pt-6">
                            <input type="checkbox" id="is_active_edit" name="is_active" value="1" x-model="editHadist.is_active"
                                class="h-4.5 w-4.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <label for="is_active_edit" class="ml-2 text-sm text-gray-700 font-semibold">Tampilkan ke Santri</label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="isEditOpen = false" 
                        class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
