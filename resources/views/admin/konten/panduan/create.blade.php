@extends('layouts.admin')

@section('title', 'Tambah Panduan Praktik')
@section('page_title', 'Tambah Panduan Praktik Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.konten.panduan.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Panduan</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.konten.panduan.store') }}" method="POST" enctype="multipart/form-data" id="panduan-form">
            @csrf
            
            <div class="space-y-6">
                <!-- Title Field -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Panduan *</label>
                    <input type="text" name="judul" required value="{{ old('judul') }}" placeholder="Contoh: Panduan Praktik Wudhu Sesuai Sunnah"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('judul') border-rose-500 @enderror">
                    @error('judul')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Grid Inputs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Jenis Praktik -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jenis Praktik / Fiqh *</label>
                        <input type="text" name="jenis_praktik" required value="{{ old('jenis_praktik') }}" placeholder="Contoh: Wudhu, Shalat, Tayammum" list="jenis_list"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <datalist id="jenis_list">
                            <option value="Wudhu">
                            <option value="Shalat">
                            <option value="Tayammum">
                            <option value="Doa & Adab">
                        </datalist>
                        @error('jenis_praktik')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Level Target -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Target Santri (Minimal Level)</label>
                        <select name="level_target_id" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Semua Level (Rekomendasi untuk semua)</option>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl->id }}" {{ old('level_target_id') == $lvl->id ? 'selected' : '' }}>{{ $lvl->nama }}</option>
                            @endforeach
                        </select>
                        @error('level_target_id')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Description Field -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Deskripsi Singkat *</label>
                    <textarea name="deskripsi" required rows="3" placeholder="Jelaskan mengenai panduan praktik ini..."
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('deskripsi') border-rose-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Gambar Cover Panduan (Optional)</label>
                    <div class="border border-gray-300 rounded-xl p-4 bg-gray-50 flex items-center justify-between">
                        <div>
                            <input type="file" name="cover_image" accept="image/*" id="cover-upload" class="hidden" onchange="previewImage(this)">
                            <button type="button" onclick="document.getElementById('cover-upload').click()"
                                class="bg-white border border-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-xs hover:bg-gray-50 transition shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i>Pilih Gambar
                            </button>
                            <span class="text-[10px] text-gray-500 ml-2">PNG, JPG, WEBP maks 2MB</span>
                        </div>
                        <div id="image-preview-container" class="hidden">
                            <img id="image-preview" src="#" alt="Preview" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>
                    @error('cover_image')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status Publish -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Status Publikasi *</label>
                    <div class="flex items-center space-x-6 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="published" required checked
                                class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Publikasikan Langsung (Published)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" required
                                class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Simpan sebagai Draft</span>
                        </label>
                    </div>
                    @error('status')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit / Actions -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-3">
                <a href="{{ route('admin.konten.panduan.index') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                    Simpan & Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Cover Image Preview Helper
    function previewImage(input) {
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.classList.add('hidden');
        }
    }
</script>
@endsection
