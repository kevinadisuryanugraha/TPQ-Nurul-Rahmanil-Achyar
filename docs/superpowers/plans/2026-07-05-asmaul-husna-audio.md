# Asmaul Husna Audio Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan fitur audio interaktif (pemutar murottal penuh nasyid Hijjaz dan pemutar suara pelafalan per nama Allah) pada halaman Asmaul Husna murid.

**Architecture:** Modifikasi dilakukan sepenuhnya di sisi client (Frontend) pada file Blade [index.blade.php](file:///c:/laragon/www/porto-apps/lms-tpq/resources/views/murid/asmaul-husna/index.blade.php) menggunakan state reactive Alpine.js dan HTML5 Audio API. Tidak membutuhkan perubahan database atau route backend.

**Tech Stack:** Laravel Blade, Alpine.js, Tailwind CSS, FontAwesome 6.4.0.

## Global Constraints
- Bahasa antarmuka: 100% Bahasa Indonesia untuk salinan teks dan label.
- Menggunakan FontAwesome via CDN untuk ikon.
- Desain responsif ramah seluler (mobile-first).

---

### Task 1: Terapkan Audio Player di Asmaul Husna View

**Files:**
- Modify: `resources/views/murid/asmaul-husna/index.blade.php`

**Interfaces:**
- Consumes: `AsmaulHusna` data from controller (variable `$names`).
- Produces: Web-based HTML5 Audio components managed by Alpine.js.

- [ ] **Step 1: Modifikasi `index.blade.php` bagian `x-data`**
  Tambahkan variabel state audio pada baris 6-14 untuk pemutar murottal Hijjaz dan audio individu:
  ```javascript
  {
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
  }
  ```

- [ ] **Step 2: Sisipkan UI Pemutar Utama (Top Audio Player Card)**
  Di bawah header dan di atas input pencarian, sisipkan elemen HTML berikut:
  ```html
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
  ```

- [ ] **Step 3: Ganti Nomor Urutan dengan Tombol Audio Per Nama**
  Modifikasi elemen tombol pemutar per nama di sebelah kiri data nama Allah:
  ```html
  <!-- Replace lines 49-52 in index.blade.php -->
  <div class="flex items-center space-x-3.5">
      <button @click.stop="playIndividual({{ $name->urutan }})" 
          class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border transition duration-300"
          :class="playingIndividualId === {{ $name->urutan }} && individualPlaying 
              ? 'bg-amber-400 border-amber-400 text-emerald-950 animate-pulse' 
              : 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:bg-emerald-100'">
          <template x-if="playingIndividualId === {{ $name->urutan }} && individualPlaying">
              <i class="fa-solid fa-pause text-[10px]"></i>
          </template>
          <template x-if="playingIndividualId !== {{ $name->urutan }} || !individualPlaying">
              <span class="font-bold text-xs" x-text="'{{ $name->urutan }}'"></span>
          </template>
      </button>
  ```

- [ ] **Step 4: Jalankan build asset**
  Run: `npm run build`

---

### Task 2: Verifikasi & Uji Coba

- [ ] **Step 1: Jalankan unit tests**
  Run: `composer run test`

- [ ] **Step 2: Uji fungsionalitas audio**
  Buka halaman Asmaul Husna santri, uji tombol pemutar murottal penuh di atas dan tombol putar individu per nomor kartu. Pastikan pemutaran audio berjalan lancar tanpa bertabrakan suara.
