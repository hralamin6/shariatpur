<aside
    class="bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen transition duration-200 ease-in-out z-30 shadow-lg"
    :class="{'translate-x-0': isSidebarOpen}">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-4">
        <h1 class="text-2xl font-bold text-primary">
            {{ setup('name', 'Shariatpur City') }}
        </h1>
        <button class="lg:hidden" @click="isSidebarOpen = false">
            <i class='bx bx-x text-2xl'></i>
        </button>
    </div>

    <!-- Sidebar Navigation -->
<nav class="space-y-1 overflow-y-scroll overflow-x-hidden h-[calc(100vh-80px)] custom-scrollbar pb-8">
    <a wire:navigate href="{{route('web.home')}}" class="{{Route::is('web.home')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-home mr-2'></i> @lang('Home')
    </a>
    <a wire:navigate href="{{route('web.notices')}}" class="{{Route::is('web.notices')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-bell mr-2'></i> @lang('Notices')
    </a>
    <a wire:navigate href="{{route('web.doctor')}}" class="{{Route::is('web.doctor.categories')||Route::is('web.doctor')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-user mr-2'></i> @lang('Doctors')
    </a>
    <a wire:navigate href="{{route('web.hospitals')}}" class="{{Route::is('web.hospitals')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-building mr-2'></i> @lang('Hospitals')
    </a>
    <a wire:navigate href="{{route('web.diagnostic_centers')}}" class="{{Route::is('web.diagnostic_centers')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-test-tube mr-2'></i> @lang('Diagnostic Centers')
    </a>
    <!-- New: Bus Routes -->
    <a wire:navigate href="{{route('web.bus.routes')}}" class="{{Route::is('web.bus.routes')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-bus mr-2'></i> @lang('Bus Routes')
    </a>
    <!-- New: Train Routes -->
    <a wire:navigate href="{{route('web.train.routes')}}" class="{{Route::is('web.train.routes')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-train mr-2'></i> @lang('Train Routes')
    </a>
    <!-- New: Launch Routes -->
    <a wire:navigate href="{{route('web.launch.routes')}}" class="{{Route::is('web.launch.routes')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-ship mr-2'></i> @lang('Launch Routes')
    </a>
    <a wire:navigate href="{{route('web.places')}}" class="{{Route::is('web.places')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-map mr-2'></i> @lang('Places')
    </a>
    <a wire:navigate href="{{route('web.hotels')}}" class="{{Route::is('web.hotels')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-building-house mr-2'></i> @lang('Hotels')
    </a>
    <!-- New: Restaurants -->
    <a wire:navigate href="{{route('web.restaurants')}}" class="{{Route::is('web.restaurants')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-restaurant mr-2'></i> @lang('Restaurants')
    </a>
    <!-- New: Hotlines -->
    <a wire:navigate href="{{route('web.hotlines')}}" class="{{Route::is('web.hotlines')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-phone-call mr-2'></i> @lang('Hotlines')
    </a>
    <!-- New: Works -->
    <a wire:navigate href="{{route('web.works')}}" class="{{Route::is('web.works')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-briefcase-alt-2 mr-2'></i> @lang('Works')
    </a>
    <!-- New: Servicemen -->
    <a wire:navigate href="{{route('web.serviceman.types')}}" class="{{Route::is('web.serviceman.types')||Route::is('web.servicemen')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-wrench mr-2'></i> @lang('Servicemen')
    </a>
    <a wire:navigate href="{{route('web.blood_donors')}}" class="{{Route::is('web.blood_donors')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-heart mr-2'></i> @lang('Blood Donors')
    </a>
    <a wire:navigate href="{{route('web.fire_services')}}" class="{{Route::is('web.fire_services')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-hot mr-2'></i> @lang('Fire Services')
    </a>
    <a wire:navigate href="{{route('web.electricity_offices')}}" class="{{Route::is('web.electricity_offices')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-bolt mr-2'></i> @lang('Electricity Offices')
    </a>
    <a wire:navigate href="{{route('web.courier_services')}}" class="{{Route::is('web.courier_services')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-truck mr-2'></i> @lang('Courier Services')
    </a>
    <!-- New: House Types (Houses/Rent) -->
    <a wire:navigate href="{{route('web.house.types')}}" class="{{Route::is('web.house.types')||Route::is('web.houses')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-home-heart mr-2'></i> @lang('Houses')
    </a>
    <!-- New: Car Types (Car Rent) -->
    <a wire:navigate href="{{route('web.car.types')}}" class="{{Route::is('web.car.types')||Route::is('web.cars')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-car mr-2'></i> @lang('Car Rent')
    </a>
    <!-- New: Sell Categories (Classifieds) -->
    <a wire:navigate href="{{route('web.sell.categories')}}" class="{{Route::is('web.sell.categories')||Route::is('web.sells')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-package mr-2'></i> @lang('Sell')
    </a>
    <!-- New: Blogs -->
    <a wire:navigate href="{{route('web.blog.categories')}}" class="{{Route::is('web.blog.categories')||Route::is('web.blogs')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-book mr-2'></i> @lang('Blogs')
    </a>
    <!-- New: News -->
    <a wire:navigate href="{{route('web.news.categories')}}" class="{{Route::is('web.news.categories')||Route::is('web.news')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-news mr-2'></i> @lang('News')
    </a>
    <!-- New: Police -->
    <a wire:navigate href="{{route('web.police')}}" class="{{Route::is('web.police')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bxs-shield-alt-2 mr-2'></i> @lang('Police')
    </a>
    <!-- New: Institutions -->
    <a wire:navigate href="{{route('web.institution.types')}}" class="{{Route::is('web.institutions')?' bg-primary ':''}}flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-primary hover:text-white">
        <i class='bx bx-library mr-2'></i> @lang('Institutions')
    </a>

</nav>
</aside>
