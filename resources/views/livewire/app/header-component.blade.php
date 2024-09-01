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
                <i class='bx bx-search text-2xl font-medium -mt-1'></i>
            </a>
            <a @click="toggleTheme()">
                <i x-show="isDark" class='bx bx-moon text-2xl font-medium -mt-1'></i>
                <i x-show="!isDark" class='bx bx-sun text-2xl font-medium -mt-1'></i>

            </a>
            <a href="">
                <i class='bx bx-bell text-2xl font-medium -mt-1'></i>
            </a>

            <a class="relative" href="{{route('app.chat')}}" wire:navigate>
                <i class='bx bx-chat text-2xl font-medium -mt-1 {{$unReadMessageCount?'bx-teda':''}}'></i>
                @if($unReadMessageCount)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center p-0.5 text-xs text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{$unReadMessageCount}}</span>
                @endif

            </a>

            <div class="relative inline-block text-left" x-data="{lang:false}">
                <div>
                    <span class="rounded-full shadow-sm">
                        <button @click="lang=!lang" @click.stop type="button" class="menu capitalize">
                            <i class='bx bx-globe text-2xl font-medium -mt-1'></i>
                        </button>
                    </span>
                </div>
                <div
                    x-cloak
                    x-show="lang"
                    @click.outside="lang=false"
                    @click="lang=false"
                    class="absolute right-0 mt-2 w-48 origin-top-right rounded-lg shadow-lg bg-white dark:bg-darkBg"
                >
                    <div class="py-1 space-y-2">
                        <a
                            wire:click.prevent="$set('locale', 'bn')"
                            class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg {{ session()->get('locale') == 'bn' ? 'bg-gray-200 dark:bg-gray-700' : '' }} hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                        >
                            @lang('bangla')
                        </a>
                        <a
                            wire:click.prevent="$set('locale', 'en')"
                            class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg {{ session()->get('locale') == 'en' ? 'bg-gray-200 dark:bg-gray-700' : '' }} hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                        >
                            @lang('english')
                        </a>
                        <a
                            wire:click.prevent="$set('locale', 'ar')"
                            class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg {{ session()->get('locale') == 'ar' ? 'bg-gray-200 dark:bg-gray-700' : '' }} hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                        >
                            @lang('arabic')
                        </a>
                    </div>
                </div>

            </div>
            @auth()
                <div class="relative inline-block text-left" x-data="{ dropdownOpen: false }">
                    <div>
                    <span class="rounded-full shadow-sm">
                        <button @click="dropdownOpen = ! dropdownOpen" @click.stop type="button" class="menu capitalize">
                            <i class='bx bx-user-circle text-2xl font-medium -mt-1'></i>
                        </button>
                    </span>
                    </div>
                    <div x-cloak x-show="dropdownOpen" @click.outside="dropdownOpen=false" @click="dropdownOpen = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
                        <div class="rounded-md bg-lightBg dark:bg-darkBg  shadow-xs">
                            <div class="py-1 flex flex-col space-y-1 cursor-pointer p-2">
                                @role('admin')
                                    <a href="{{ route('app.dashboard') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 {{Route::is('dashboard')?'bg-gray-300 dark:bg-gray-500':''}} hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('dashboard')</a>
                                @endrole
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
