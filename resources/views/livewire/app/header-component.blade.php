<header class="w-full h-14 bg-white dark:bg-darkSidebar border-b dark:border-gray-600" x-data="{search: false}">
    <div class="flex justify-between gap-6 px-4 pt-4 relative inline-block">
        <div class="flex justify-start space-x-4 md:space-x-7 text-gray-500 dark:text-gray-200 text-sm z-0" :class="{'hidden': search}">
            <a class="md:hidden" @click="nav= !nav" x-on:click.stop>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </a>
            <a href="" class="capitalize hidden md:block">home</a>
            <a href="" class="capitalize hidden md:block">contact</a>

        </div>
        <div class="w-full hidden md:block">
            <div class="flex justify-center space-x-2 text-gray-500 dark:text-gray-200 text-sm">
                <input type="search" class=" top-0 inset-0 w-72 mb-4 border-none dark:bg-gray-600 bg-gray-200 dark:placeholder-gray-300 text-xs rounded-2xl h-8" placeholder="Type your query…">
                <a href="" class="mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 dark:text-gray-200 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </a>
            </div>
        </div>

        <div x-cloak x-show="search" class="block px-4 w-full absolute inset-0 inline-flex items-center justify-center z-50 flex space-x-2 text-gray-500 text-sm mt-5 font-bold" x-transition>
            <input type="search" class="dark:bg-gray-600 dark:placeholder-gray-300 w-full bg-gray-300 text-gray-500 dark:text-gray-200 h-10 rounded border-none text-sm" autofocus placeholder="Type your query…">
            <a href="" class="cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 text-gray-600 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </a>
            <a href="" class="cursor-pointer" @click.prevent="search=false">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 text-gray-600 dark:text-gray-200" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>

        </div>

        <div class="flex text-base font-bold justify-end space-x-8 md:space-x-12 text-gray-600 dark:text-gray-200 text-sm font-bold z-0" :class="{'hidden': search}">
            <a class="md:hidden" @click.prevent="search=!search">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 font-bold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </a>
            <a @click="toggleTheme()">
                <svg x-cloak="" x-show="isDark"  xmlns="http://www.w3.org/2000/svg" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-cloak="" xmlns="http://www.w3.org/2000/svg" x-show="!isDark" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </a>
            <a href="">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

            </a>

            <a class="relative" href="{{route('app.chat')}}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="absolute top-0 right-0 inline-flex items-center justify-center p-0.5 text-xs text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{$unReadMessageCount}}</span>
            </a>

            <div class="relative inline-block text-left" x-data="{lang:false}">
                <div>
                    <span class="rounded-full shadow-sm">
                        <button @click="lang=!lang" @click.stop type="button" class="menu capitalize">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802" /></svg>
                        </button>
                    </span>
                </div>
                <div x-cloak x-show="lang" @click.outside="lang=false" @click="lang=false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
                    <div class="rounded-md bg-lightBg dark:bg-darkBg   shadow-xs">
                        <div class="py-1 flex flex-col space-y-1 cursor-pointer p-2">
                            <a wire:click.prevent="$set('locale', 'bn')" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 {{session()->get('locale')=='bn'?'bg-gray-300 dark:bg-gray-500':''}} hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('bangla')</a>
                            <a wire:click.prevent="$set('locale', 'en')" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 {{session()->get('locale')=='en'?'bg-gray-300 dark:bg-gray-500':''}} hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('english')</a>
                            <a wire:click.prevent="$set('locale', 'ar')" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 {{session()->get('locale')=='ar'?'bg-gray-300 dark:bg-gray-500':''}} hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('arabic')</a>
                        </div>
                    </div>
                </div>
            </div>
            @auth()
                <div class="relative inline-block text-left" x-data="{ dropdownOpen: false }">
                    <div>
                    <span class="rounded-full shadow-sm">
                        <button @click="dropdownOpen = ! dropdownOpen" @click.stop type="button" class="menu capitalize">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                        </button>
                    </span>
                    </div>
                    <div x-cloak x-show="dropdownOpen" @click.outside="dropdownOpen=false" @click="dropdownOpen = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
                        <div class="rounded-md bg-lightBg dark:bg-darkBg  shadow-xs">
                            <div class="py-1 flex flex-col space-y-1 cursor-pointer p-2">
                                @can('isAdmin')
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 {{Route::is('dashboard')?'bg-gray-300 dark:bg-gray-500':''}} hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('dashboard')</a>
                                @endcan
                                <a wire:click.prevent="logout" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('logout')</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
            @guest()
                <a href="{{route('login')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </a>
                <a href="{{route('register')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </a>
            @endguest
        </div>
    </div>

</header>
