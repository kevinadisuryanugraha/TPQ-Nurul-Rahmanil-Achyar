@extends('layouts.public')

@section('title', 'Pendaftaran Santri Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 sm:py-16">
    
    <!-- Title / Heading -->
    <div class="text-center max-w-xl mx-auto mb-10 space-y-3">
        <span class="text-xs font-bold text-amber-600 uppercase tracking-widest block">📝 Form Pendaftaran</span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-emerald-950 tracking-tight">Pendaftaran Santri Baru (PSB)</h1>
        <p class="text-xs text-gray-500 font-light">Lengkapi formulir di bawah ini dengan data yang valid. Data yang dikirimkan akan ditinjau oleh Ustadz/Admin untuk pembuatan akun santri.</p>
    </div>

    <!-- Toast Success / Error Notifications -->
    @if (session('error'))
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-sm text-xs text-rose-800 flex items-center space-x-3 animate-pulse">
            <i class="fa-solid fa-circle-xmark text-rose-500 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Form container -->
    <div class="bg-white rounded-3xl border border-emerald-50/80 shadow-md p-6 sm:p-10 overflow-hidden relative" x-data="{ pernahMengaji: '{{ old('pernah_mengaji', 'tidak') }}' }">
        <!-- Brand accent line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-800 to-amber-500"></div>

        <form action="{{ route('daftar.store') }}" method="POST" id="form-pendaftaran" class="space-y-8">
            @csrf

            <!-- Security Anti-Spam Honeypot Field -->
            <div style="display:none; overflow:hidden; width:0; height:0;" class="opacity-0 pointer-events-none">
                <label for="website_url">Website URL (Mohon jangan diisi)</label>
                <input type="text" name="website_url" id="website_url" autocomplete="off">
            </div>

            <!-- Security reCAPTCHA Token Hidden Input -->
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            <!-- Section 1: Identitas Calon Santri -->
            <div class="space-y-5">
                <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-2"><i class="fa-solid fa-id-card mr-2"></i> Identitas Calon Santri</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-gray-700 mb-2">Nama Lengkap Santri <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap santri..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-xs">
                        @error('nama_lengkap')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <div class="flex items-center space-x-6 h-10">
                            <label class="inline-flex items-center text-xs font-medium text-gray-700 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="L" required {{ old('jenis_kelamin') === 'L' ? 'checked' : '' }}
                                       class="form-radio text-emerald-600 focus:ring-emerald-500 w-4 h-4 border-gray-300">
                                <span class="ml-2">Laki-laki</span>
                            </label>
                            <label class="inline-flex items-center text-xs font-medium text-gray-700 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="P" required {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }}
                                       class="form-radio text-emerald-600 focus:ring-emerald-500 w-4 h-4 border-gray-300">
                                <span class="ml-2">Perempuan</span>
                            </label>
                        </div>
                        @error('jenis_kelamin')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-xs font-bold text-gray-700 mb-2">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Contoh: Jakarta"
                               class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-xs">
                        @error('tempat_lahir')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-bold text-gray-700 mb-2">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" required value="{{ old('tanggal_lahir') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-xs">
                        @error('tanggal_lahir')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Informasi Wali / Kontak -->
            <div class="space-y-5">
                <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-2"><i class="fa-solid fa-users-rectangle mr-2"></i> Orang Tua / Wali</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Nama Wali -->
                    <div>
                        <label for="nama_orang_tua" class="block text-xs font-bold text-gray-700 mb-2">Nama Orang Tua / Wali <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_orang_tua" id="nama_orang_tua" required value="{{ old('nama_orang_tua') }}" placeholder="Nama Bapak / Ibu wali..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-xs">
                        @error('nama_orang_tua')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak HP/WA -->
                    <div>
                        <label for="no_wa" class="block text-xs font-bold text-gray-700 mb-2">No. WhatsApp Aktif <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_wa" id="no_wa" required value="{{ old('no_wa') }}" placeholder="Contoh: 08123456789"
                               class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-xs">
                        <span class="text-[9px] text-gray-400 block mt-1"><i class="fa-solid fa-circle-info"></i> Format nomor HP Indonesia (Contoh: 0812xxxxxx / 62812xxxxxx).</span>
                        @error('no_wa')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-xs font-bold text-gray-700 mb-2">Alamat Rumah Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" id="alamat" rows="3" required placeholder="Jl. Mawar No. 12, RT 01/02, Kebayoran..."
                              class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-xs">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Section 3: Riwayat Mengaji -->
            <div class="space-y-5">
                <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-2"><i class="fa-solid fa-book-quran mr-2"></i> Riwayat Belajar Mengaji</h3>
                
                <div class="space-y-4">
                    <!-- Pernah Mengaji? -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2.5">Pernah Belajar Mengaji Sebelumnya? <span class="text-rose-500">*</span></label>
                        <div class="flex items-center space-x-6 h-10">
                            <label class="inline-flex items-center text-xs font-medium text-gray-700 cursor-pointer">
                                <input type="radio" name="pernah_mengaji" value="ya" x-model="pernahMengaji"
                                       class="form-radio text-emerald-600 focus:ring-emerald-500 w-4 h-4 border-gray-300">
                                <span class="ml-2">Sudah Pernah</span>
                            </label>
                            <label class="inline-flex items-center text-xs font-medium text-gray-700 cursor-pointer">
                                <input type="radio" name="pernah_mengaji" value="tidak" x-model="pernahMengaji"
                                       class="form-radio text-emerald-600 focus:ring-emerald-500 w-4 h-4 border-gray-300">
                                <span class="ml-2">Belum Pernah (Mulai dari Nol/Dasar)</span>
                            </label>
                        </div>
                        @error('pernah_mengaji')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Level Mengaji Terakhir (Shown Conditionally) -->
                    <div x-show="pernahMengaji === 'ya'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         style="display: none;">
                        <label for="level_mengaji_sebelumnya" class="block text-xs font-bold text-emerald-950 mb-2">Sampai Level Apa / Halaman Keberapa? <span class="text-rose-500">*</span></label>
                        <input type="text" name="level_mengaji_sebelumnya" id="level_mengaji_sebelumnya" value="{{ old('level_mengaji_sebelumnya') }}" placeholder="Contoh: Iqra 4 halaman 12, atau Al-Qur'an Juz 1..."
                               class="w-full px-4 py-3 border border-emerald-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-emerald-900/20 text-xs bg-emerald-50/20">
                        @error('level_mengaji_sebelumnya')
                            <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Catatan Tambahan -->
            <div class="space-y-5">
                <h3 class="text-xs font-bold text-emerald-800 uppercase tracking-wider border-b border-gray-100 pb-2"><i class="fa-solid fa-note-sticky mr-2"></i> Informasi Tambahan</h3>
                <div>
                    <label for="catatan_tambahan" class="block text-xs font-bold text-gray-700 mb-2">Pertanyaan / Catatan Khusus (Opsional)</label>
                    <textarea name="catatan_tambahan" id="catatan_tambahan" rows="3" placeholder="Tuliskan catatan medis khusus, alergi, atau hal-hal penting lainnya di sini..."
                              class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition placeholder-gray-300 text-xs">{{ old('catatan_tambahan') }}</textarea>
                    @error('catatan_tambahan')
                        <p class="text-[10px] text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
                <button type="submit" id="btn-submit"
                        class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-extrabold rounded-2xl shadow-md hover:shadow-lg transition active:scale-[0.98] text-xs uppercase tracking-wider">
                    Kirim Pendaftaran <i class="fa-solid fa-paper-plane ml-2"></i>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
@if($recaptchaSiteKey)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
    <script>
        document.getElementById('form-pendaftaran').addEventListener('submit', function(e) {
            e.preventDefault();
            let form = this;
            
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ $recaptchaSiteKey }}', {action: 'pendaftaran_santri'}).then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    form.submit();
                });
            });
        });
    </script>
@endif
@endsection
