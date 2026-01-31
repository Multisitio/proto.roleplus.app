const CACHE_NAME = 'minimal-cache-v1';
const OFFLINE_URL = 'offline.html';

// Instalación del Service Worker
self.addEventListener('install', event => {
	console.log('[Service Worker] Installing...');
	event.waitUntil(
		caches.open(CACHE_NAME)
			.then(cache => {
				console.log('[Service Worker] Caching offline page');
				return cache.addAll([OFFLINE_URL]);
			})
			.catch(error => console.error('[Service Worker] Error caching offline page:', error))
	);
	self.skipWaiting(); // Activa el SW inmediatamente
});

// Manejo de las solicitudes de red
self.addEventListener('fetch', event => {
	event.respondWith(
		fetch(event.request)
			.catch(() => caches.match(OFFLINE_URL))
	);
});
