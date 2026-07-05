# Jadwal Shalat & Adzan Realtime Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan widget Jadwal Shalat Realtime beserta pemutaran Adzan otomatis penuh sesuai pilihan Muadzin di Dashboard Portal Murid menggunakan lokasi GPS dan fallback pilihan Kota/Kabupaten.

**Architecture:**
- Frontend-only implementation menggunakan Alpine.js di dalam Blade view `resources/views/murid/dashboard.blade.php`.
- Menggunakan Geolocation API browser untuk koordinat GPS, dengan fallback dropdown 10 Kota besar di Indonesia.
- Mengambil data dari AlAdhan API bulanan (`https://api.aladhan.com/v1/calendar`) dengan metode Kemenag RI (method 20).
- Menyimpan cache bulanan di LocalStorage (`tpq_sholat_data`) untuk efisiensi API dan kesiapan offline PWA.
- Menjalankan realtime interval di browser untuk melacak countdown shalat berikutnya, memicu Adzan otomatis, serta menangani kebijakan autoplay browser dengan banner interaktif.

**Tech Stack:**
- Blade (Laravel)
- Alpine.js (terintegrasi di Portal Murid)
- Tailwind CSS / Vanilla CSS (untuk penataan layout widget premium)
- HTML5 Audio API & Geolocation API

---

## Global Constraints

- Bahasa Antarmuka: 100% Bahasa Indonesia.
- Kompatibel dengan PWA offline (jadwal shalat harus tetap tampil dari cache saat offline).
- Gunakan ikon Font Awesome (misal `fa-solid fa-mosque`, `fa-solid fa-volume-high`, dll.) yang sudah diload di layout murid.

---

### Task 1: Terapkan Widget Jadwal Shalat & Alpine.js Logic di Dashboard Murid

**Files:**
- Modify: `resources/views/murid/dashboard.blade.php:40-45`

**Interfaces:**
- Consumes: AlAdhan API bulanan (`https://api.aladhan.com/v1/calendar`), LocalStorage browser.
- Produces: Widget Jadwal Shalat, countdown realtime, pemutaran Adzan otomatis & manual test.

