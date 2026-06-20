@extends('layouts.superadmin')

@section('title', 'Kelola Pengurus')
@section('page_title', 'Daftar Pengurus TPQ')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Kelola akun ustadz/ustadzah dan administrator yang mengoperasikan sistem.</p>
        <a href="{{ route('superadmin.admins.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-sm transition text-sm flex items-center space-x-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengurus</span>
        </a>
    </div>

    <!-- Admins Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($admins->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <i class="fa-regular fa-user text-4xl mb-3 block"></i>
                    <p class="text-sm">Belum ada data pengurus terdaftar.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                            <th class="p-4">Nama</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">No. HP</th>
                            <th class="p-4">Peran (Role)</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Tanggal Dibuat</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($admins as $admin)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 font-bold text-gray-800">
                                    {{ $admin->nama }}
                                </td>
                                <td class="p-4">
                                    {{ $admin->email }}
                                </td>
                                <td class="p-4">
                                    {{ $admin->no_hp ?? '-' }}
                                </td>
                                <td class="p-4">
                                    @if($admin->isSuperadmin())
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-800 text-xs font-bold rounded-full border border-amber-100">Superadmin</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100">Admin/Pengurus</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($admin->is_active)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-100"><i class="fa-solid fa-circle text-[8px] mr-1.5 align-middle"></i>Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-800 text-xs font-bold rounded-full border border-rose-100"><i class="fa-solid fa-circle text-[8px] mr-1.5 align-middle"></i>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-gray-400">
                                    {{ $admin->created_at->format('d M Y') }}
                                </td>
                                <td class="p-4 text-right space-x-2 whitespace-nowrap">
                                    <!-- Edit Button -->
                                    <a href="{{ route('superadmin.admins.edit', $admin->id) }}" class="inline-block p-2 text-gray-600 hover:text-emerald-800 rounded-lg hover:bg-gray-50 transition" title="Edit Profil & Reset Password">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    
                                    <!-- Delete (Deactivate) Button -->
                                    @if(auth()->guard('admin')->id() !== $admin->id)
                                        @if($admin->is_active)
                                            <form action="{{ route('superadmin.admins.destroy', $admin->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun pengurus ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-600 hover:text-rose-800 rounded-lg hover:bg-rose-50 transition" title="Nonaktifkan Pengurus">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($admins->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
