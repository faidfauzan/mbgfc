const CACHE_NAME = 'mbgfc-pwa-v1';
const OFFLINE_URL = '/offline';

const URLS_TO_CACHE = [
    OFFLINE_URL,
    // Kita juga bisa tambahkan css/js jika diperlukan
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(URLS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

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
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    // Only cache GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request).catch((error) => {
            // Jika network gagal, kembalikan dari cache atau offline page
            return caches.match(event.request).then((response) => {
                if (response) {
                    return response;
                }
                
                // Jika request adalah navigasi halaman, kembalikan halaman offline
                if (event.request.mode === 'navigate') {
                    return caches.match(OFFLINE_URL);
                }
                
                return Promise.reject('Offline');
            });
        })
    );
});
