@extends('layouts.admin')

@section('title', 'Pendaftaran Murid Baru')
@section('page_title', 'PSB - Pendaftaran Murid Baru')

@section('content')
<div class="space-y-6">
    
    <!-- Title & Search Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-800">Daftar Pendaftar PSB</h3>
                <p class="text-xs text-gray-500 mt-1">Review berkas pendaftaran santri baru, update status, dan buat akun santri secara otomatis.</p>
            </div>
        </div>

        <form action="{{ route('admin.landing.pendaftaran.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <!-- Search field -->
            <div>
                <label for="search" class="block text-xs font-semibold text-gray-700 mb-1.5">Pencarian</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama santri / wali..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs bg-white">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-xs"><i class="fa-solid fa-magnifying-glass"></i></span>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-semibold text-gray-700 mb-1.5">Filter Status</label>
                <select name="status" id="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs bg-white">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="dihubungi" {{ request('status') === 'dihubungi' ? 'selected' : '' }}>Dihubungi</option>
                    <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Submit / Reset -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-xl transition text-xs flex-1">
                    Cari & Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.landing.pendaftaran.index') }}" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-emerald-950 font-bold rounded-xl transition text-xs">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if($registrations->isEmpty())
            <div class="text-center py-16 p-8">
                <i class="fa-solid fa-id-card-clip text-gray-300 text-5xl mb-4 block"></i>
                <p class="text-xs text-gray-400 font-light">Tidak ditemukan data pendaftaran santri baru.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="bg-stone-50 border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider">
                            <th class="p-4">Tanggal Daftar</th>
                            <th class="p-4">Nama Calon Santri</th>
                            <th class="p-4">Nama Orang Tua</th>
                            <th class="p-4">No. WhatsApp</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($registrations as $reg)
                            <tr class="hover:bg-stone-50/50 transition">
                                <td class="p-4 text-gray-500">
                                    {{ $reg->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="p-4 font-bold text-emerald-950 text-sm">
                                    {{ $reg->nama_lengkap }}
                                    <span class="text-[10px] text-gray-400 font-normal block mt-0.5">
                                        L/P: {{ $reg->jenis_kelamin }} | Lahir: {{ $reg->tanggal_lahir->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-700">
                                    {{ $reg->nama_orang_tua }}
                                </td>
                                <td class="p-4">
                                    @php
                                        // clean phone number for wa link
                                        $phone = preg_replace('/[^0-9]/', '', $reg->no_wa);
                                        if (str_starts_with($phone, '0')) {
                                            $phone = '62' . substr($phone, 1);
                                        }
                                        $waUrl = "https://wa.me/{$phone}?text=" . urlencode("Assalamu'alaikum Bapak/Ibu {$reg->nama_orang_tua}, kami dari Pengurus TPQ ingin mengonfirmasi pendaftaran atas nama {$reg->nama_lengkap}...");
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center text-emerald-700 hover:text-emerald-900 font-semibold hover:underline">
                                        <i class="fa-brands fa-whatsapp text-emerald-600 text-sm mr-1.5"></i> {{ $reg->no_wa }}
                                    </a>
                                </td>
                                <td class="p-4 text-center">
                                    @if($reg->status === 'baru')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-150">
                                            Baru
                                        </span>
                                    @elseif($reg->status === 'dihubungi')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            Dihubungi
                                        </span>
                                    @elseif($reg->status === 'diterima')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Diterima
                                        </span>
                                    @elseif($reg->status === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 text-stone-600 border border-stone-200">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.landing.pendaftaran.show', $reg->id) }}" class="px-3 py-1.5 bg-emerald-50 text-emerald-800 border border-emerald-100 hover:bg-emerald-100 rounded-lg text-[10px] font-bold transition flex items-center">
                                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Detail / Review
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
