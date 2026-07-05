@extends('layouts.murid')

@section('title', 'Beranda')

@section('content')
<div class="px-5 py-6 space-y-6">
    <!-- Welcome Header Card -->
    <div class="bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-6 shadow-md relative overflow-hidden">
        <!-- Gold decoration -->
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-amber-400 opacity-15 rounded-full blur-xl"></div>
        <div class="absolute -left-10 -top-10 w-28 h-28 bg-emerald-700 opacity-40 rounded-full blur-lg"></div>

        <div class="relative z-10 space-y-3">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-amber-400 border-2 border-emerald-700 text-emerald-950 flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($student->nama_panggilan, 0, 1)) }}
                </div>
                <div>
                    <span class="text-xs text-emerald-200 block font-medium">Assalamu'alaikum,</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <h2 class="font-extrabold text-base text-white leading-none">{{ $student->nama_lengkap }}</h2>
                        <div class="bg-amber-400 text-emerald-950 font-extrabold text-[9px] px-2 py-0.5 rounded-full flex items-center gap-1 shadow-xs shrink-0 select-none">
                            <i class="fa-solid fa-star text-[8px] animate-pulse"></i>
                            <span>{{ $student->points }} XP</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-emerald-800 flex items-center justify-between">
                <div>
                    <span class="text-[9px] text-emerald-300 block uppercase tracking-wider font-semibold">Level Saat Ini</span>
                    <span class="text-sm font-bold text-amber-300">{{ $student->currentLevel->nama ?? '-' }}</span>
                </div>
                <div class="text-right">
                    <span class="text-[9px] text-emerald-300 block uppercase tracking-wider font-semibold">Tahun Ajaran</span>
                    <span class="text-xs font-semibold text-white">{{ $appSettings['tahun_ajaran'] ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

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
                <span class="text-[9px] font-bold text-amber-900 block flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation text-amber-600 animate-bounce"></i> 
                    <span x-text="adzanName === 'Pengujian Suara' ? 'Tes Adzan terblokir oleh browser. Ketuk di sini untuk memutar.' : 'Waktunya Shalat! Ketuk di sini untuk mengumandangkan Adzan.'"></span>
                </span>
            </div>

            <!-- Grid Shalat -->
            <div class="grid grid-cols-3 gap-2.5 text-center">
                <template x-for="(time, name) in prayerTimes" :key="name">
                    <div class="p-2.5 rounded-2xl border transition duration-300"
                         :class="nextPrayer.name === translatePrayerName(name) ? 'bg-gradient-to-br from-emerald-800 to-emerald-950 text-white border-emerald-850 scale-[1.03] shadow-md font-bold' : 'bg-gray-50/70 text-gray-900 border-gray-100/80 hover:bg-gray-100/50'">
                        <span class="text-[9px] uppercase tracking-wider block opacity-75" x-text="translatePrayerName(name)"></span>
                        <span class="text-xs font-black block mt-1.5" x-text="time"></span>
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
                makkah: 'https://www.islamcan.com/audio/adhan/azan1.mp3',
                madinah: 'https://www.islamcan.com/audio/adhan/azan2.mp3',
                aqsa: 'https://www.islamcan.com/audio/adhan/azan6.mp3',
                turki: 'https://www.islamcan.com/audio/adhan/azan16.mp3'
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
                this.browserBlockedAdzan = false;

                this.adzanAudio.play().catch(err => {
                    console.warn('Gagal memutar suara tes:', err.message);
                    this.browserBlockedAdzan = true;
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

    <!-- Quick Action Library Grid -->
    <div>
        <h3 class="font-bold text-gray-800 text-sm mb-3">Modul Belajar</h3>
        <div class="grid grid-cols-2 gap-4">
            <!-- Quran -->
            <a href="{{ route('murid.quran.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-book-quran"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Al-Qur'an</h4>
                    <span class="text-[9px] text-gray-400">114 Surah</span>
                </div>
            </a>

            <!-- Doa -->
            <a href="{{ route('murid.doa.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-hands-praying"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Doa Harian</h4>
                    <span class="text-[9px] text-gray-400">Kumpulan Doa</span>
                </div>
            </a>

            <!-- Hadist -->
            <a href="{{ route('murid.hadist.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-quote-left"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Hadist Pilihan</h4>
                    <span class="text-[9px] text-gray-400">Hadist Pendek</span>
                </div>
            </a>

            <!-- Cerita -->
            <a href="{{ route('murid.cerita.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-feather-pointed"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Cerita Kisah</h4>
                    <span class="text-[9px] text-gray-400">Kisah Islami</span>
                </div>
            </a>

            <!-- Asmaul Husna -->
            <a href="{{ route('murid.asmaul-husna.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-kaaba"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Asmaul Husna</h4>
                    <span class="text-[9px] text-gray-400">99 Nama Allah</span>
                </div>
            </a>

            <!-- Panduan -->
            <a href="{{ route('murid.panduan.index') }}" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-3 hover:shadow transition">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-compass"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xs text-gray-900 leading-tight">Panduan Praktik</h4>
                    <span class="text-[9px] text-gray-400">Wudhu & Shalat</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Grid -->
    <div class="grid grid-cols-2 gap-4">
        <!-- Attendance Stats -->
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Absensi Bulan Ini</span>
            @php
                $percent = $attendanceStats['total'] > 0 ? round(($attendanceStats['hadir'] / $attendanceStats['total']) * 100) : 100;
            @endphp
            <div class="flex items-baseline space-x-1.5 my-1">
                <span class="text-2xl font-extrabold text-emerald-800">{{ $percent }}%</span>
                <span class="text-[10px] text-gray-500">kehadiran</span>
            </div>
            <div class="text-[9px] text-gray-500 flex justify-between pt-2 border-t border-gray-50">
                <span>Hadir: <strong>{{ $attendanceStats['hadir'] }}</strong></span>
                <span>Absen: <strong class="text-rose-600">{{ $attendanceStats['alpha'] }}</strong></span>
            </div>
        </div>

        <!-- Latest Grades Summary -->
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Nilai Bacaan Terakhir</span>
            <div class="flex items-baseline space-x-1.5 my-1">
                <span class="text-2xl font-extrabold text-amber-500">{{ $latestBaca->nilai ?? '-' }}</span>
                <span class="text-[10px] text-gray-500">predikat</span>
            </div>
            <div class="text-[9px] text-gray-500 truncate pt-2 border-t border-gray-50 font-semibold text-emerald-800">
                @if($latestBaca)
                    {{ $latestBaca->surah_bacaan ?? 'Iqra Hal ' . $latestBaca->jilid_halaman }}
                @else
                    Belum ada nilai
                @endif
            </div>
        </div>
    </div>

    <!-- Badges Section -->
    <div>
        <h3 class="font-bold text-gray-800 text-sm mb-3">Lencana Penghargaanku</h3>
        
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm">
            @php
                $allBadges = \App\Models\Badge::orderBy('id')->get();
                $earnedBadgeIds = $student->badges->pluck('id')->toArray();
            @endphp
            <div class="grid grid-cols-5 gap-3">
                @foreach($allBadges as $bdg)
                    @php
                        $isEarned = in_array($bdg->id, $earnedBadgeIds);
                    @endphp
                    <div class="flex flex-col items-center text-center space-y-1 relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" 
                                class="w-12 h-12 rounded-full flex items-center justify-center border-2 transition duration-300 relative focus:outline-none {{ $isEarned ? 'bg-gradient-to-br from-amber-50 to-amber-100 border-amber-400 shadow-xs scale-105' : 'bg-gray-50 border-gray-150 grayscale opacity-45' }}">
                            @if($isEarned)
                                <i class="{{ $bdg->icon }} text-base"></i>
                                <!-- Sparkle effect -->
                                <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-amber-400 animate-ping opacity-75"></span>
                            @else
                                <i class="fa-solid fa-lock text-gray-300 text-xs"></i>
                            @endif
                        </button>
                        <span class="text-[8px] font-extrabold {{ $isEarned ? 'text-emerald-800' : 'text-gray-400' }} truncate max-w-full leading-tight select-none">
                            {{ $bdg->nama }}
                        </span>
                        
                        <!-- Interactive Tooltip (Alpine.js) -->
                        <div x-show="open" @click.outside="open = false" x-transition.opacity
                             class="absolute z-20 top-14 w-28 p-2 bg-gray-900 text-white rounded-xl shadow-md text-[9px] leading-normal text-center font-medium">
                            <strong>{{ $bdg->nama }}</strong>
                            <p class="text-gray-300 mt-0.5 text-[8px]">{{ $bdg->deskripsi }}</p>
                            <p class="mt-1 font-bold text-[8px] {{ $isEarned ? 'text-amber-300' : 'text-rose-300' }}">
                                {{ $isEarned ? 'Sudah Didapat!' : 'Belum Terbuka' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Announcements Slider/List -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-bold text-gray-800 text-sm">Pengumuman Terbaru</h3>
            <a href="{{ route('murid.pengumuman.index') }}" class="text-[10px] text-emerald-700 font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-3">
            @forelse($announcements as $ann)
                <div class="bg-white rounded-2xl border border-gray-150 p-4 shadow-xs relative overflow-hidden">
                    <span class="absolute top-0 left-0 w-1.5 h-full bg-emerald-700"></span>
                    <h4 class="font-bold text-xs text-gray-900 leading-snug">{{ $ann->judul }}</h4>
                    <span class="text-[8px] text-gray-400 block mt-0.5 mb-2">{{ \Carbon\Carbon::parse($ann->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                    <div class="text-[10px] text-gray-600 line-clamp-2 leading-relaxed">
                        {!! strip_tags($ann->isi) !!}
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-400 shadow-sm">
                    <i class="fa-solid fa-bullhorn text-2xl text-gray-300 mb-2"></i>
                    <p class="text-[10px]">Belum ada pengumuman untuk Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
