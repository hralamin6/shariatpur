<header class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 sticky top-0 z-20 shadow-sm">
    <!-- Mobile Menu Button -->
    <button class="text-2xl text-gray-600 dark:text-gray-300 lg:hidden" @click="isSidebarOpen = !isSidebarOpen">
        <i class='bx bx-menu'></i>
    </button>
    <!-- Desktop Title (or search bar) -->
    <a wire:navigate href="{{route('web.home')}}" class="flex gap-2 items-center text-center ml-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
        <img src="{{getSettingImage('iconImage')}}" alt="" class="h-8 rounded-full object-cover">
        <span class="text-2xl font-bold text-teal-400">{{setup('name', 'Shariatpur City')}}</span>
    </a>


    <div class="items-center justify-between lg:space-x-12 hidden ml-auto lg:flex">
        <a href="{{route('web.notices')}}" class="{{Route::is('web.notices')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
            <span class="font-medium" >@lang('Notices')</span>
        </a>
        <a href="{{route('web.news')}}" class="{{Route::is('web.news')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
            <span class="font-medium" >@lang('News')</span>
        </a>
        <a href="{{route('web.profile')}}" class="{{Route::is('web.profile')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
            <span class="font-medium" >@lang('Profile')</span>
        </a>
    </div>
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
    <button @click="toggleTheme()" class="text-2xl text-gray-600 dark:text-gray-300 ml-auto mt-1.5">
        <i class='bx' :class="isDark ? 'bxs-sun' : 'bxs-moon'"></i>
    </button>
</header>
