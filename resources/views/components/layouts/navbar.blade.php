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
    <!-- Dark mode toggle button -->
    <button @click="toggleTheme()" class="text-2xl text-gray-600 dark:text-gray-300 ml-auto mt-1.5">
        <i class='bx' :class="isDark ? 'bxs-sun' : 'bxs-moon'"></i>
    </button>
    <div class="flex items-center justify-between lg:space-x-12 hidden lg:flex">
        <template x-for="nav in navigation" :key="nav.name">
            <a href="#" class="flex flex-col items-center justify-center w-full transition-colors duration-200"
               :class="activeTab === nav.name ? 'text-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary'"
               @click.prevent="activeTab = nav.name">
                <i class="text-2xl" :class="nav.icon"></i>
{{--                <span class="text-xs font-medium" x-text="nav.name"></span>--}}
            </a>
        </template>
    </div>
</header>
