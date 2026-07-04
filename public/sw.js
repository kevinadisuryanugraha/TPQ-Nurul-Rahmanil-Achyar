const CACHE_NAME = 'lms-tpq-cache-v1';
const OFFLINE_URL = '/offline';

// Assets to precache
const PRECACHE_ASSETS = [
  OFFLINE_URL,
  '/manifest.webmanifest',
  '/images/icon-192.png',
  '/images/icon-512.png',
  '/favicon.ico',
];

// Install Event - Precache fallback and core assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS);
    }).then(() => self.skipWaiting())
  );
});

// Activate Event - Clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event - Routing strategies
self.addEventListener('fetch', (event) => {
  // Only handle GET requests and exclude Laravel dev tools / hot reload
  if (event.request.method !== 'GET' || event.request.url.includes('/@vite/') || event.request.url.includes('/hot')) {
    return;
  }

  // Exclude unsupported schemes (like chrome-extension)
  if (!event.request.url.startsWith('http://') && !event.request.url.startsWith('https://')) {
    return;
  }

  const url = new URL(event.request.url);

  // 1. NAVIGATION REQUESTS (HTML page loads)
  if (event.request.mode === 'navigate') {
    // Network-First for core dynamic screens
    if (
      url.pathname.includes('/murid/dashboard') ||
      url.pathname.includes('/murid/nilai') ||
      url.pathname.includes('/murid/absensi') ||
      url.pathname.includes('/murid/pengumuman')
    ) {
      event.respondWith(
        fetch(event.request)
          .then((response) => {
            // Save copy to cache
            let copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
            return response;
          })
          .catch(() => {
            // Load from cache fallback if offline
            return caches.match(event.request).then((cachedResponse) => {
              return cachedResponse || caches.match(OFFLINE_URL);
            });
          })
      );
      return;
    }

    // Stale-While-Revalidate for libraries (Quran, Doa, Hadist, Cerita, Panduan)
    if (
      url.pathname.includes('/murid/quran') ||
      url.pathname.includes('/murid/doa') ||
      url.pathname.includes('/murid/hadist') ||
      url.pathname.includes('/murid/cerita') ||
      url.pathname.includes('/murid/panduan')
    ) {
      event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
          const fetchPromise = fetch(event.request).then((networkResponse) => {
            if (networkResponse.status === 200) {
              let copy = networkResponse.clone();
              caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
            }
            return networkResponse;
          }).catch(() => {
            // Suppress network errors offline
          });

          return cachedResponse || fetchPromise || caches.match(OFFLINE_URL);
        })
      );
      return;
    }

    // Default Fallback Strategy for any other page loads
    event.respondWith(
      fetch(event.request).catch(() => {
        return caches.match(event.request).then((cachedResponse) => {
          return cachedResponse || caches.match(OFFLINE_URL);
        });
      })
    );
    return;
  }

  // 2. STATIC ASSETS (CSS, JS, Fonts, Images)
  // Cache First strategy
  if (
    url.pathname.includes('/build/assets/') || 
    url.pathname.includes('/images/') || 
    url.pathname.includes('/fonts/') ||
    event.request.destination === 'font' ||
    event.request.destination === 'image' ||
    event.request.destination === 'style' ||
    event.request.destination === 'script'
  ) {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }

        return fetch(event.request).then((networkResponse) => {
          if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
            return networkResponse;
          }

          let copy = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
          return networkResponse;
        }).catch(() => {
          // Silent catch for images/assets offline
        });
      })
    );
  }
});
