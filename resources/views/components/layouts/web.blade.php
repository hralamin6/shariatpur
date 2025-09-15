@extends('components.layouts.base')

@section('body')
    <div class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased h-screen overflow-hidden">

        <div class="relative h-full lg:flex overflow-hidden">
            <!-- Sidebar -->
            <div x-show="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            </div>

            <!-- Sidebar Overlay -->
            @include('components.layouts.web-sidebar')
            <!-- Main Content -->
            <div class="flex-1 flex flex-col h-full overflow-hidden">
                <!-- Header -->
            @include('components.layouts.navbar')
                <!-- Main Content Area -->
                <main class="p-4 sm:p-6 lg:p-8 flex-1 min-h-0 overflow-y-auto overflow-x-auto pb-20">
                    @yield('content')

                    @isset($slot)
                        {{ $slot }}
                    @endisset

                </main>
            </div>
        </div>

        <!-- Bottom Navigation (Mobile Only) -->
        <footer class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-[0_-2px_5px_rgba(0,0,0,0.05)] lg:hidden">
            <div class="flex justify-around items-center h-16">
                    <a href="{{route('web.home')}}" class="{{Route::is('web.home')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
                        <i class="text-2xl bx bxs-home"></i>
                        <span class="text-xs font-medium">home</span>
                    </a>
                    <a href="{{route('web.profile')}}" class="{{Route::is('web.profile')?' text-primary ':' text-gray-500 dark:text-gray-400 hover:text-primary '}} flex flex-col items-center justify-center w-full transition-colors duration-200">
                        <i class="text-2xl bx bxs-user"></i>
                        <span class="text-xs font-medium">profile</span>
                    </a>
            </div>
        </footer>

    </div>


@endsection
