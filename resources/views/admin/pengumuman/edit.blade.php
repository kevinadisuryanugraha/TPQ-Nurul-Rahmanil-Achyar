@extends('layouts.admin')

@section('title', 'Edit Pengumuman')
@section('page_title', 'Edit Pengumuman')

@section('content')
<!-- Include Quill stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        min-height: 200px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        line-height: 1.6;
    }
    .ql-toolbar.ql-snow {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        border-color: #e5e7eb;
        background-color: #f9fafb;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        border-color: #e5e7eb;
    }
</style>

<div class="max-w-3xl mx-auto" x-data="{ targetSemua: '{{ $pengumuman->target_semua ? '1' : '0' }}' }">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.pengumuman.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Pengumuman</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" id="pengumuman-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Title Field -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Pengumuman *</label>
                    <input type="text" name="judul" required value="{{ old('judul', $pengumuman->judul) }}" placeholder="Contoh: Pengumuman Libur Hari Raya Idul Fitri"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('judul') border-rose-500 @enderror">
                    @error('judul')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Target Group -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Target Penerima *</label>
                    <div class="flex items-center space-x-6 mt-2 mb-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="target_semua" value="1" x-model="targetSemua"
                                class="h-4.5 w-4.5 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Semua Santri</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="target_semua" value="0" x-model="targetSemua"
                                class="h-4.5 w-4.5 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Target Level Tertentu</span>
                        </label>
                    </div>

                    <!-- Level Selector (Dynamic show/hide) -->
                    <div x-show="targetSemua === '0'" x-transition class="mt-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Pilih Target Level *</label>
                        <select name="level_target_id" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">-- Pilih Level --</option>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl->id }}" {{ old('level_target_id', $pengumuman->level_target_id) == $lvl->id ? 'selected' : '' }}>{{ $lvl->nama }}</option>
                            @endforeach
                        </select>
                        @error('level_target_id')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Rich text content -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Isi Pengumuman *</label>
                    <div id="editor">
                        {!! old('isi', $pengumuman->isi) !!}
                    </div>
                    <input type="hidden" name="isi" id="isi-input" value="{{ old('isi', $pengumuman->isi) }}">
                    @error('isi')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Active Dates scheduling -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tanggal Mulai Tampil *</label>
                        <input type="date" name="tanggal_mulai" required value="{{ old('tanggal_mulai', $pengumuman->tanggal_mulai) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('tanggal_mulai')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tanggal Berakhir Tampil (Optional)</label>
                        <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir', $pengumuman->tanggal_berakhir) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <span class="text-[10px] text-gray-400 mt-1 block">Biarkan kosong agar tampil selamanya</span>
                        @error('tanggal_berakhir')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status Publish -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Status Publikasi *</label>
                    <div class="flex items-center space-x-6 mt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="published" required {{ old('status', $pengumuman->status) == 'published' ? 'checked' : '' }}
                                class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Publikasikan Langsung (Published)</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="draft" required {{ old('status', $pengumuman->status) == 'draft' ? 'checked' : '' }}
                                class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Simpan sebagai Draft</span>
                        </label>
                    </div>
                    @error('status')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-3">
                <a href="{{ route('admin.pengumuman.index') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<script>
    // Initialize Quill Editor
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis isi pengumuman di sini...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    // Copy editor html to input before form submit
    const form = document.getElementById('pengumuman-form');
    form.addEventListener('submit', function(e) {
        const contentInput = document.getElementById('isi-input');
        contentInput.value = quill.root.innerHTML;
        
        // Validation check
        if (quill.getText().trim() === '') {
            e.preventDefault();
            alert('Isi pengumuman wajib diisi!');
        }
    });
</script>
@endsection
