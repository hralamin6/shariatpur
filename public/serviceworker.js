var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/build/manifest.json',
    '/images/icons/icon.png',
    // '/images/icons/icon-96x96.png',
    // '/images/icons/icon-128x128.png',
    // '/images/icons/icon-144x144.png',
    // '/images/icons/icon-152x152.png',
    // '/images/icons/icon-192x192.png',
    // '/images/icons/icon-384x384.png',
    // '/images/icons/icon-512x512.png',
];

// Cache on install
self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => cache.addAll(filesToCache))
            .catch(err => console.error('Error caching files on install:', err))
    );
});

// Clear old caches on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(cacheName => cacheName.startsWith("pwa-") && cacheName !== staticCacheName)
                    .map(cacheName => caches.delete(cacheName))
            );
        }).catch(err => console.error('Error clearing old caches on activate:', err))
    );
});

// Serve from Cache
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
            .catch(() => caches.match('/offline'))
    );
});

// Handle Push Notifications
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'Push Notification';
    const options = {
        body: data.body || 'You have a new notification!',
        icon: data.icon || '/images/icons/icon.png',
        badge: data.icon || '/images/icons/icon.png',
        data: {
            url: data.url || '/app/notifications'
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Handle notification clicks
self.addEventListener('notificationclick', event => {
    // event.notification.close();

    const urlToOpen = event.notification.data && event.notification.data.url ? event.notification.data.url : '/';
    event.waitUntil(
        clients.openWindow(urlToOpen)
    );
});
