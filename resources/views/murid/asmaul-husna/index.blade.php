@extends('layouts.murid')

@section('title', 'Asmaul Husna')

@section('content')
<div class="px-5 py-6 space-y-5" x-data="{ 
    search: '',
    openId: null,
    filterName(latin, arti, arab) {
        if (this.search === '') return true;
        const q = this.search.toLowerCase();
        return latin.toLowerCase().includes(q) || arti.toLowerCase().includes(q) || arab.includes(q);
    },
    
    // Audio State
    fullAudio: null,
    fullPlaying: false,
    fullDuration: 0,
    fullCurrentTime: 0,
    fullPlaybackRate: 1.0,
    
    individualAudio: null,
    playingIndividualId: null,
    individualPlaying: false,

    // Initializer
    init() {
        this.fullAudio = new Audio('https://archive.org/download/KoleksiNasyidPilihanBacaquran.tk/Hijjaz-asmaulHusna.mp3');
        
        this.fullAudio.addEventListener('durationchange', () => {
            this.fullDuration = this.fullAudio.duration;
        });
        this.fullAudio.addEventListener('timeupdate', () => {
            this.fullCurrentTime = this.fullAudio.currentTime;
        });
        this.fullAudio.addEventListener('ended', () => {
            this.fullPlaying = false;
            this.fullCurrentTime = 0;
        });
    },

    // Getter Penentu Highlight Aktif
    get activeHighlightId() {
        if (this.individualPlaying) {
            return this.playingIndividualId;
        }
        if (this.fullPlaying && this.fullDuration > 20) {
            const intro = 12; // intro Hijjaz (12 detik)
            if (this.fullCurrentTime < intro) return null;
            const pct = (this.fullCurrentTime - intro) / (this.fullDuration - intro);
            const index = Math.floor(pct * 99) + 1;
            return Math.min(99, Math.max(1, index));
        }
        return null;
    },

    // Full Audio Controls
    toggleFull() {
        if (this.fullPlaying) {
            this.fullAudio.pause();
            this.fullPlaying = false;
        } else {
            this.stopIndividual();
            this.fullAudio.playbackRate = this.fullPlaybackRate;
            this.fullAudio.play().catch(e => console.error('Gagal memutar audio penuh:', e));
            this.fullPlaying = true;
        }
    },
    
    setSpeed(rate) {
        this.fullPlaybackRate = rate;
        this.fullAudio.playbackRate = rate;
    },
    
    seekFull(value) {
        this.fullAudio.currentTime = value;
        this.fullCurrentTime = value;
    },
    
    formatTime(seconds) {
        if (isNaN(seconds)) return '00:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    },

    // Individual Audio Controls
    playIndividual(urutan) {
        // Stop full audio if playing
        if (this.fullPlaying) {
            this.fullAudio.pause();
            this.fullPlaying = false;
        }

        // If clicking the same already playing individual audio, pause it
        if (this.playingIndividualId === urutan && this.individualPlaying) {
            this.stopIndividual();
            return;
          }

        this.stopIndividual();

        this.playingIndividualId = urutan;
        this.individualPlaying = true;
        const padId = String(urutan).padStart(3, '0');
        const url = `https://www.islamicity.org/mediaassets/MP3/other/covers/99-names-of-Allah/${padId}.mp3?v06092021`;

        this.individualAudio = new Audio(url);
        this.individualAudio.play().catch(err => {
            console.warn('Gagal memutar audio individu:', err.message);
            this.stopIndividual();
        });

        this.individualAudio.addEventListener('ended', () => {
            this.stopIndividual();
        });
    },

    stopIndividual() {
        if (this.individualAudio) {
            this.individualAudio.pause();
            this.individualAudio = null;
        }
        this.playingIndividualId = null;
        this.individualPlaying = false;
    }
}">
    <!-- Header -->
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center text-lg shadow-md">
            <i class="fa-solid fa-kaaba"></i>
        </div>
        <div>
            <h2 class="font-extrabold text-gray-900 text-base">Asmaul Husna</h2>
            <p class="text-[10px] text-gray-500">99 Nama Allah Yang Maha Indah</p>
        </div>
    </div>

    <!-- Top Audio Player Card -->
    <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-5 shadow-md flex flex-col space-y-3 relative overflow-hidden">
        <!-- Islamic Spinning star decoration -->
        <div class="absolute -right-6 -bottom-6 w-24 h-24 border border-white/5 rounded-full flex items-center justify-center pointer-events-none">
            <i class="fa-solid fa-star-and-crescent text-4xl text-white/5 animate-[spin_20s_linear_infinite]" :class="fullPlaying ? 'opacity-100' : 'opacity-20'"></i>
        </div>

        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-xs text-amber-300 uppercase tracking-wider">Murottal Asmaul Husna</h3>
                <p class="text-[10px] text-emerald-200 mt-0.5">Lantunan Indah 99 Nama Allah (Hijjaz)</p>
            </div>
            <!-- Speed Controller -->
            <div class="flex items-center space-x-1 bg-white/10 rounded-xl p-0.5 text-[9px] font-bold">
                <button @click="setSpeed(1.0)" :class="fullPlaybackRate === 1.0 ? 'bg-amber-400 text-emerald-950' : 'text-white'" class="px-2 py-0.5 rounded-lg transition">1.0x</button>
                <button @click="setSpeed(1.25)" :class="fullPlaybackRate === 1.25 ? 'bg-amber-400 text-emerald-950' : 'text-white'" class="px-2 py-0.5 rounded-lg transition">1.25x</button>
                <button @click="setSpeed(1.5)" :class="fullPlaybackRate === 1.5 ? 'bg-amber-400 text-emerald-950' : 'text-white'" class="px-2 py-0.5 rounded-lg transition">1.5x</button>
            </div>
        </div>

        <!-- Controls and Progress -->
        <div class="flex items-center space-x-3.5">
            <button @click="toggleFull()" class="w-10 h-10 rounded-full bg-amber-400 text-emerald-950 flex items-center justify-center text-sm shadow-md transition hover:scale-105 active:scale-95 shrink-0">
                <i class="fa-solid" :class="fullPlaying ? 'fa-pause' : 'fa-play pl-0.5'"></i>
            </button>

            <!-- Timeline -->
            <div class="flex-1 space-y-1">
                <input type="range" min="0" :max="fullDuration || 100" :value="fullCurrentTime" @input="seekFull($event.target.value)"
                    class="w-full h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-amber-400 focus:outline-none">
                <div class="flex justify-between text-[8px] text-emerald-200/80 font-mono">
                    <span x-text="formatTime(fullCurrentTime)">00:00</span>
                    <span x-text="formatTime(fullDuration)">00:00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </span>
        <input type="text" x-model="search" placeholder="Cari nama Allah..."
            class="pl-10 pr-4 py-2.5 w-full bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-xs shadow-xs">
    </div>

    <!-- Counter -->
    <div class="text-[10px] text-gray-400 font-semibold text-center -mt-2">
        <span x-text="document.querySelectorAll('[data-asma-item]').length || '99'"></span> dari 99 Nama
    </div>

    <!-- Grid Cards Asmaul Husna -->
    <div class="grid grid-cols-2 gap-4 animate-[fadeIn_0.4s_ease-out]">
        @forelse($names as $name)
            <div data-asma-item x-show="filterName('{{ addslashes($name->latin) }}', '{{ addslashes($name->arti) }}', '{{ addslashes($name->arab) }}')" x-transition
                @click="openId = {{ $name->id }}"
                class="bg-white rounded-2xl border p-3 flex flex-col justify-between shadow-xs transition duration-300 relative cursor-pointer select-none hover:shadow-md hover:scale-[1.01] overflow-hidden"
                :class="activeHighlightId === {{ $name->urutan }} ? 'border-emerald-500 ring-2 ring-emerald-500/20 bg-emerald-50/10' : 'border-gray-100'">
                
                <!-- Floating Play Button (Top-Left) -->
                <button @click.stop="playIndividual({{ $name->urutan }})" 
                    class="absolute top-2.5 left-2.5 w-6.5 h-6.5 rounded-full flex items-center justify-center shrink-0 border transition duration-300 focus:outline-none z-10 shadow-xs"
                    :class="playingIndividualId === {{ $name->urutan }} && individualPlaying 
                        ? 'bg-amber-400 border-amber-400 text-emerald-950 animate-pulse font-bold' 
                        : 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:bg-emerald-100/80'">
                    <template x-if="playingIndividualId === {{ $name->urutan }} && individualPlaying">
                        <i class="fa-solid fa-pause text-[8px]"></i>
                    </template>
                    <template x-if="playingIndividualId !== {{ $name->urutan }} || !individualPlaying">
                        <i class="fa-solid fa-play text-[8px] pl-0.5"></i>
                    </template>
                </button>

                <!-- Sequence Badge (Top-Right) -->
                <span class="absolute top-2.5 right-2.5 px-1.5 py-0.5 rounded-md bg-gray-50 border border-gray-100 text-gray-400 font-bold text-[8px]">
                    {{ $name->urutan }}
                </span>

                <!-- Dome Arch containing Arabic Calligraphy -->
                <div class="border border-amber-200/50 rounded-t-full mt-6 p-4 flex flex-col items-center justify-center bg-gray-50/40 relative aspect-[4/5] w-full">
                    <h3 class="arabic-text text-3xl font-bold text-emerald-950 leading-none select-none">{{ $name->arab }}</h3>
                </div>

                <!-- Divider -->
                <div class="border-b border-gray-100 my-2"></div>

                <!-- Text Info -->
                <div class="text-center space-y-0.5">
                    <span class="text-xs font-black text-gray-900 block leading-tight">{{ $name->latin }}</span>
                    <p class="text-[9px] text-gray-500 font-semibold leading-tight line-clamp-1" title="{{ $name->arti }}">{{ $name->arti }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-kaaba text-3xl text-gray-300 mb-2"></i>
                <p class="text-xs">Data Asmaul Husna tidak tersedia.</p>
            </div>
        @endforelse
    </div>

    <!-- Bottom Sheet Drawer Modal -->
    <div x-show="openId !== null" 
        class="absolute top-0 left-0 right-0 bottom-[-80px] z-50 flex items-end justify-center bg-gray-900/60 backdrop-blur-xs" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak>
        
        <div @click.outside="openId = null" 
            class="bg-white rounded-t-3xl w-full p-6 space-y-5 shadow-2xl border-t border-gray-150 relative transform transition-transform duration-300"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full">
            
            <!-- Close Indicator Bar -->
            <div class="w-12 h-1 bg-gray-200 rounded-full mx-auto -mt-2 mb-3 cursor-pointer" @click="openId = null"></div>

            <!-- Drawer Content -->
            @foreach($names as $name)
                <div x-show="openId === {{ $name->id }}" class="space-y-4 text-center">
                    <!-- Header Calligraphy -->
                    <div class="py-4 bg-emerald-50/20 border border-emerald-100/50 rounded-2xl relative overflow-hidden">
                        <h4 class="arabic-text text-5xl font-black text-emerald-950">{{ $name->arab }}</h4>
                    </div>
                    
                    <!-- Latin & Arti -->
                    <div>
                        <h3 class="text-xl font-black text-gray-900">{{ $name->latin }}</h3>
                        <span class="text-xs font-bold text-amber-600">Nama Ke-{{ $name->urutan }} • {{ $name->arti }}</span>
                    </div>

                    <!-- Divider -->
                    <div class="border-b border-gray-100"></div>

                    <!-- Deskripsi -->
                    <div class="text-left bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-[9px] font-black text-gray-400 block uppercase tracking-wider mb-1">Khasiat & Penjelasan</span>
                        <p class="text-xs text-gray-700 leading-relaxed">{{ $name->deskripsi }}</p>
                    </div>

                    <!-- Action Play Button inside Modal -->
                    <div class="pt-2 flex items-center justify-center space-x-3">
                        <button @click="playIndividual({{ $name->urutan }})" 
                            class="w-full py-3.5 rounded-2xl font-extrabold text-xs flex items-center justify-center space-x-2 transition"
                            :class="playingIndividualId === {{ $name->urutan }} && individualPlaying 
                                ? 'bg-rose-600 hover:bg-rose-700 text-white' 
                                : 'bg-emerald-700 hover:bg-emerald-800 text-white'">
                            <i class="fa-solid" :class="playingIndividualId === {{ $name->urutan }} && individualPlaying ? 'fa-pause' : 'fa-play'"></i>
                            <span x-text="playingIndividualId === {{ $name->urutan }} && individualPlaying ? 'Jeda Suara' : 'Putar Pelafalan'"></span>
                        </button>
                        <button @click="openId = null" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-extrabold text-xs px-5 py-3.5 rounded-2xl transition">
                            Tutup
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
