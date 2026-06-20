@extends('layouts.admin')

@section('title', 'Detail Panduan Praktik')
@section('page_title', 'Detail Panduan Praktik')

@section('content')
<div x-data="{ 
    isAddLangkahOpen: false, 
    isEditLangkahOpen: false,
    editLangkah: { id: '', nomor_urut: '', judul_langkah: '', deskripsi: '', gambar: '' }
}">
    <!-- Back & Action Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="{{ route('admin.konten.panduan.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Panduan</span>
        </a>

        <a href="{{ route('admin.konten.panduan.edit', $panduan->id) }}" 
            class="bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2.5 rounded-xl transition duration-200 shadow-sm flex items-center justify-center space-x-1.5 text-sm">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Edit Metadata Panduan</span>
        </a>
    </div>

    <!-- Guide Summary Section -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 sm:p-8 flex flex-col md:flex-row gap-6">
            <!-- Cover image -->
            <div class="w-full md:w-64 h-40 rounded-xl overflow-hidden bg-emerald-950 border border-gray-200 shrink-0">
                @if($panduan->cover_image)
                    <img src="{{ $panduan->cover_image }}" alt="{{ $panduan->judul }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-white">
                        <i class="fa-solid fa-compass text-4xl text-amber-400 mb-1"></i>
                        <span class="text-xs font-bold uppercase tracking-wider opacity-80">{{ $panduan->jenis_praktik }}</span>
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="flex-1 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="bg-emerald-50 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full border border-emerald-100 uppercase tracking-wide">
                        {{ $panduan->jenis_praktik }}
                    </span>
                    @if($panduan->status == 'published')
                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded-full">Published</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-0.5 rounded-full">Draft</span>
                    @endif
                    <span class="text-xs bg-gray-50 text-gray-700 font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">
                        Target: {{ $panduan->levelTarget->nama ?? 'Semua Level' }}
                    </span>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 leading-tight">{{ $panduan->judul }}</h2>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $panduan->deskripsi }}</p>
            </div>
        </div>
    </div>

    <!-- Steps Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-gray-900 text-lg flex items-center">
                <i class="fa-solid fa-list-ol text-emerald-600 mr-2"></i>
                Langkah-Langkah Panduan
            </h3>

            <button @click="isAddLangkahOpen = true"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl transition text-sm flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Langkah</span>
            </button>
        </div>

        @if($errors->any())
            <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-sm text-sm text-rose-800">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Steps List -->
        <div class="space-y-4">
            @forelse($panduan->langkahs as $langkah)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex flex-col sm:flex-row gap-5 items-start">
                    <!-- Step Number Indicator -->
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 font-extrabold flex items-center justify-center shrink-0 border border-emerald-200 text-lg shadow-sm">
                        {{ $langkah->nomor_urut }}
                    </div>

                    <!-- Step Illustration -->
                    <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-200 shrink-0 bg-gray-50 flex items-center justify-center">
                        @if($langkah->gambar)
                            <img src="{{ $langkah->gambar }}" alt="Langkah {{ $langkah->nomor_urut }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-image text-2xl text-gray-300"></i>
                        @endif
                    </div>

                    <!-- Step Content -->
                    <div class="flex-1 space-y-1.5">
                        <h4 class="font-bold text-gray-900 text-base leading-snug">{{ $langkah->judul_langkah }}</h4>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $langkah->deskripsi }}</p>
                    </div>

                    <!-- Step Actions -->
                    <div class="flex space-x-1 self-end sm:self-start">
                        <button 
                            @click="
                                editLangkah = { 
                                    id: '{{ $langkah->id }}', 
                                    nomor_urut: '{{ $langkah->nomor_urut }}', 
                                    judul_langkah: '{{ addslashes($langkah->judul_langkah) }}', 
                                    deskripsi: '{{ addslashes($langkah->deskripsi) }}', 
                                    gambar: '{{ $langkah->gambar }}' 
                                }; 
                                isEditLangkahOpen = true;
                            "
                            class="text-amber-600 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"
                            title="Edit Langkah">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <form action="{{ route('admin.konten.langkah.destroy', $langkah->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus langkah ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 p-2 rounded-lg transition" title="Hapus Langkah">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-500 shadow-sm">
                    <i class="fa-solid fa-list-check text-4xl text-gray-300 mb-3"></i>
                    <p class="text-sm mb-3">Belum ada langkah yang ditambahkan ke panduan ini.</p>
                    <button @click="isAddLangkahOpen = true"
                        class="bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold px-4 py-2 rounded-xl transition text-xs border border-emerald-200 inline-flex items-center space-x-1 shadow-sm">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Langkah Pertama</span>
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ================= ADD LANGKAH MODAL ================= -->
    <div x-show="isAddLangkahOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 overflow-y-auto" style="display: none;">
        <div @click.away="isAddLangkahOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative my-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fa-solid fa-plus text-emerald-600 mr-2"></i>
                Tambah Langkah Baru
            </h3>
            
            <form action="{{ route('admin.konten.langkah.store', $panduan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nomor Urut *</label>
                            <input type="number" name="nomor_urut" required min="1" 
                                value="{{ $panduan->langkahs->max('nomor_urut') ? $panduan->langkahs->max('nomor_urut') + 1 : 1 }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Langkah *</label>
                            <input type="text" name="judul_langkah" required placeholder="Contoh: Membasuh Kedua Telapak Tangan" 
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Deskripsi Langkah *</label>
                        <textarea name="deskripsi" required rows="3" placeholder="Jelaskan detail gerakan atau tata caranya..." 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Ilustrasi Gambar (Optional)</label>
                        <div class="border border-gray-300 rounded-xl p-3 bg-gray-50 flex items-center justify-between">
                            <div>
                                <input type="file" name="gambar" accept="image/*" id="step-img-upload" class="hidden" onchange="previewStepImage(this, 'add')">
                                <button type="button" onclick="document.getElementById('step-img-upload').click()"
                                    class="bg-white border border-gray-300 text-gray-700 font-semibold px-3 py-1.5 rounded-lg text-xs hover:bg-gray-50 transition shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up mr-1"></i>Pilih Gambar
                                </button>
                                <span class="text-[9px] text-gray-500 block mt-1">Maks 2MB</span>
                            </div>
                            <div id="add-step-preview-container" class="hidden">
                                <img id="add-step-preview" src="#" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-gray-200">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="isAddLangkahOpen = false" 
                        class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                        Simpan Langkah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= EDIT LANGKAH MODAL ================= -->
    <div x-show="isEditLangkahOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 overflow-y-auto" style="display: none;">
        <div @click.away="isEditLangkahOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative my-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fa-solid fa-pen-to-square text-amber-600 mr-2"></i>
                Edit Langkah Panduan
            </h3>
            
            <form :action="'{{ url('/admin/konten/langkah') }}/' + editLangkah.id" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nomor Urut *</label>
                            <input type="number" name="nomor_urut" required min="1" x-model="editLangkah.nomor_urut"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Langkah *</label>
                            <input type="text" name="judul_langkah" required x-model="editLangkah.judul_langkah"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Deskripsi Langkah *</label>
                        <textarea name="deskripsi" required rows="3" x-model="editLangkah.deskripsi"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Ilustrasi Gambar (Optional)</label>
                        <div class="border border-gray-300 rounded-xl p-3 bg-gray-50 flex items-center justify-between">
                            <div>
                                <input type="file" name="gambar" accept="image/*" id="step-img-edit-upload" class="hidden" onchange="previewStepImage(this, 'edit')">
                                <button type="button" onclick="document.getElementById('step-img-edit-upload').click()"
                                    class="bg-white border border-gray-300 text-gray-700 font-semibold px-3 py-1.5 rounded-lg text-xs hover:bg-gray-50 transition shadow-sm">
                                    <i class="fa-solid fa-cloud-arrow-up mr-1"></i>Ganti Gambar
                                </button>
                                <span class="text-[9px] text-gray-500 block mt-1">Maks 2MB</span>
                            </div>
                            <div id="edit-step-preview-container" class="block">
                                <img id="edit-step-preview" :src="editLangkah.gambar ? editLangkah.gambar : '#'" alt="Preview" 
                                    :class="editLangkah.gambar ? 'w-14 h-14 object-cover rounded-lg border border-gray-200' : 'hidden'">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" @click="isEditLangkahOpen = false" 
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

<script>
    // Step Image Preview Helper
    function previewStepImage(input, mode) {
        const previewContainerId = mode === 'add' ? 'add-step-preview-container' : 'edit-step-preview-container';
        const previewImgId = mode === 'add' ? 'add-step-preview' : 'edit-step-preview';
        
        const previewContainer = document.getElementById(previewContainerId);
        const preview = document.getElementById(previewImgId);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                preview.classList.add('w-14', 'h-14', 'object-cover', 'rounded-lg', 'border', 'border-gray-200');
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
