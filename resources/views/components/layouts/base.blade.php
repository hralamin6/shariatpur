<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @hasSection('title')

        <title>@yield('title') - {{ setup('name', 'starter') }}</title>
    @else
        <title>{{ setup('name', 'starter') }}</title>
    @endif
{{--    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js" ></script>--}}
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @laravelPWA
    <script>
        const setup = () => {

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
                init(){
                    setColors(this.color)
                }



            }
        }

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

<script>

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
</script>
</body>
</html>
