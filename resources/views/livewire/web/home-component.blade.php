<div class="max-w-7xl mx-auto">
    <!-- Image Banner/Slider -->
    <div class="relative rounded-xl overflow-hidden mb-6 shadow-md">
        <img src="https://placehold.co/1200x400/a0c4ff/ffffff?text=Welcome+to+Faridpur+City"
             alt="Faridpur City Banner"
             class="w-full h-auto object-cover"
             onerror="this.onerror=null;this.src='https://placehold.co/1200x400/cccccc/ffffff?text=Image+Not+Found';">
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
            <span class="block w-3 h-3 bg-white rounded-full"></span>
            <span class="block w-3 h-3 bg-white/50 rounded-full"></span>
            <span class="block w-3 h-3 bg-white/50 rounded-full"></span>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        <a wire:navigate href="{{route('web.doctor.categories')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-blue-500">
                <i class="bx bxs-user-plus"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Doctors')</span>
        </a>
        <a wire:navigate href="{{route('web.hospitals')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-green-500">
                <i class="bx bx-building"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Hostipals')</span>
        </a>
        <a wire:navigate href="{{route('web.bus.routes')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-green-500">
                <i class="bx bx-bus"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Buses')</span>
        </a>
        <a wire:navigate href="{{route('web.train.routes')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-indigo-500">
                <i class="bx bx-train"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Trains')</span>
        </a>
        <a wire:navigate href="{{route('web.launch.routes')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-cyan-500">
                <i class="bx bxs-ship"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Launches')</span>
        </a>
        <a wire:navigate href="{{route('web.places')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-emerald-500">
                <i class="bx bx-map"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Places</span>
        </a>
        <a wire:navigate href="{{route('web.fire_services')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-red-500">
                <i class="bx bxs-hot"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Fire Services')</span>
        </a>

        <template x-for="item in services" :key="item.name">
            <a href="#" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="text-4xl mb-2" :class="item.color">
                    <i :class="item.icon"></i>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="item.name"></span>
            </a>
        </template>
    </div>
</div>
