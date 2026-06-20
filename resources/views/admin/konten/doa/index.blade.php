@extends('layouts.admin')

@section('title', 'Manajemen Doa-Doa')
@section('page_title', 'Manajemen Doa Harian')

@section('content')
<div x-data="{ 
    isAddOpen: false, 
    isEditOpen: false,
    editDoa: { id: '', judul: '', teks_arab: '', transliterasi: '', terjemahan: '', kategori: '', urutan: '', is_active: true }
}">
    <!-- Top Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Search and Filter -->
        <form action="{{ route('admin.konten.doa.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[240px]">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari doa (judul, terjemahan)..." 
                    class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
            </div>

            <select name="kategori" onchange="this.form.submit()" 
                class="border border-gray-300 rounded-xl px-4 py-2.5 bg-white text-sm focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            @if(request('search') || request('kategori'))
                <a href="{{ route('admin.konten.doa.index') }}" class="text-xs text-rose-600 hover:underline font-semibold">
                    Reset Filter
                </a>
            @endif
        </form>

        <!-- Add Button -->
        <button @click="isAddOpen = true" 
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition duration-200 shadow-sm flex items-center justify-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Doa Baru</span>
        </button>
    </div>

    <!-- Doa Table List -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">Urutan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Doa</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($doas as $doa)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $doa->urutan ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $doa->judul }}</div>
                                <div class="mt-2 text-right text-emerald-950 font-semibold text-lg arabic-text mb-1">{{ $doa->teks_arab }}</div>
                                <div class="text-xs text-gray-500 italic mb-1">{{ $doa->transliterasi }}</div>
                                <div class="text-xs text-gray-600 line-clamp-2">"{{ $doa->terjemahan }}"</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-emerald-50 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-100">
                                    {{ $doa->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($doa->is_active)
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
                                        editDoa = { 
                                            id: '{{ $doa->id }}', 
                                            judul: '{{ addslashes($doa->judul) }}', 
                                            teks_arab: '{{ addslashes($doa->teks_arab) }}', 
                                            transliterasi: '{{ addslashes($doa->transliterasi) }}', 
                                            terjemahan: '{{ addslashes($doa->terjemahan) }}', 
                                            kategori: '{{ addslashes($doa->kategori) }}', 
                                            urutan: '{{ $doa->urutan }}',
                                            is_active: {{ $doa->is_active ? 'true' : 'false' }}
                                        }; 
                                        isEditOpen = true;
                                    "
                                    class="text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.konten.doa.destroy', $doa->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus doa ini?')">
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
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-hands-praying text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm">Tidak ada data doa ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($doas->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $doas->links() }}
            </div>
        @endif
    </div>

    <!-- ================= MODAL ADD ================= -->
    <div x-show="isAddOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 overflow-y-auto" style="display: none;">
        <div @click.away="isAddOpen = false" class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative my-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fa-solid fa-plus text-emerald-600 mr-2"></i>
                Tambah Doa Baru
            </h3>
            
            <form action="{{ route('admin.konten.doa.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Doa *</label>
                        <input type="text" name="judul" required placeholder="Contoh: Doa Sebelum Makan" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kategori *</label>
                        <input type="text" name="kategori" required placeholder="Contoh: Harian, Ibadah, Khusus" list="doa_categories"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <datalist id="doa_categories">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Teks Arab *</label>
                        <textarea name="teks_arab" required rows="3" dir="rtl" placeholder="الَّلهُمَّ..." 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-lg font-semibold arabic-text text-right focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Transliterasi *</label>
                        <textarea name="transliterasi" required rows="2" placeholder="Allahumma..." 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Terjemahan *</label>
                        <textarea name="terjemahan" required rows="3" placeholder="Artinya: Ya Allah..." 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Urutan Tampilan</label>
                            <input type="number" name="urutan" min="1" placeholder="Optional"
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
                        Simpan Doa
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
                Edit Doa
            </h3>
            
            <form :action="'{{ url('/admin/konten/doa') }}/' + editDoa.id" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Doa *</label>
                        <input type="text" name="judul" required x-model="editDoa.judul"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kategori *</label>
                        <input type="text" name="kategori" required x-model="editDoa.kategori" list="doa_categories_edit"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        <datalist id="doa_categories_edit">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Teks Arab *</label>
                        <textarea name="teks_arab" required rows="3" dir="rtl" x-model="editDoa.teks_arab"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-lg font-semibold arabic-text text-right focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Transliterasi *</label>
                        <textarea name="transliterasi" required rows="2" x-model="editDoa.transliterasi"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Terjemahan *</label>
                        <textarea name="terjemahan" required rows="3" x-model="editDoa.terjemahan"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Urutan Tampilan</label>
                            <input type="number" name="urutan" min="1" x-model="editDoa.urutan"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="flex items-center pt-6">
                            <input type="checkbox" id="is_active_edit" name="is_active" value="1" x-model="editDoa.is_active"
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
