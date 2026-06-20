@extends('layouts.admin')

@section('title', 'Pengaturan Landing Page')
@section('page_title', 'CMS Landing Page - Pengaturan Konten')

@section('content')
<div class="max-w-4xl bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-white">
        <h3 class="text-lg font-bold text-gray-800">Ubah Konten Landing Page</h3>
        <p class="text-xs text-gray-500 mt-1">Kelola teks hero, visi & misi, keunggulan, jam kerja, peta lokasi, dan media sosial tanpa menyentuh kode program.</p>
    </div>

    <form action="{{ route('admin.landing.pengaturan.update') }}" method="POST" class="p-6 space-y-8">
        @csrf
        @method('PUT')

        <!-- 1. HERO SECTION -->
        <div class="space-y-4">
            <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-shapes mr-1.5"></i> Bagian Hero / Beranda</h4>
            
            <div class="space-y-4">
                <div>
                    <label for="hero_headline" class="block text-xs font-semibold text-gray-700 mb-2">Headline Utama <span class="text-rose-500">*</span></label>
                    <input type="text" name="hero_headline" id="hero_headline" required value="{{ old('hero_headline', $settings['hero_headline'] ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                    @error('hero_headline')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="hero_subheadline" class="block text-xs font-semibold text-gray-700 mb-2">Sub-headline / Deskripsi Pendukung <span class="text-rose-500">*</span></label>
                    <textarea name="hero_subheadline" id="hero_subheadline" rows="2" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">{{ old('hero_subheadline', $settings['hero_subheadline'] ?? '') }}</textarea>
                    @error('hero_subheadline')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 2. TENTANG KAMI, VISI, MISI -->
        <div class="space-y-4">
            <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-address-card mr-1.5"></i> Tentang Kami, Visi & Misi</h4>
            
            <div class="space-y-4">
                <div>
                    <label for="tentang_kami" class="block text-xs font-semibold text-gray-700 mb-2">Teks "Tentang Kami" <span class="text-rose-500">*</span></label>
                    <textarea name="tentang_kami" id="tentang_kami" rows="4" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">{{ old('tentang_kami', $settings['tentang_kami'] ?? '') }}</textarea>
                    @error('tentang_kami')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="visi" class="block text-xs font-semibold text-gray-700 mb-2">Pernyataan Visi <span class="text-rose-500">*</span></label>
                        <textarea name="visi" id="visi" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">{{ old('visi', $settings['visi'] ?? '') }}</textarea>
                        @error('visi')
                            <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="misi" class="block text-xs font-semibold text-gray-700 mb-2">Daftar Misi (Satu per baris) <span class="text-rose-500">*</span></label>
                        @php
                            $misiText = is_array($settings['misi'] ?? null) ? implode("\n", $settings['misi']) : '';
                        @endphp
                        <textarea name="misi" id="misi" rows="5" required placeholder="Contoh:&#10;Menyelenggarakan pembelajaran teratur&#10;Membina hafalan santri&#10;Menanamkan akhlak mulia"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">{{ old('misi', $misiText) }}</textarea>
                        <span class="text-[9px] text-gray-400 mt-1 block"><i class="fa-solid fa-circle-info"></i> Tekan Enter untuk memisahkan setiap poin misi.</span>
                        @error('misi')
                            <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. POIN KEUNGGULAN (4 CARDS) -->
        <div class="space-y-4">
            <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-star-and-crescent mr-1.5"></i> Poin Keunggulan TPQ (Maksimal 4 Poin)</h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @for ($i = 1; $i <= 4; $i++)
                    @php
                        $item = ($settings['poin_keunggulan'] ?? [])[$i - 1] ?? null;
                    @endphp
                    <div class="p-4 bg-stone-50 border border-gray-100 rounded-xl space-y-3">
                        <span class="text-[10px] font-bold text-amber-600 block uppercase">Keunggulan {{ $i }} @if($i == 1)<span class="text-rose-500">*</span>@endif</span>
                        <div>
                            <input type="text" name="keunggulan_title_{{ $i }}" placeholder="Judul Keunggulan (misal: Laporan Online)"
                                   value="{{ old('keunggulan_title_'.$i, $item['title'] ?? '') }}" @if($i == 1) required @endif
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        </div>
                        <div>
                            <textarea name="keunggulan_desc_{{ $i }}" rows="2" placeholder="Deskripsi singkat..."
                                      @if($i == 1) required @endif
                                      class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">{{ old('keunggulan_desc_'.$i, $item['desc'] ?? '') }}</textarea>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- 4. LOKASI, GOOGLE MAPS, OPERASIONAL -->
        <div class="space-y-4">
            <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-location-dot mr-1.5"></i> Lokasi & Jam Operasional</h4>
            
            <div class="space-y-4">
                <div>
                    <label for="alamat" class="block text-xs font-semibold text-gray-700 mb-2">Alamat Kantor Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" id="alamat" rows="2" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">{{ old('alamat', $settings['alamat'] ?? '') }}</textarea>
                    @error('alamat')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="maps_embed_url" class="block text-xs font-semibold text-gray-700 mb-2">Link Iframe Google Maps (Embed URL) <span class="text-rose-500">*</span></label>
                    <input type="text" name="maps_embed_url" id="maps_embed_url" required value="{{ old('maps_embed_url', $settings['maps_embed_url'] ?? '') }}"
                           placeholder="https://www.google.com/maps/embed?pb=..."
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                    <span class="text-[9px] text-gray-400 mt-1 block"><i class="fa-solid fa-circle-info"></i> Ambil dari Google Maps: Bagikan -> Sematkan Peta -> Copy link dari dalam atribut `src="..."`.</span>
                    @error('maps_embed_url')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="jam_operasional" class="block text-xs font-semibold text-gray-700 mb-2">Jam Operasional Pendaftaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="jam_operasional" id="jam_operasional" required value="{{ old('jam_operasional', $settings['jam_operasional'] ?? '') }}"
                               placeholder="Contoh: Senin - Jumat: 16.00 - 17.30 WIB"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                        @error('jam_operasional')
                            <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-700 mb-2">Alamat Email TPQ (Opsional)</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $settings['email'] ?? '') }}"
                               placeholder="Contoh: info@tpq.sch.id"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                        @error('email')
                            <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. KONTAK WA/TELPON & MEDIA SOSIAL -->
        <div class="space-y-4">
            <h4 class="text-sm font-bold text-emerald-800 border-b border-gray-100 pb-2"><i class="fa-solid fa-phone mr-1.5"></i> Kontak & Sosial Media</h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="no_wa" class="block text-xs font-semibold text-gray-700 mb-2">No. WhatsApp Resmi (Gunakan kode negara, misal: 62812xxx) <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_wa" id="no_wa" required value="{{ old('no_wa', $settings['no_wa'] ?? '') }}"
                           placeholder="Contoh: 628123456789"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                    @error('no_wa')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_telpon" class="block text-xs font-semibold text-gray-700 mb-2">No. Telepon Direct (Click-to-Call) <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_telpon" id="no_telpon" required value="{{ old('no_telpon', $settings['no_telpon'] ?? '') }}"
                           placeholder="Contoh: 0217201234"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                    @error('no_telpon')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="instagram_url" class="block text-xs font-semibold text-gray-700 mb-2">Link Profil Instagram (URL Lengkap)</label>
                    <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                           placeholder="https://instagram.com/akun-tpq"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                    @error('instagram_url')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="facebook_url" class="block text-xs font-semibold text-gray-700 mb-2">Link Fanpage Facebook (URL Lengkap)</label>
                    <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                           placeholder="https://facebook.com/akun-tpq"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                    @error('facebook_url')
                        <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 flex items-center justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-xs">
                Simpan Perubahan Konten
            </button>
        </div>

    </form>
</div>
@endsection
