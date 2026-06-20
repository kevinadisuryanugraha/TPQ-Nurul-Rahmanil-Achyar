@extends('layouts.admin')

@section('title', 'Edit Cerita Kisah')
@section('page_title', 'Edit Cerita Kisah')

@section('content')
<!-- Include Quill stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<style>
    .ql-editor {
        min-height: 250px;
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

<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.konten.cerita.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Cerita</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.konten.cerita.update', $cerita->id) }}" method="POST" enctype="multipart/form-data" id="cerita-form">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Title Field -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Judul Cerita *</label>
                    <input type="text" name="judul" required value="{{ old('judul', $cerita->judul) }}" placeholder="Contoh: Kisah Kejujuran Nabi Muhammad SAW"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('judul') border-rose-500 @enderror">
                    @error('judul')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Grid Inputs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Kategori *</label>
                        <select name="kategori" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="kisah_nabi" {{ old('kategori', $cerita->kategori) == 'kisah_nabi' ? 'selected' : '' }}>Kisah Nabi</option>
                            <option value="kisah_sahabat" {{ old('kategori', $cerita->kategori) == 'kisah_sahabat' ? 'selected' : '' }}>Kisah Sahabat</option>
                            <option value="islami_lainnya" {{ old('kategori', $cerita->kategori) == 'islami_lainnya' ? 'selected' : '' }}>Islami Lainnya</option>
                        </select>
                        @error('kategori')
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
                                <option value="{{ $lvl->id }}" {{ old('level_target_id', $cerita->level_target_id) == $lvl->id ? 'selected' : '' }}>{{ $lvl->nama }}</option>
                            @endforeach
                        </select>
                        @error('level_target_id')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Image/Thumbnail Upload -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Gambar Cover / Thumbnail (Optional)</label>
                    <div class="border border-gray-300 rounded-xl p-4 bg-gray-50 flex items-center justify-between">
                        <div>
                            <input type="file" name="thumbnail" accept="image/*" id="thumbnail-upload" class="hidden" onchange="previewImage(this)">
                            <button type="button" onclick="document.getElementById('thumbnail-upload').click()"
                                class="bg-white border border-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg text-xs hover:bg-gray-50 transition shadow-sm">
                                <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i>Ganti Gambar
                            </button>
                            <span class="text-[10px] text-gray-500 ml-2">PNG, JPG, WEBP maks 2MB</span>
                        </div>
                        <div id="image-preview-container" class="{{ $cerita->thumbnail ? '' : 'hidden' }}">
                            <img id="image-preview" src="{{ $cerita->thumbnail ?? '#' }}" alt="Preview" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>
                    @error('thumbnail')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Rich Editor Container -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Isi Konten Cerita *</label>
                    <div id="editor">
                        {!! old('konten', $cerita->konten) !!}
                    </div>
                    <input type="hidden" name="konten" id="konten-input" value="{{ old('konten', $cerita->konten) }}">
                    @error('konten')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status Publish -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Status Publikasi *</label>
                    <div class="flex items-center space-x-6 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="published" required {{ old('status', $cerita->status) == 'published' ? 'checked' : '' }}
                                class="h-4 w-4 border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700 font-semibold">Publikasikan Langsung (Published)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" required {{ old('status', $cerita->status) == 'draft' ? 'checked' : '' }}
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
                <a href="{{ route('admin.konten.cerita.index') }}" 
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
        placeholder: 'Tulis cerita kisah islami di sini...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['clean']
            ]
        }
    });

    // Copy editor html to input before form submit
    const form = document.getElementById('cerita-form');
    form.addEventListener('submit', function(e) {
        const contentInput = document.getElementById('konten-input');
        contentInput.value = quill.root.innerHTML;
        
        // If content is empty or contains just empty tags, prevent submit
        if (quill.getText().trim() === '') {
            e.preventDefault();
            alert('Isi konten cerita wajib diisi!');
        }
    });

    // Thumbnail Preview Helper
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
            // Do not hide preview if there is an existing thumbnail
            @if(!$cerita->thumbnail)
                previewContainer.classList.add('hidden');
            @endif
        }
    }
</script>
@endsection
