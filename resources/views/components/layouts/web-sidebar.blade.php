<aside
    class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen transition duration-200 ease-in-out z-30 shadow-lg"
    :class="{'translate-x-0': isSidebarOpen}">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-4">
        <h1 class="text-2xl font-bold text-primary">
            Faridpur City<span class="font-normal text-gray-500 dark:text-gray-400">app</span>
        </h1>
        <button class="lg:hidden" @click="isSidebarOpen = false">
            <i class='bx bx-x text-2xl'></i>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="space-y-1">
        <a wire:navigate href="{{route('web.home')}}" class="{{Route::is('web.home')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary text-white">
            <i class='bx bxs-home mr-2'></i> হোম
        </a>
        <a wire:navigate href="{{route('web.doctor')}}" class="{{Route::is('web.doctor.categories')||Route::is('web.doctor')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-user mr-2'></i> Doctors
        </a>
        <a wire:navigate href="{{route('web.hospitals')}}" class="{{Route::is('web.hospitals')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bx-building mr-2'></i> Hospitals
        </a>
        <!-- New: Bus Routes -->
        <a wire:navigate href="{{route('web.bus.routes')}}" class="{{Route::is('web.bus.routes')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bx-bus mr-2'></i> Bus Routes
        </a>
        <!-- New: Train Routes -->
        <a wire:navigate href="{{route('web.train.routes')}}" class="{{Route::is('web.train.routes')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bx-train mr-2'></i> Train Routes
        </a>
        <!-- New: Launch Routes -->
        <a wire:navigate href="{{route('web.launch.routes')}}" class="{{Route::is('web.launch.routes')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-ship mr-2'></i> Launch Routes
        </a>
        <a wire:navigate href="{{route('web.places')}}" class="{{Route::is('web.places')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bx-map mr-2'></i> Places
        </a>
        <a wire:navigate href="{{route('web.fire_services')}}" class="{{Route::is('web.fire_services')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-hot mr-2'></i> Fire Services
        </a>
        <a href="#" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-chat mr-2'></i> যোগাযোগ
        </a>
        <a href="#" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-bell mr-2'></i> নোটিফিকেশন
        </a>
        <a href="#" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-cog mr-2'></i> সেটিংস
        </a>
        <a href="#" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-log-out mr-2'></i> লগ আউট
        </a>
        <!-- New: House Types (Houses/Rent) -->
        <a wire:navigate href="{{route('web.house.types')}}" class="{{Route::is('web.house.types')||Route::is('web.houses')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
            <i class='bx bxs-home-heart mr-2'></i> Houses
        </a>
    </nav>
</aside>
