@extends('layouts.public')

@section('title', 'Pendaftaran Santri Baru')

@section('content')
<div class="bg-stone-50 pattern-islamic-light min-h-screen">
<div class="max-w-3xl mx-auto px-4 py-12 sm:py-16">

    {{-- Page Header --}}
    <div class="text-center max-w-xl mx-auto mb-10 reveal">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-800 text-amber-400 text-xl mx-auto mb-5 shadow-md">
            <i class="fa-solid fa-file-pen"></i>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-emerald-950 tracking-tight mb-3">
            Pendaftaran Santri Baru
        </h1>
        <p class="text-base text-gray-500 leading-relaxed">
            Lengkapi formulir di bawah ini dengan data yang valid. Tim pengurus akan meninjau dan menghubungi Anda dalam 1×24 jam.
        </p>
    </div>

    {{-- Progress Steps --}}
    <div class="flex items-center justify-center gap-0 mb-10 reveal reveal-delay-1">
        @php
            $steps = [
                ['label' => 'Identitas', 'icon' => 'fa-id-card'],
                ['label' => 'Orang Tua', 'icon' => 'fa-users'],
                ['label' => 'Riwayat', 'icon' => 'fa-book-quran'],
                ['label' => 'Catatan', 'icon' => 'fa-note-sticky'],
            ];
        @endphp
        @foreach($steps as $i => $step)
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-9 h-9 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                        <i class="fa-solid {{ $step['icon'] }}"></i>
                    </div>
                    <span class="text-[10px] text-emerald-800 font-semibold mt-1.5 tracking-wide hidden sm:block">{{ $step['label'] }}</span>
                </div>
                @if($i < count($steps) - 1)
                    <div class="w-12 sm:w-20 h-px bg-emerald-200 mx-1 mb-4 sm:mb-0"></div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Error Toast --}}
    @if(session('error'))
        <div class="mb-8 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-sm text-rose-800 flex items-start gap-3 shadow-sm reveal">
            <i class="fa-solid fa-circle-xmark text-rose-500 text-lg mt-0.5 flex-shrink-0"></i>
            <span class="leading-relaxed">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-white rounded-3xl shadow-md border border-emerald-50/80 overflow-hidden reveal reveal-delay-2"
         x-data="{ pernahMengaji: '{{ old('pernah_mengaji', 'tidak') }}', submitting: false }">

        {{-- Brand accent bar --}}
        <div class="h-1.5 bg-gradient-to-r from-emerald-800 via-emerald-700 to-amber-500"></div>

        <form action="{{ route('daftar.store') }}" method="POST" id="form-pendaftaran"
              @submit="submitting = true"
              class="p-6 sm:p-10 space-y-10">
            @csrf

            {{-- Honeypot anti-spam --}}
            <div style="display:none; overflow:hidden; width:0; height:0;" aria-hidden="true">
                <label for="website_url">Website URL (jangan diisi)</label>
                <input type="text" name="website_url" id="website_url" autocomplete="off" tabindex="-1">
            </div>

            {{-- reCAPTCHA hidden --}}
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">


            {{-- ────────────────────────────────────────────────────────────
                 SECTION 1: Identitas Calon Santri
            ──────────────────────────────────────────────────────────────── --}}
            <fieldset>
                <legend class="flex items-center gap-2.5 w-full pb-4 border-b border-gray-100 mb-6">
                    <span class="w-8 h-8 rounded-xl bg-emerald-800 text-white flex items-center justify-center text-xs flex-shrink-0">
                        <i class="fa-solid fa-id-card"></i>
                    </span>
                    <span class="text-sm font-bold text-emerald-950 uppercase tracking-wider">Identitas Calon Santri</span>
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Lengkap Santri <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required
                               value="{{ old('nama_lengkap') }}"
                               placeholder="Masukkan nama lengkap santri..."
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none transition-all duration-200">
                        @error('nama_lengkap')
                            <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-5">
                            <label class="inline-flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="jenis_kelamin" value="L" required
                                       {{ old('jenis_kelamin') === 'L' ? 'checked' : '' }}
                                       class="w-4 h-4 text-emerald-700 border-gray-300 focus:ring-emerald-600 cursor-pointer">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-800 transition">Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="jenis_kelamin" value="P" required
                                       {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }}
                                       class="w-4 h-4 text-emerald-700 border-gray-300 focus:ring-emerald-600 cursor-pointer">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-emerald-800 transition">Perempuan</span>
                            </label>
                        </div>
                        @error('jenis_kelamin')
                            <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">

                    {{-- Tempat Lahir --}}
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir"
                               value="{{ old('tempat_lahir') }}" placeholder="Contoh: Jakarta"
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none transition-all duration-200">
                        @error('tempat_lahir')
                            <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-bold text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" required
                               value="{{ old('tanggal_lahir') }}"
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm text-gray-800 focus:outline-none transition-all duration-200">
                        @error('tanggal_lahir')
                            <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </fieldset>


            {{-- ────────────────────────────────────────────────────────────
                 SECTION 2: Orang Tua / Wali
            ──────────────────────────────────────────────────────────────── --}}
            <fieldset>
                <legend class="flex items-center gap-2.5 w-full pb-4 border-b border-gray-100 mb-6">
                    <span class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center text-xs flex-shrink-0">
                        <i class="fa-solid fa-users"></i>
                    </span>
                    <span class="text-sm font-bold text-emerald-950 uppercase tracking-wider">Orang Tua / Wali</span>
                </legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Nama Wali --}}
                    <div>
                        <label for="nama_orang_tua" class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Orang Tua / Wali <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_orang_tua" id="nama_orang_tua" required
                               value="{{ old('nama_orang_tua') }}" placeholder="Nama Bapak / Ibu wali..."
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none transition-all duration-200">
                        @error('nama_orang_tua')
                            <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- No WhatsApp --}}
                    <div>
                        <label for="no_wa" class="block text-sm font-bold text-gray-700 mb-2">
                            No. WhatsApp Aktif <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="no_wa" id="no_wa" required
                               value="{{ old('no_wa') }}" placeholder="Contoh: 08123456789"
                               inputmode="tel" autocomplete="tel"
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none transition-all duration-200">
                        <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info text-emerald-500"></i>
                            Format nomor HP Indonesia (contoh: 0812xxxxxx atau 62812xxxxxx).
                        </p>
                        @error('no_wa')
                            <p class="text-xs text-rose-600 mt-1 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="mt-6">
                    <label for="alamat" class="block text-sm font-bold text-gray-700 mb-2">
                        Alamat Rumah Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="alamat" id="alamat" rows="3" required
                              placeholder="Jl. Mawar No. 12, RT 01/02, Kebayoran Baru..."
                              class="input-field w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none transition-all duration-200 resize-none">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </fieldset>


            {{-- ────────────────────────────────────────────────────────────
                 SECTION 3: Riwayat Belajar Mengaji
            ──────────────────────────────────────────────────────────────── --}}
            <fieldset>
                <legend class="flex items-center gap-2.5 w-full pb-4 border-b border-gray-100 mb-6">
                    <span class="w-8 h-8 rounded-xl bg-emerald-800 text-white flex items-center justify-center text-xs flex-shrink-0">
                        <i class="fa-solid fa-book-quran"></i>
                    </span>
                    <span class="text-sm font-bold text-emerald-950 uppercase tracking-wider">Riwayat Belajar Mengaji</span>
                </legend>

                {{-- Pernah Mengaji? --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        Pernah Belajar Mengaji Sebelumnya? <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="flex-1 flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                               :class="pernahMengaji === 'ya' ? 'border-emerald-600 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200'">
                            <input type="radio" name="pernah_mengaji" value="ya" x-model="pernahMengaji"
                                   class="w-4 h-4 text-emerald-700 border-gray-300 focus:ring-emerald-600 cursor-pointer">
                            <div>
                                <span class="text-sm font-bold text-gray-800 block">Sudah Pernah</span>
                                <span class="text-xs text-gray-400">Pernah belajar mengaji sebelumnya</span>
                            </div>
                        </label>
                        <label class="flex-1 flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                               :class="pernahMengaji === 'tidak' ? 'border-emerald-600 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200'">
                            <input type="radio" name="pernah_mengaji" value="tidak" x-model="pernahMengaji"
                                   class="w-4 h-4 text-emerald-700 border-gray-300 focus:ring-emerald-600 cursor-pointer">
                            <div>
                                <span class="text-sm font-bold text-gray-800 block">Belum Pernah</span>
                                <span class="text-xs text-gray-400">Mulai dari nol / dasar</span>
                            </div>
                        </label>
                    </div>
                    @error('pernah_mengaji')
                        <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Level Mengaji (conditional) --}}
                <div x-show="pernahMengaji === 'ya'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     style="display: none;">
                    <label for="level_mengaji_sebelumnya" class="block text-sm font-bold text-gray-700 mb-2">
                        Sampai Level / Halaman Berapa? <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="level_mengaji_sebelumnya" id="level_mengaji_sebelumnya"
                           value="{{ old('level_mengaji_sebelumnya') }}"
                           placeholder="Contoh: Iqra 4 halaman 12, atau Al-Qur'an Juz 1..."
                           class="input-field w-full px-4 py-3 border border-emerald-200 bg-emerald-50/30 rounded-2xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none transition-all duration-200">
                    @error('level_mengaji_sebelumnya')
                        <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </fieldset>


            {{-- ────────────────────────────────────────────────────────────
                 SECTION 4: Informasi Tambahan
            ──────────────────────────────────────────────────────────────── --}}
            <fieldset>
                <legend class="flex items-center gap-2.5 w-full pb-4 border-b border-gray-100 mb-6">
                    <span class="w-8 h-8 rounded-xl bg-amber-600 text-white flex items-center justify-center text-xs flex-shrink-0">
                        <i class="fa-solid fa-note-sticky"></i>
                    </span>
                    <span class="text-sm font-bold text-emerald-950 uppercase tracking-wider">Informasi Tambahan</span>
                </legend>

                <div>
                    <label for="catatan_tambahan" class="block text-sm font-bold text-gray-700 mb-2">
                        Pertanyaan / Catatan Khusus
                        <span class="text-xs text-gray-400 font-normal ml-1">(Opsional)</span>
                    </label>
                    <textarea name="catatan_tambahan" id="catatan_tambahan" rows="4"
                              placeholder="Tuliskan catatan medis khusus, alergi, atau hal-hal penting lainnya di sini..."
                              class="input-field w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm text-gray-800 placeholder-gray-300 focus:outline-none transition-all duration-200 resize-none">{{ old('catatan_tambahan') }}</textarea>
                    @error('catatan_tambahan')
                        <p class="text-xs text-rose-600 mt-1.5 font-semibold flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </fieldset>


            {{-- Submit Footer --}}
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-400 text-center sm:text-left leading-relaxed">
                    <i class="fa-solid fa-lock text-emerald-500 mr-1"></i>
                    Data Anda aman. Formulir ini dilindungi reCAPTCHA Google.
                </p>

                <button type="submit" id="btn-submit"
                        :disabled="submitting"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 disabled:from-emerald-900 disabled:to-emerald-900 disabled:opacity-70 text-white font-extrabold rounded-2xl shadow-md shadow-emerald-900/20 hover:shadow-lg transition-all duration-200 active:scale-[0.98] cursor-pointer text-sm uppercase tracking-wider">
                    <span x-show="!submitting">Kirim Pendaftaran</span>
                    <span x-show="submitting" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Mengirim...
                    </span>
                    <i class="fa-solid fa-paper-plane" x-show="!submitting"></i>
                </button>
            </div>

        </form>
    </div>

</div>
</div>
@endsection

@section('scripts')
@if($recaptchaSiteKey)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
    <script>
        document.getElementById('form-pendaftaran').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            grecaptcha.ready(function () {
                grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'pendaftaran_santri' })
                    .then(function (token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        form.submit();
                    });
            });
        });
    </script>
@endif
@endsection
