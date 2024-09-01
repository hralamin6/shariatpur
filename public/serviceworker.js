const saveSubscription = async (subscription) =>{
    const response = await fetch('http://127.0.0.1:8000/save-subscription', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(subscription)
    })
    return response.json()
}


var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/offline',
    '/build/manifest.json',
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {

    // const subcription =  self.registration.pushManager.subscribe({
    //     userVisibleOnly: true,
    //     applicationServerKey: urlBase64ToUint8Array('BLjlutgO6ZFApt60msYT_yOcdpDH-RSOtX9hJlOsnqQnhJNkiCbjpBGX3QP-saiYjm1rObcOMHrNqCblzVr0j9M')
    // })
    // const  response =  saveSubscription(subcription)
    // console.log(response)

    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache

self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});
// public/service-worker.js

self.addEventListener('push', function(event) {
    const data = event.data.json();
    const title = data.title || 'Push Notification';
    const options = {
        body: data.body || 'You have a new notification!',
        icon: data.icon || '/path/to/icon.png',
        badge: data.badge || '/path/to/badge.png',
        data: {
            url: data.url || '/app/chat' // Default URL if none is provided
        }

    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
    // clients.openWindow('http://127.0.0.1:8000')

});


self.addEventListener('notificationclick', function(event) {
    event.notification.close();
console.log('Notification clicked');
    // Check if the notification contains a URL
    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    } else {
        // Optionally handle cases where no URL is provided
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});
