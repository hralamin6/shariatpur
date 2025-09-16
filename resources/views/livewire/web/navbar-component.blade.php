<header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 sticky top-0 z-20 shadow-sm">
    <!-- Mobile Menu Button -->
    <button class="text-2xl text-gray-600 dark:text-gray-300 lg:hidden" @click="isSidebarOpen = !isSidebarOpen">
        <i class='bx bx-menu'></i>
    </button>
    <!-- Desktop Title (or search bar) -->
    <a wire:navigate href="{{route('web.home')}}" class="flex gap-2 items-center text-center ml-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
        <img src="{{asset('images/icons/icon.png')}}" alt="" class="h-8 rounded-full object-cover">
        <span class="text-xl font-bold text-teal-400">{{setup('name', 'Shariatpur City')}}</span>
    </a>


    <div class="items-center justify-between lg:space-x-12 hidden ml-auto lg:flex">
        <a href="{{route('web.notices')}}" class="{{Route::is('web.notices')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
            <span class="font-medium" >@lang('Notices')</span>
        </a>
        <a href="{{route('web.news')}}" class="{{Route::is('web.news')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
            <span class="font-medium" >@lang('News')</span>
        </a>
        <a href="{{route('web.blogs')}}" class="{{Route::is('web.blogs')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
            <span class="font-medium" >@lang('Blogs')</span>
        </a>
    </div>

    <div class="ml-auto flex justify-between gap-3 mt-1.5">
    <div class="relative inline-block text-left" x-data="{lang:false}">
        <div>
                    <span class="rounded-full shadow-sm">
                        <button @click="lang=!lang" @click.stop type="button" class="menu capitalize">
                            <i class='bx bx-globe text-2xl -mt-1'></i>
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
            </div>
        </div>

    </div>

        <button @click="toggleTheme()" class="text-2xl -mt-0.5 text-gray-600 dark:text-gray-300  ">
            <i class='bx font-medium ' :class="isDark ? 'bxs-sun' : 'bxs-moon'"></i>
        </button>
    @auth()
        <div class="relative inline-block text-left" x-data="{ dropdownOpen: false }">
            <div>
                    <span class="rounded-full shadow-sm">
                        <button @click="dropdownOpen = ! dropdownOpen" @click.stop type="button" class="menu capitalize">
                            <i class='bx bx-user-circle text-2xl -mt-1'></i>
                        </button>
                    </span>
            </div>
            <div x-cloak x-show="dropdownOpen" @click.outside="dropdownOpen=false" @click="dropdownOpen = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg">
                <div class="rounded-md bg-lightBg dark:bg-darkBg  shadow-xs">
                    <div class="py-1 flex flex-col space-y-1 cursor-pointer p-2">
                        <a wire:navigate href="{{ route('web.profile') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 {{Route::is('web.profile')?'bg-gray-300 dark:bg-gray-500':''}} hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('Profile')</a>
                        <a wire:click.prevent="logout" class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:dark:bg-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 transition duration-150 ease-in-out">@lang('logout')</a>
                    </div>
                </div>
            </div>
        </div>
    @endauth
    @guest()
        <a wire:navigate href="{{route('register')}}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </a>
    @endguest
    </div>

</header>
