const CACHE_NAME   = 'offline';
const filesToCache = [
  '/', 
  '/offline.html'
];

// Preload our core pages
function preLoad() {
  return caches.open(CACHE_NAME)
    .then(cache => cache.addAll(filesToCache));
}

self.addEventListener('install', event => {
  event.waitUntil(preLoad());
  self.skipWaiting();
});

self.addEventListener('activate', event => {
  // Immediately take control
  event.waitUntil(self.clients.claim());
});

// Try network first; if it's a 404 or fails, go to cache.
function checkResponse(request) {
  return fetch(request).then(response => {
    if (response.status === 404) {
      // Force fallback to cache
      return Promise.reject('404');
    }
    return response;
  });
}

// Cache new requests (HTTP/S only)
function addToCache(request) {
  if (!request.url.startsWith('http')) {
    return Promise.resolve();
  }
  return caches.open(CACHE_NAME).then(cache => {
    return fetch(request).then(response => {
      // Only cache successful GETs
      if (response.ok && request.method === 'GET') {
        cache.put(request, response.clone());
      }
    });
  });
}

// Return from cache, or fallback to offline.html
function returnFromCache(request) {
  return caches.open(CACHE_NAME).then(cache => {
    return cache.match(request).then(matching => {
      if (matching) {
        return matching;
      }
      // If nothing in cache, serve offline page
      return cache.match('/offline.html');
    });
  });
}

self.addEventListener('fetch', event => {
  // 1. Try network → 2. If it 404s or errors → 3. Serve from cache
  event.respondWith(
    checkResponse(event.request)
      .catch(() => returnFromCache(event.request))
  );

  // Meanwhile, update cache in the background
  if (event.request.url.startsWith('http')) {
    event.waitUntil(addToCache(event.request));
  }
});
