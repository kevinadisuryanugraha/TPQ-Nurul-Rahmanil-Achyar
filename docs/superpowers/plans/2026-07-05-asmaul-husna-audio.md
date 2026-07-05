# Asmaul Husna Grid Card & Audio Integration Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengubah tampilan Asmaul Husna menjadi kartu grid 2-kolom bermotif kubah masjid, menyisipkan laci detail (bottom drawer modal), serta menambahkan logika highlight dinamis linear ketika memutar murottal penuh.

**Architecture:** Modifikasi dilakukan sepenuhnya di sisi client (Frontend) pada file Blade [index.blade.php](file:///c:/laragon/www/porto-apps/lms-tpq/resources/views/murid/asmaul-husna/index.blade.php) menggunakan Alpine.js dan Tailwind CSS.

**Tech Stack:** Laravel Blade, Alpine.js, Tailwind CSS, FontAwesome 6.4.0.

## Global Constraints
- Bahasa antarmuka: 100% Bahasa Indonesia untuk salinan teks dan label.
- Menggunakan FontAwesome via CDN untuk ikon.
- Desain responsif ramah seluler (mobile-first, max-width 480px).

---

### Task 1: Terapkan Grid Card, Laci Detail, & Highlight Dinamis

**Files:**
- Modify: `resources/views/murid/asmaul-husna/index.blade.php`

**Interfaces:**
- Consumes: `$names` collection from controller.
- Produces: Grid cards, bottom sheet drawer, and linear highlight state.

- [ ] **Step 1: Modifikasi Alpine.js State & Logika Highlight**
  Perbarui `x-data` di [index.blade.php](file:///c:/laragon/www/porto-apps/lms-tpq/resources/views/murid/asmaul-husna/index.blade.php) untuk melacak `activeDetailId` (untuk laci bawah bawah) dan menghitung `activeHighlightId`:
  ```javascript
  {
      search: '',
      openId: null, // Digunakan sebagai activeDetailId (laci laci bawah)
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
          if (this.fullPlaying) {
              this.fullAudio.pause();
              this.fullPlaying = false;
          }

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
  }
  ```

- [ ] **Step 2: Update Markup Grid Card List**
  Ganti bagian `<!-- Accordion Cards -->` menjadi grid card dengan ornamen kubah masjid (dome arch) dan highlight dinamis:
  ```html
  <!-- Grid Cards Asmaul Husna -->
  <div class="grid grid-cols-2 gap-4">
      @forelse($names as $name)
          <div data-asma-item x-show="filterName('{{ $name->latin }}', '{{ $name->arti }}', '{{ $name->arab }}')" x-transition
              @click="openId = {{ $name->id }}"
              class="bg-white rounded-2xl border p-3.5 flex flex-col justify-between shadow-xs transition duration-300 relative cursor-pointer select-none hover:shadow-md hover:scale-[1.01] overflow-hidden"
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
              <div class="border-t border-x border-amber-200/50 rounded-t-full mt-6 p-3 pt-6 pb-4 flex flex-col items-center justify-center bg-gray-50/40 relative">
                  <h3 class="arabic-text text-2xl font-bold text-emerald-950 leading-none select-none">{{ $name->arab }}</h3>
              </div>

              <!-- Divider -->
              <div class="border-b border-gray-100 my-2"></div>

              <!-- Text Info -->
              <div class="text-center space-y-0.5">
                  <span class="text-xs font-black text-gray-900 block">{{ $name->latin }}</span>
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
  ```

- [ ] **Step 3: Tambahkan Markup Bottom Sheet Detail Modal**
  Sisipkan modal laci bawah (Bottom Sheet Drawer) di bagian paling bawah halaman untuk menampilkan penjelasan detail nama saat kartu diklik:
  ```html
  <!-- Bottom Sheet Drawer Modal -->
  <div x-show="openId !== null" 
      class="fixed inset-0 z-50 flex items-end justify-center bg-gray-900/60 backdrop-blur-xs" 
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      x-cloak>
      
      <div @click.outside="openId = null" 
          class="bg-white rounded-t-3xl max-w-sm w-full p-6 space-y-5 shadow-2xl border-t border-gray-150 relative transform transition-transform duration-300"
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
  ```

- [ ] **Step 4: Jalankan build asset**
  Run: `npm run build`

---

### Task 2: Verifikasi & Uji Coba

- [ ] **Step 1: Jalankan unit tests**
  Run: `composer run test`

- [ ] **Step 2: Uji fungsionalitas audio & grid highlight**
  Buka halaman Asmaul Husna santri, uji pemutar murottal penuh dan klik beberapa nama untuk membuka laci bawah detail modal. Uji highlight dinamis yang berpindah dan penanganan jeda audio.