- [ ] **Step 1: Modifikasi Dashboard Murid untuk Menyisipkan Widget & Alpine.js State**
  Buka [dashboard.blade.php](file:///c:/laragon/www/porto-apps/lms-tpq/resources/views/murid/dashboard.blade.php) dan sisipkan komponen widget Jadwal Shalat tepat di atas `<!-- Quick Action Library Grid -->` (di bawah Welcome Card).

  Gunakan kode implementasi lengkap berikut:

  ```html
  <!-- Prayer Times Widget -->
  <div class="bg-white rounded-3xl border border-gray-150 p-5 shadow-sm space-y-4"
       x-data="prayerTimesWidget()">
      
      <!-- Widget Header -->
      <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div class="flex items-center space-x-2">
              <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-base">
                  <i class="fa-solid fa-mosque"></i>
              </div>
              <div>
                  <h4 class="font-extrabold text-xs text-gray-900 leading-none">Jadwal Shalat</h4>
                  <span class="text-[9px] text-emerald-700 font-bold" x-text="locationName">Mendeteksi Lokasi...</span>
              </div>
          </div>
          <button @click="showLocationModal = true" class="text-[10px] text-gray-400 hover:text-emerald-700 font-bold flex items-center space-x-1 transition">
              <i class="fa-solid fa-location-dot"></i>
              <span>Ubah Lokasi</span>
          </button>
      </div>

      <!-- Loading State -->
      <div x-show="loading" class="flex flex-col items-center justify-center py-6 space-y-2">
          <div class="w-6 h-6 border-2 border-emerald-700 border-t-transparent rounded-full animate-spin"></div>
          <span class="text-[10px] text-gray-400 font-medium">Memuat jadwal shalat...</span>
      </div>

      <!-- Widget Content -->
      <div x-show="!loading" class="space-y-4" x-cloak>
          <!-- Next Prayer Countdown -->
          <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl p-4 text-center border border-emerald-100 relative overflow-hidden">
              <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-emerald-700 opacity-5 rounded-full"></div>
              <span class="text-[9px] text-emerald-800 font-extrabold uppercase tracking-wider block" x-text="nextPrayer.name ? nextPrayer.name + ' Berikutnya' : 'Memuat...'"></span>
              <span class="text-xl font-black text-emerald-950 block my-1" x-text="nextPrayer.countdownStr || '--:--:--'"></span>
              <span class="text-[9px] text-gray-500 block" x-text="nextPrayer.timeStr ? 'Pukul ' + nextPrayer.timeStr : ''"></span>
          </div>

          <!-- Active Adzan Controller Banner -->
          <div x-show="adzanPlaying" class="bg-rose-50 border border-rose-100 rounded-2xl p-3 flex items-center justify-between animate-pulse">
              <div class="flex items-center space-x-2">
                  <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                  </span>
                  <span class="text-[9px] font-bold text-rose-900" x-text="'Adzan Kumandang: ' + adzanName"></span>
              </div>
              <button @click="stopAdzan()" class="bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-[9px] px-3 py-1 rounded-xl transition shadow-sm">
                  <i class="fa-solid fa-stop mr-1"></i> Hentikan
              </button>
          </div>

          <!-- Browser Blocked Autoplay Alert -->
          <div x-show="browserBlockedAdzan" class="bg-amber-50 border border-amber-200 rounded-2xl p-3 text-center space-y-2 cursor-pointer transition hover:bg-amber-100" @click="playAdzanBypassed()">
              <span class="text-[9px] font-bold text-amber-900 block">
                  <i class="fa-solid fa-circle-exclamation mr-1 text-amber-600 animate-bounce"></i> 
                  Waktunya Shalat! Ketuk di sini untuk mengumandangkan Adzan.
              </span>
          </div>

          <!-- Grid Shalat -->
          <div class="grid grid-cols-5 gap-1.5 text-center">
              <template x-for="(time, name) in prayerTimes" :key="name">
                  <div class="p-2 rounded-xl border transition"
                       :class="nextPrayer.name === name ? 'bg-emerald-800 text-white border-emerald-800 scale-105 shadow-sm font-bold' : 'bg-gray-50 text-gray-900 border-gray-100'">
                      <span class="text-[8px] uppercase tracking-wider block opacity-75" x-text="translatePrayerName(name)"></span>
                      <span class="text-[10px] font-black block mt-1" x-text="time"></span>
                  </div>
              </template>
          </div>

          <!-- Settings Bottom Bar -->
          <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
              <!-- Muadzin Selection -->
              <div class="flex items-center space-x-1.5">
                  <span class="text-[9px] font-bold text-gray-400 uppercase">Muadzin:</span>
                  <select x-model="muadzin" @change="saveSettings()" class="text-[10px] font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-lg px-2 py-1 focus:outline-none focus:border-emerald-700">
                      <option value="makkah">Makkah</option>
                      <option value="madinah">Madinah</option>
                      <option value="aqsa">Al-Aqsa</option>
                      <option value="turki">Turki</option>
                  </select>
              </div>

              <!-- Test and Mute Toggles -->
              <div class="flex items-center space-x-2">
                  <button @click="testAdzan()" class="text-[9px] font-extrabold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-2 py-1 rounded-lg transition" x-text="adzanPlaying ? 'Stop Tes' : 'Tes Adzan'">
                      Tes Adzan
                  </button>
                  <button @click="toggleMute()" class="w-7 h-7 rounded-lg flex items-center justify-center transition border"
                          :class="isMuted ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-gray-50 text-gray-600 border-gray-200 hover:text-emerald-700'">
                      <i class="fa-solid" :class="isMuted ? 'fa-volume-xmark' : 'fa-volume-high'"></i>
                  </button>
              </div>
          </div>
      </div>

      <!-- Location Selection Modal (Alpine.js) -->
      <div x-show="showLocationModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs" x-transition x-cloak>
          <div @click.outside="showLocationModal = false" class="bg-white rounded-3xl p-6 max-w-sm w-full space-y-4 shadow-xl border border-gray-100">
              <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                  <h3 class="font-extrabold text-sm text-gray-900">Pilih Wilayah / Kota</h3>
                  <button @click="showLocationModal = false" class="text-gray-400 hover:text-gray-600 text-xs">
                      <i class="fa-solid fa-xmark text-sm"></i>
                  </button>
              </div>
              <div class="space-y-3">
                  <p class="text-[10px] text-gray-500 leading-normal">Pilih kota secara manual jika lokasi GPS Anda tidak terdeteksi dengan akurat atau diblokir oleh browser.</p>
                  
                  <button @click="requestGPSLocation()" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs py-2.5 px-4 rounded-xl flex items-center justify-center space-x-2 transition shadow-xs">
                      <i class="fa-solid fa-crosshairs text-xs"></i>
                      <span>Gunakan Lokasi GPS Saya</span>
                  </button>

                  <div class="relative flex items-center justify-center my-3">
                      <span class="absolute w-full border-t border-gray-100"></span>
                      <span class="relative bg-white px-3 text-[9px] font-bold text-gray-400 uppercase">Atau Pilih Kota</span>
                  </div>

                  <select x-model="selectedCity" @change="selectManualCity()" class="w-full text-xs font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-50">
                      <option value="jakarta">DKI Jakarta</option>
                      <option value="surabaya">Surabaya</option>
                      <option value="bandung">Bandung</option>
                      <option value="medan">Medan</option>
                      <option value="makassar">Makassar</option>
                      <option value="semarang">Semarang</option>
                      <option value="palembang">Palembang</option>
                      <option value="yogyakarta">Yogyakarta</option>
                      <option value="balikpapan">Balikpapan</option>
                      <option value="aceh">Banda Aceh</option>
                  </select>
              </div>
          </div>
      </div>
  </div>

  <!-- Javascript Logic for Prayer Times Widget -->
  <script>
  function prayerTimesWidget() {
      return {
          loading: true,
          latitude: null,
          longitude: null,
          locationName: 'Mendeteksi Lokasi...',
          showLocationModal: false,
          selectedCity: 'jakarta',
          prayerTimes: {},
          nextPrayer: {
              name: '',
              timeStr: '',
              time: null,
              countdownStr: ''
          },
          muadzin: 'makkah',
          isMuted: false,
          adzanPlaying: false,
          adzanName: '',
          browserBlockedAdzan: false,
          adzanAudio: null,
          lastTriggeredPrayer: '',
          timerInterval: null,
          
          muadzinUrls: {
              makkah: 'https://archive.org/download/AdhanMakkah/Adhan%20Makkah.mp3',
              madinah: 'https://archive.org/download/adhan-madinah/adhan-madinah.mp3',
              aqsa: 'https://archive.org/download/adhan-al-aqsa/adhan-al-aqsa.mp3',
              turki: 'https://archive.org/download/adhan-turkey/adhan-turkey.mp3'
          },

          cities: {
              jakarta: { name: 'DKI Jakarta', lat: -6.2088, lng: 106.8456 },
              surabaya: { name: 'Surabaya', lat: -7.2575, lng: 112.7521 },
              bandung: { name: 'Bandung', lat: -6.9175, lng: 107.6191 },
              medan: { name: 'Medan', lat: 3.5952, lng: 98.6722 },
              makassar: { name: 'Makassar', lat: -5.1477, lng: 119.4327 },
              semarang: { name: 'Semarang', lat: -6.9667, lng: 110.4167 },
              palembang: { name: 'Palembang', lat: -2.9909, lng: 104.7566 },
              yogyakarta: { name: 'Yogyakarta', lat: -7.7956, lng: 110.3695 },
              balikpapan: { name: 'Balikpapan', lat: -1.2686, lng: 116.8612 },
              aceh: { name: 'Banda Aceh', lat: 5.5483, lng: 95.3238 }
          },

          init() {
              // Load saved settings
              const settings = JSON.parse(localStorage.getItem('tpq_sholat_settings') || '{}');
              this.muadzin = settings.muadzin || 'makkah';
              this.isMuted = settings.isMuted !== undefined ? settings.isMuted : false;
              this.selectedCity = settings.selectedCity || 'jakarta';

              // Request location
              this.loadLocation();

              // Start countdown timer
              this.timerInterval = setInterval(() => {
                  this.updateCountdown();
              }, 1000);

              // Setup user interaction listener to bypass browser autoplay restriction
              const userInteract = () => {
                  if (this.adzanAudio) {
                      // Silently try to load to register user gesture
                      this.adzanAudio.load();
                  }
                  document.removeEventListener('click', userInteract);
                  document.removeEventListener('touchstart', userInteract);
              };
              document.addEventListener('click', userInteract);
              document.addEventListener('touchstart', userInteract);
          },

          loadLocation() {
              const cachedLoc = JSON.parse(localStorage.getItem('tpq_sholat_location') || 'null');
              if (cachedLoc) {
                  this.latitude = cachedLoc.lat;
                  this.longitude = cachedLoc.lng;
                  this.locationName = cachedLoc.name;
                  this.fetchPrayerTimes();
              } else {
                  this.detectGPS(true); // Attempt GPS on first load, fallback to manual city silently
              }
          },

          detectGPS(fallbackSilently = false) {
              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(
                      (position) => {
                          this.latitude = position.coords.latitude;
                          this.longitude = position.coords.longitude;
                          this.locationName = 'Lokasi GPS Anda';
                          
                          localStorage.setItem('tpq_sholat_location', JSON.stringify({
                              lat: this.latitude,
                              lng: this.longitude,
                              name: this.locationName
                          }));
                          
                          this.fetchPrayerTimes();
                      },
                      (error) => {
                          console.warn('Gagal mendapatkan lokasi GPS:', error.message);
                          if (fallbackSilently) {
                              this.selectManualCity(true);
                          } else {
                              alert('Gagal mendeteksi lokasi GPS. Silakan pilih Kota secara manual.');
                          }
                      },
                      { timeout: 8000 }
                  );
              } else {
                  if (fallbackSilently) this.selectManualCity(true);
              }
          },

          requestGPSLocation() {
              this.showLocationModal = false;
              this.loading = true;
              this.detectGPS(false);
          },

          selectManualCity(silent = false) {
              const cityData = this.cities[this.selectedCity];
              if (cityData) {
                  this.latitude = cityData.lat;
                  this.longitude = cityData.lng;
                  this.locationName = 'Kota ' + cityData.name;
                  
                  localStorage.setItem('tpq_sholat_location', JSON.stringify({
                      lat: this.latitude,
                      lng: this.longitude,
                      name: this.locationName
                  }));
                  
                  this.saveSettings();
                  this.fetchPrayerTimes();
              }
              if (!silent) this.showLocationModal = false;
          },

          async fetchPrayerTimes() {
              this.loading = true;
              const date = new Date();
              const year = date.getFullYear();
              const month = date.getMonth() + 1; // 1-indexed
              const cacheKey = `tpq_sholat_data_${month}_${year}_${this.latitude.toFixed(2)}_${this.longitude.toFixed(2)}`;

              const cachedData = localStorage.getItem(cacheKey);
              if (cachedData) {
                  this.parseAndStoreTimings(JSON.parse(cachedData));
                  this.loading = false;
                  return;
              }

              try {
                  const res = await fetch(`https://api.aladhan.com/v1/calendar?latitude=${this.latitude}&longitude=${this.longitude}&method=20&month=${month}&year=${year}`);
                  if (!res.ok) throw new Error();
                  const data = await res.json();
                  
                  localStorage.setItem(cacheKey, JSON.stringify(data.data));
                  this.parseAndStoreTimings(data.data);
              } catch (e) {
                  console.error('Gagal mengambil jadwal sholat:', e);
                  this.locationName = 'Gagal memuat jadwal offline';
              } finally {
                  this.loading = false;
              }
          },

          parseAndStoreTimings(monthData) {
              const day = new Date().getDate();
              const todayTimings = monthData[day - 1]?.timings;
              if (todayTimings) {
                  // Filter out only Fajr, Dhuhr, Asr, Maghrib, Isha (and Sunrise for reference)
                  this.prayerTimes = {
                      Fajr: this.cleanTime(todayTimings.Fajr),
                      Sunrise: this.cleanTime(todayTimings.Sunrise),
                      Dhuhr: this.cleanTime(todayTimings.Dhuhr),
                      Asr: this.cleanTime(todayTimings.Asr),
                      Maghrib: this.cleanTime(todayTimings.Maghrib),
                      Isha: this.cleanTime(todayTimings.Isha)
                  };
              }
          },

          cleanTime(timeStr) {
              // Format: "04:30 (WIB)" -> "04:30"
              return timeStr.split(' ')[0];
          },

          translatePrayerName(name) {
              const translation = {
                  Fajr: 'Subuh',
                  Sunrise: 'Terbit',
                  Dhuhr: 'Dzuhur',
                  Asr: 'Ashar',
                  Maghrib: 'Maghrib',
                  Isha: 'Isya'
              };
              return translation[name] || name;
          },

          updateCountdown() {
              if (Object.keys(this.prayerTimes).length === 0) return;

              const now = new Date();
              const currentYear = now.getFullYear();
              const currentMonth = now.getMonth();
              const currentDate = now.getDate();

              let closestPrayer = null;
              let closestTime = null;
              let isTomorrow = false;

              // We only countdown to Fajr, Dhuhr, Asr, Maghrib, Isha
              const targetPrayers = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];

              targetPrayers.forEach(name => {
                  const timeParts = this.prayerTimes[name].split(':');
                  const prayerDate = new Date(currentYear, currentMonth, currentDate, parseInt(timeParts[0]), parseInt(timeParts[1]), 0);
                  
                  if (prayerDate > now) {
                      if (!closestTime || prayerDate < closestTime) {
                          closestTime = prayerDate;
                          closestPrayer = name;
                      }
                  }
              });

              // If all prayers today are passed, closest is Fajr tomorrow
              if (!closestPrayer) {
                  const timeParts = this.prayerTimes['Fajr'].split(':');
                  const tomorrow = new Date(now);
                  tomorrow.setDate(now.getDate() + 1);
                  closestTime = new Date(tomorrow.getFullYear(), tomorrow.getMonth(), tomorrow.getDate(), parseInt(timeParts[0]), parseInt(timeParts[1]), 0);
                  closestPrayer = 'Fajr';
                  isTomorrow = true;
              }

              // Update next prayer object
              this.nextPrayer.name = this.translatePrayerName(closestPrayer);
              this.nextPrayer.timeStr = this.prayerTimes[closestPrayer];
              this.nextPrayer.time = closestTime;

              const diffMs = closestTime - now;
              
              // Trigger Adzan when countdown hits 0 (between 0 and 1000ms left)
              if (diffMs > 0 && diffMs <= 1000) {
                  const prayerId = isTomorrow ? 'tomorrow_Fajr' : closestPrayer;
                  if (this.lastTriggeredPrayer !== prayerId) {
                      this.lastTriggeredPrayer = prayerId;
                      this.triggerAdzanNotification(this.nextPrayer.name);
                  }
              }

              this.nextPrayer.countdownStr = this.formatTimeDiff(diffMs);
          },

          formatTimeDiff(ms) {
              const totalSecs = Math.floor(ms / 1000);
              const hrs = Math.floor(totalSecs / 3600);
              const mins = Math.floor((totalSecs % 3600) / 60);
              const secs = totalSecs % 60;
              
              const pad = (n) => String(n).padStart(2, '0');
              return `${pad(hrs)}:${pad(mins)}:${pad(secs)}`;
          },

          triggerAdzanNotification(prayerName) {
              if (this.isMuted) {
                  console.info('Adzan dibisukan (mute state).');
                  return;
              }

              this.adzanName = prayerName;
              const adzanUrl = this.muadzinUrls[this.muadzin];
              
              if (this.adzanAudio) {
                  this.adzanAudio.pause();
              }
              
              this.adzanAudio = new Audio(adzanUrl);
              this.adzanPlaying = true;
              this.browserBlockedAdzan = false;

              this.adzanAudio.play().catch(err => {
                  console.warn('Autoplay terblokir oleh browser:', err.message);
                  this.browserBlockedAdzan = true;
                  this.adzanPlaying = false;
              });

              this.adzanAudio.addEventListener('ended', () => {
                  this.stopAdzan();
              });
          },

          playAdzanBypassed() {
              this.browserBlockedAdzan = false;
              this.adzanPlaying = true;
              if (this.adzanAudio) {
                  this.adzanAudio.play().catch(e => console.error(e));
              }
          },

          testAdzan() {
              if (this.adzanPlaying) {
                  this.stopAdzan();
                  return;
              }

              this.adzanName = 'Pengujian Suara';
              const adzanUrl = this.muadzinUrls[this.muadzin];

              if (this.adzanAudio) {
                  this.adzanAudio.pause();
              }

              this.adzanAudio = new Audio(adzanUrl);
              this.adzanPlaying = true;

              this.adzanAudio.play().catch(err => {
                  console.error('Gagal memutar suara tes:', err.message);
                  alert('Autoplay terblokir. Silakan ketuk area mana saja pada halaman terlebih dahulu baru klik Tes Adzan lagi.');
                  this.adzanPlaying = false;
              });

              this.adzanAudio.addEventListener('ended', () => {
                  this.stopAdzan();
              });
          },

          stopAdzan() {
              if (this.adzanAudio) {
                  this.adzanAudio.pause();
                  this.adzanAudio = null;
              }
              this.adzanPlaying = false;
              this.browserBlockedAdzan = false;
          },

          toggleMute() {
              this.isMuted = !this.isMuted;
              if (this.isMuted && this.adzanPlaying) {
                  this.stopAdzan();
              }
              this.saveSettings();
          },

          saveSettings() {
              localStorage.setItem('tpq_sholat_settings', JSON.stringify({
                  muadzin: this.muadzin,
                  isMuted: this.isMuted,
                  selectedCity: this.selectedCity
              }));
          }
      };
  }
  </script>
  ```

- [ ] **Step 2: Jalankan asset compilation**
  Run: `npm run build`
  Expected: Compile Vite asset sukses tanpa ada syntax error.

---

## Task 2: Verifikasi & Uji Coba

- [ ] **Step 1: Jalankan automated tests**
  Run: `composer run test`
  Expected: 150 tests passed successfully.

- [ ] **Step 2: Pengujian Manual di Browser**
  Buka halaman Portal Murid (Beranda).
  1. Izinkan pencarian lokasi GPS. Verifikasi nama lokasi muncul *"Lokasi GPS Anda"* dan waktu shalat terisi penuh.
  2. Tekan tombol **Tes Adzan**. Verifikasi audio terputar secara penuh sesuai Muadzin pilihan dan tombol berubah menjadi *"Hentikan"*. Klik *"Hentikan"* untuk mematikannya kembali.
  3. Klik **Ubah Lokasi**, pilih *"Kota Yogyakarta"*. Verifikasi widget memuat ulang jadwal shalat Yogyakarta dan nama lokasi berubah menjadi *"Kota Yogyakarta"*.
