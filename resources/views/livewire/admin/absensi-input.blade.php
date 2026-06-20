<div class="space-y-6">
    <!-- Top Date & Session Controls -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-6 items-end">
        <!-- Date Picker -->
        <div>
            <label for="tanggal" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Pilih Tanggal</label>
            <input type="date" wire:model.live="tanggal" id="tanggal"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm">
        </div>

        <!-- Session Selection -->
        <div>
            <label for="sesi" class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5">Pilih Sesi</label>
            <select wire:model.live="sesi" id="sesi"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-sm bg-white">
                @foreach($appSessions as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>
        </div>

        <!-- Mode Indicator Badge -->
        <div class="flex items-center sm:justify-end py-1">
            @if($isEdit)
                <span class="px-4 py-2 bg-amber-50 text-amber-800 rounded-xl border border-amber-200 text-xs font-bold flex items-center space-x-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Mode Edit (Data Sudah Ada)</span>
                </span>
            @else
                <span class="px-4 py-2 bg-emerald-50 text-emerald-800 rounded-xl border border-emerald-200 text-xs font-bold flex items-center space-x-2">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span>Mode Input Baru (Bawaan Alpha)</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Student Attendance Form List -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-white">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Daftar Kehadiran Santri</h3>
        </div>

        <div class="overflow-x-auto">
            @if(empty($studentsList))
                <div class="p-12 text-center text-gray-400">
                    <i class="fa-regular fa-face-frown text-4xl mb-3 block"></i>
                    <p class="text-sm">Tidak ada santri aktif untuk dicatat absensinya.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase">
                            <th class="p-4 w-[35%]">Nama Santri</th>
                            <th class="p-4 text-center">Hadir</th>
                            <th class="p-4 text-center">Izin</th>
                            <th class="p-4 text-center">Sakit</th>
                            <th class="p-4 text-center">Alpha</th>
                            <th class="p-4 w-[35%]">Catatan Khusus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @foreach($studentsList as $student)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ $student->nama_lengkap }}</div>
                                    <span class="text-xs text-gray-400">Level: {{ $student->currentLevel->nama }}</span>
                                </td>
                                
                                <!-- Hadir -->
                                <td class="p-4 text-center">
                                    <label class="inline-flex items-center justify-center p-2 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="hadir" 
                                            wire:model="attendance.{{ $student->id }}"
                                            class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                                    </label>
                                </td>

                                <!-- Izin -->
                                <td class="p-4 text-center">
                                    <label class="inline-flex items-center justify-center p-2 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="izin" 
                                            wire:model="attendance.{{ $student->id }}"
                                            class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    </label>
                                </td>

                                <!-- Sakit -->
                                <td class="p-4 text-center">
                                    <label class="inline-flex items-center justify-center p-2 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="sakit" 
                                            wire:model="attendance.{{ $student->id }}"
                                            class="w-5 h-5 text-amber-600 border-gray-300 focus:ring-amber-500">
                                    </label>
                                </td>

                                <!-- Alpha -->
                                <td class="p-4 text-center">
                                    <label class="inline-flex items-center justify-center p-2 cursor-pointer">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="alpha" 
                                            wire:model="attendance.{{ $student->id }}"
                                            class="w-5 h-5 text-rose-600 border-gray-300 focus:ring-rose-500">
                                    </label>
                                </td>

                                <!-- Note Text field -->
                                <td class="p-4">
                                    <input type="text" wire:model="catatan.{{ $student->id }}" placeholder="Keterangan..."
                                        class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-transparent transition text-xs placeholder-gray-300">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Action Footer -->
        <div class="p-6 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end">
            <button type="button" wire:click="save"
                class="px-6 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-md transition active:scale-[0.98] text-sm">
                Simpan Kehadiran
            </button>
        </div>
    </div>
</div>
