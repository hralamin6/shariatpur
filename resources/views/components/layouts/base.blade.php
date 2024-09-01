<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @hasSection('title')

        <title>@yield('title') - {{ setup('name', 'starter') }}</title>
    @else
        <title>{{ setup('name', 'starter') }}</title>
    @endif
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
{{--    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">--}}
    <style>
        [x-cloak] {
            display: none;
        }
        .flickity-viewport {
            height: 500px !important;
        }
        @media print {
            #header, #footer, #url {
                display: none;
            }
        }
    </style>
    @vite(['resources/js/bootstrap.js'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @laravelPWA
        <style>
            .trix-content p,
            .trix-content h1,
            .trix-content h2,
            .trix-content h3,
            .trix-content a,
            .trix-content ul,
            .trix-content ol {
                @apply text-gray-800 dark:text-gray-100;
            }

            .trix-content a {
                @apply text-blue-600 dark:text-blue-400;
            }

            .trix-content blockquote {
                @apply bg-gray-100 dark:bg-gray-800;
                @apply border-l-4 border-gray-300 dark:border-gray-600;
            }
        </style>
    <script>

        const setup = () => {
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (event) => {


                // event.preventDefault();
                // Stash the event so it can be triggered later
                deferredPrompt = event;

                  // Show your custom install prompt (e.g., show a button)
                showInstallButton(); // Define this function to show your install button or popup
                console.log(`'beforeinstallprompt' event was fired.`);
            });

            document.getElementById('installButton').addEventListener('click', () => {
                // Hide the custom install prompt (e.g., hide the button)
                hideInstallButton(); // Define this function to hide your install button or popup

                // Show the install prompt
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                        } else {
                            console.log('User dismissed the A2HS prompt');
                        }
                        deferredPrompt = null;
                    });
                }
            });
            function showInstallButton() {
                // Implement this function to show the install button or popup
                document.getElementById('installButton').style.display = 'block';

            }
            function hideInstallButton() {
                // Implement this function to hide the install button or popup
                document.getElementById('installButton').style.display = 'none';

            }



            const getTheme = () => {
                if (window.localStorage.getItem('dark')) {
                    return JSON.parse(window.localStorage.getItem('dark'))
                }

                return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
            }

            const setTheme = (value) => {
                window.localStorage.setItem('dark', value)
            }

            const getColor = () => {

                if (window.localStorage.getItem('color')) {
                    return window.localStorage.getItem('color')
                }

                return 'green'
            }

            const setColors = (color) => {
                // console.log(color)
                const root = document.documentElement
                root.style.setProperty('--color-primary', `var(--color-${color})`)
                root.style.setProperty('--color-primary-50', `var(--color-${color}-50)`)
                root.style.setProperty('--color-primary-100', `var(--color-${color}-100)`)
                root.style.setProperty('--color-primary-light', `var(--color-${color}-light)`)
                root.style.setProperty('--color-primary-lighter', `var(--color-${color}-lighter)`)
                root.style.setProperty('--color-primary-dark', `var(--color-${color}-dark)`)
                root.style.setProperty('--color-primary-darker', `var(--color-${color}-darker)`)
                this.selectedColor = color
                window.localStorage.setItem('color', color)
                //
            }

            return {
                nav: false,
                loading: true,
                sidebarOpen: false,
                isDark: getTheme(),
                toggleTheme() {
                    this.isDark = !this.isDark
                    setTheme(this.isDark)
                },

                color: getColor(),
                selectedColor: 'green',

                setColors,
                isVisible: false,
                deferredPrompt: null,
                init() {
                    setColors(this.color)

                    window.addEventListener('beforeinstallprompt', (e) => {
                        e.preventDefault();
                        this.deferredPrompt = e;
                        this.isVisible = true;
                    });

                    // Optionally hide the popup after some time
                    setTimeout(() => {
                        this.isVisible = false;
                    }, 10000); // Hide after 10 seconds

                },
                installPWA() {
                    if (this.deferredPrompt) {
                        this.deferredPrompt.prompt();
                        this.deferredPrompt.userChoice.then((result) => {
                            if (result.outcome === 'accepted') {
                                console.log('User accepted the A2HS prompt');
                            } else {
                                console.log('User dismissed the A2HS prompt');
                            }
                            this.deferredPrompt = null;
                            this.isVisible = false;
                        });
                    }
                },
                closePopup() {
                    this.isVisible = false;
                }
            }

        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('browserMessage', (e) => {

                if (window.location.pathname === '/app/chat') {
                   console.log('chat')
                } else {
                    const notification = new Notification(e.userName, {
                        body: e.messageBody,
                        icon: 'https://unmeshbd.com/media/Images/Unmesh/logo.png',
                        requireInteraction: true // Keeps the notification until the user interacts
                    });

                    // Set a timeout to close the notification after a specific time
                    setTimeout(() => {
                        notification.close();
                    }, 20000); // 10 seconds

                    // Handle click event
                    notification.onclick = function (e) {
                        console.log(e)
                        e.preventDefault(); // Prevent the default action
                        window.location.href = '/app/chat'; // Navigate to the link
                        window.focus();
                        this.close(); // Close the notification
                    };

                    // Handle vibration (works on mobile devices with proper permissions)
                    if ("vibrate" in navigator) {
                        navigator.vibrate([200, 100]); // Vibration pattern
                    }
                }

            });
        });
    </script>


</head>
<body x-data="setup()" :class="{ 'dark': isDark}" x-cloak="none" class="">
{{--<button id="installButton"--}}
{{--        style="display:none;"--}}
{{-->Install App</button>--}}
@yield('body')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{--<script src="{{ asset('js/sa.js') }}"> </script>--}}
<x-livewire-alert::scripts />

<button id="installButton" style="display:none;">Install App</button>
<div x-show="isVisible" x-transition.opacity class="fixed inset-0 flex items-center justify-center bg-white dark:bg-gray-800 bg-opacity-90 dark:bg-opacity-90 transition-opacity duration-300 ease-in-out z-50">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 max-w-sm mx-auto w-full">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Install Our App</h2>
        <p class="mt-2 text-gray-600 dark:text-gray-300">For the best experience, install our app on your device.</p>
        <div class="mt-4 flex justify-between">
            <button @click="installPWA" class="bg-blue-600 dark:bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">Install</button>
            <button @click="closePopup" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 py-2 px-4 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-400">Close</button>
        </div>
    </div>
</div>
<script>


</script>

</body>
</html>
