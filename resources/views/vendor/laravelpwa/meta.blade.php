<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="@yield('description', 'Easily make and customize your class note according to subject and chapter wise') - {{config('app.name')}}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta property="og:title" content="@yield('title', 'Home Page') - {{config('app.name')}}" />
<meta property="og:description" content="@yield('description', 'Easily make and customize your class note according to subject and chapter wise') - {{config('app.name')}}" />
<meta property="og:url" content="@yield('url', config('app.url'))" />
<meta property="og:image" content="@yield('image', url(asset('favicon.ico')))" />
<meta property="og:image:secure_url" content="{{ url(asset('favicon.ico')) }}" />
<meta property="og:site_name" content="{{config('app.name')}}" />
<meta property="og:image:width" content="1536" />
<meta property="og:image:height" content="1024" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:description" content="@yield('description', 'Easily make and customize your class note according to subject and chapter wise') - {{config('app.name')}}" />
<meta name="twitter:title" content="@yield('title', 'Home Page') - {{config('app.name')}}" />
<meta name="twitter:image" content="{{ getSettingImage('iconImage') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="shortcut icon" href="{{ getSettingImage('iconImage') }}">

{{--@dd(data_get(end($config['icons']), 'sizes'))--}}

<!-- Web Application Manifest -->
<link rel="manifest" href="{{ route('laravelpwa.manifest') }}">
<!-- Chrome for Android theme color -->
<meta name="theme-color" content="{{ $config['theme_color'] }}">

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="{{ $config['display'] == 'standalone' ? 'yes' : 'no' }}">
<meta name="application-name" content="{{ $config['short_name'] }}">
<link rel="icon" sizes="{{ data_get(end($config['icons']), 'sizes') }}" href="{{ getSettingImage('iconImage') }}">

<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable" content="{{ $config['display'] == 'standalone' ? 'yes' : 'no' }}">
<meta name="apple-mobile-web-app-status-bar-style" content="{{  $config['status_bar'] }}">
<meta name="apple-mobile-web-app-title" content="{{ $config['short_name'] }}">
<link rel="apple-touch-icon" href="{{ data_get(end($config['icons']), 'src') }}">


<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ getSettingImage('iconImage') }}" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

<!-- Tile for Win8 -->
<meta name="msapplication-TileColor" content="{{ $config['background_color'] }}">
<meta name="msapplication-TileImage" content="{{ data_get(end($config['icons']), 'src') }}">

<script type="text/javascript">
    // Initialize the service worker
    const publicVapidKey = 'BMd5P4XcEt9LqKKu3tf5CdBfu2WKwYRu18iS2vU5UwL65SCIzWqqX81hdFh3ncQdWOiC2IjlJ2sUdmJ127tf3U0';

    // Register Service Worker
    if ('serviceWorker' in navigator) {
        registerServiceWorker().catch(err => console.error('Service Worker registration failed:', err));
    }

    async function registerServiceWorker() {
        try {
            const register = await navigator.serviceWorker.register('/serviceworker.js');
            console.log('Service Worker registered:', register);

            const subscription = await subscribeUser(register);
            console.log('Push Subscription:', subscription);

            await sendSubscriptionToServer(subscription);
        } catch (error) {
            console.error('Error in registering Service Worker:', error);
        }
    }

    async function subscribeUser(register) {
        return await register.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
        });
    }

    async function sendSubscriptionToServer(subscription) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        await fetch('/subscribe', {
            method: 'POST',
            body: JSON.stringify(subscription),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            }
        });
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = base64String.replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    Notification.requestPermission().then(permission => {
        if (permission !== 'granted') {
            console.log('Notification permission denied');
        }
    });
</script>
