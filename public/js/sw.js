// sw-register.js

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);

            // Set up notification handling
            Notification.requestPermission().then(function(permission) {
                if (permission === 'granted') {
                    registration.showNotification('Welcome!', {
                        body: 'Thanks for visiting our site.',
                        icon: '/img/logo.png'
                    });
                }
            });

        }, function(err) {
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}
