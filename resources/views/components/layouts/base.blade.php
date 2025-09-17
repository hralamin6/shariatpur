<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <style>
        [x-cloak] {
            display: none;
        }
        .flickity-viewport {
            height: 500px !important;
        }
    </style>
    @stack('head')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

    {{--        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">--}}
{{--        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.8/push.min.js" integrity="sha512-eiqtDDb4GUVCSqOSOTz/s/eiU4B31GrdSb17aPAA4Lv/Cjc8o+hnDvuNkgXhSI5yHuDvYkuojMaQmrB5JB31XQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @laravelPWA
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
                        console.log('adsf')
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
                },

                isSidebarOpen: false,
                activeTab: 'হোম',
                services: [
                    { name: 'হাসপাতাল', icon: 'bx bxs-hospital', color: 'text-red-500' },
                    { name: 'বাসের সময়সূচি', icon: 'bx bxs-bus', color: 'text-green-500' },
                    { name: 'ট্রেনের সময়সূচি', icon: 'bx bxs-train', color: 'text-sky-500' },
                    { name: 'দর্শনীয় স্থান', icon: 'bx bxs-camera', color: 'text-purple-500' },
                    { name: 'বাসা ভাড়া', icon: 'bx bxs-home-heart', color: 'text-orange-500' },
                    { name: 'শপিং', icon: 'bx bxs-shopping-bag', color: 'text-pink-500' },
                    { name: 'ফায়ার সার্ভিস', icon: 'bx bxs-hot', color: 'text-red-600' },
                    { name: 'কুরিয়ার সার্ভিস', icon: 'bx bxs-truck', color: 'text-yellow-500' },
                    { name: 'থানা-পুলিশ', icon: 'bx bxs-shield-alt-2', color: 'text-blue-700' },
                    { name: 'ওয়েবসাইট', icon: 'bx bx-globe', color: 'text-indigo-500' },
                    { name: 'বিদ্যুৎ অফিস', icon: 'bx bxs-bulb', color: 'text-yellow-400' },
                    { name: 'ডায়াগনস্টিক', icon: 'bx bxs-flask', color: 'text-teal-500' },
                    { name: 'রক্ত', icon: 'bx bxs-droplet', color: 'text-red-500' },
                    { name: 'হোটেল', icon: 'bx bxs-hotel', color: 'text-amber-600' },
                    { name: 'গাড়ি ভাড়া', icon: 'bx bxs-car', color: 'text-gray-600' },
                    { name: 'মিস্ত্রি', icon: 'bx bxs-wrench', color: 'text-gray-500' },
                    { name: 'জরুরী সেবা', icon: 'bx bxs-phone-call', color: 'text-rose-500' },
                    { name: 'চাকরি', icon: 'bx bxs-briefcase', color: 'text-cyan-500' },
                    { name: 'উদ্যোক্তা', icon: 'bx bxs-dollar-circle', color: 'text-lime-500' },
                    { name: 'শিক্ষক', icon: 'bx bxs-graduation', color: 'text-blue-600' },
                    { name: 'পার্লার', icon: 'bx bxs-florist', color: 'text-fuchsia-500' },
                    { name: 'রেস্টুরেন্ট', icon: 'bx bxs-dish', color: 'text-orange-400' },
                    { name: 'ফ্ল্যাট ও জমি', icon: 'bx bxs-building-house', color: 'text-indigo-600' },
                    { name: 'ভিডিও', icon: 'bx bxs-videos', color: 'text-blue-500' },
                    { name: 'নিউজ', icon: 'bx bxs-news', color: 'text-gray-500' },
                    { name: 'অন্যান্য', icon: 'bx bxs-category', color: 'text-teal-500' },
                    { name: 'প্রোফাইল', icon: 'bx bxs-user-circle', color: 'text-green-500' },
                ],
                navigation: [
                    { name: 'হোম', icon: 'bx bxs-home' },
                    { name: 'যোগাযোগ', icon: 'bx bxs-chat' },
                    { name: 'নোটিফিকেশন', icon: 'bx bxs-bell' },
                    { name: 'প্রোফাইল', icon: 'bx bxs-user' },
                ]
            }

        }
        document.addEventListener('livewire:init', () => {


            Livewire.on('browserMessage', (e) => {

                if (window.location.href === e.messageLink) {
                    console.log('same')

                } else {

                    Push.create(e.userName, {
                        body: e.messageBody,
                        icon: e.messageImage,
                        requireInteraction: true,
                        vibrate: [200, 100],
                        data: {
                            url: e.messageLink
                        },                        onClick: function (event) {
                            if (event.target && event.target.data && event.target.data.url) {
                                window.location.href = event.target.data.url;
                            } else {
                                window.location.href = e.messageLink;
                            }
                            window.focus();
                            this.close();
                        }
                });

                }

            });
        });

    </script>

</head>
<body x-data="setup()" :class="{ 'dark': isDark}" x-cloak="none" class="font-serif">
@yield('body')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

</body>
</html>
