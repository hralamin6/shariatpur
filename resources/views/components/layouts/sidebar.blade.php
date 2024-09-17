<nav x-cloak @click.outside="nav = false"
     class="md:block shadow-2xl bg-white overflow-y-hidden overflow-x-hidden inset-y-0 z-10 fixed md:relative flex-shrink-0 w-64 overflow-y-auto bg-white dark:bg-darkSidebar dark:scrollbar-thin-dark scrollbar-thin-light"
     :class="{'hidden': nav == false}">
    <div class="h-14 border-b dark:border-gray-600 flex px-4 py-2 gap-3 items-center">
    <a href="{{route('web.home')}}" wire:navigate
        class="w-10 h-10 rounded-full bg-purple-600 border dark:border-gray-600 shadow-xl overflow-hidden flex items-center justify-center">
        <img src="{{ getSettingImage('iconImage', 'icon') }}" alt="" onerror="{{ getErrorImage() }}"
             class="w-full h-full object-cover">
    </a>
        <a href="{{route('web.home')}}" wire:navigate class="text-xl text-gray-500 font-mono dark:text-gray-300">{{ setup('name', 'laravel') }}</a>
    </div>

    <div class="h-screen  overflow-y-auto scrollbar-none">
        <div class="h-16 border-b dark:border-gray-600 flex px-4 py-2 gap-3">
    <a href="{{route('app.profile')}}" wire:navigate
        class="w-10 h-10 rounded-full bg-purple-600 border dark:border-gray-600 shadow-xl overflow-hidden flex items-center justify-center">
                <img src="{{ getUserProfileImage(auth()->user()) }}" alt=""
                     onerror="{{ getErrorProfile(auth()->user()) }}" class="w-full h-full object-cover">
            </a>
            <a href="{{route('app.profile')}}" wire:navigate class="my-auto text-sm text-gray-600 font-medium dark:text-gray-300">{{auth()->user()->name}}</a>
        </div>
        <div class="m-2 mt-4 flex">
            <input type="search"
                   class="border dark:border-gray-500 dark:bg-gray-600 dark:placeholder-gray-300 text-gray-200 text-sm border-gray-300 bg-gray-100 px-2 w-48 h-9 rounded-md rounded-r-none"
                   placeholder="Search">
            <a href=""
               class="border  dark:bg-gray-600 border-gray-300 dark:border-gray-500 bg-gray-100 rounded-l-none p-2 h-9 rounded-md">
                <svg xmlns="http'https://ui-avatars.com/api/?name={{auth()->user()->name}}'://www.w3.org/2000/svg"
                     class="w-5 text-gray-600 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </a>
        </div>
        <div class="capitalize">


            <a href="{{route('app.dashboard')}}" wire:navigate
               class="navMenuLink items-center {{Route::is('app.dashboard')?'navActive':'navInactive'}}">
                <i class='bx bx-home text-xl {{Route::is('app.dashboard')?'bx-tada':''}}'></i><span
                    class="">@lang('dashboard')</span>
            </a>
{{--            <a href="{{route('app.roles')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.roles')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bx-shield text-xl {{Route::is('app.roles')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('roles')</span>--}}
{{--            </a>--}}
{{--            <a href="{{route('app.users')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.users')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bx-user text-xl {{Route::is('app.users')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('users')</span>--}}
{{--            </a>--}}
{{--            <a href="{{route('app.backups')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.backups')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bx-cloud-download text-xl {{Route::is('app.backups')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('backups')</span>--}}
{{--            </a>--}}
            <a href="{{route('app.profile')}}" wire:navigate
               class="navMenuLink items-center {{Route::is('app.profile')?'navActive':'navInactive'}}">
                <i class='bx bxl-product-hunt text-xl {{Route::is('app.profile')?'bx-tada':''}}'></i><span
                    class="">@lang('profile')</span>
            </a>
{{--            <a href="{{route('app.setting')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.setting')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bx-cog text-xl {{Route::is('app.setting')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('setting')</span>--}}
{{--            </a>--}}
{{--            <a href="{{route('app.chat')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.chat')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bx-chat text-xl {{Route::is('app.chat')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('chat')</span>--}}
{{--            </a>--}}
{{--            <a href="{{route('app.pages')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.pages')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bxs-copy text-xl {{Route::is('app.pages')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('pages')</span>--}}
{{--            </a>--}}

{{--            <a href="{{route('app.notifications')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.notifications')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bx-bell text-xl {{Route::is('app.notifications')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('notifications')</span>--}}
{{--            </a>--}}
{{--            <a href="{{route('app.translate')}}" wire:navigate--}}
{{--               class="navMenuLink items-center {{Route::is('app.translate')?'navActive':'navInactive'}}">--}}
{{--                <i class='bx bx-globe text-xl {{Route::is('app.translate')?'bx-tada':''}}'></i><span--}}
{{--                    class="">@lang('translate')</span>--}}
{{--            </a>--}}


            <div x-data="{ app: @json(Route::is('app.roles') || Route::is('app.users') || Route::is('app.backups') || Route::is('app.setting') || Route::is('app.pages')) }">
                <div @click="app= !app" class="navMenuLink dark:text-gray-300 cursor-pointer  {{Route::is('app.roles') || Route::is('app.users') || Route::is('app.backups') || Route::is('app.setting') || Route::is('app.pages')?'navActive':'navInactive'}}">
                    <i  class="bx bx-cog text-xl"></i>
                    <span class="">@lang("app manager")</span>
                    <i x-show="!app" class="bx bx-chevron-down text-xl ml-auto"></i>
                    <i x-show="app" class="bx bx-chevron-up text-xl ml-auto"></i>
                </div>
                <div x-show="app" class="text-sm grid grid-cols-1 gap-0.5" x-collapse.duration.200ms>
                    <a href="{{route('app.backups')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.backups')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bxs-cloud-download text-xl {{Route::is('app.backups')?'bx-tada':''}}'></i><span
                            class="">@lang('backups')</span>
                    </a>
                    <a href="{{route('app.setting')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.setting')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bxs-cog text-xl {{Route::is('app.setting')?'bx-tada':''}}'></i><span
                            class="">@lang('setting')</span>
                    </a>
                    <a href="{{route('app.roles')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.roles')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bx-shield text-xl {{Route::is('app.roles')?'bx-tada':''}}'></i><span
                            class="">@lang('roles')</span>
                    </a>
                    <a href="{{route('app.users')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.users')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bxs-user text-xl {{Route::is('app.users')?'bx-tada':''}}'></i><span
                            class="">@lang('users')</span>
                    </a>
                    <a href="{{route('app.pages')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.pages')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bxs-copy text-xl {{Route::is('app.pages')?'bx-tada':''}}'></i><span
                            class="">@lang('pages')</span>
                    </a>
                    <a href="{{route('app.translate')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.translate')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bx-globe text-xl {{Route::is('app.translate')?'bx-tada':''}}'></i><span
                            class="">@lang('translate')</span>
                    </a>
                </div>
            </div>
            <div x-data="{ post: @json(Route::is('app.posts') || Route::is('app.categories')) }">
                <div @click="post= !post" class="navMenuLink dark:text-gray-300 cursor-pointer  {{Route::is('app.posts')|Route::is('app.categories')?'navActive':'navInactive'}}">
                    <i  class="bx bx-file text-xl"></i>
                    <span class="">@lang("post manager")</span>
                    <i x-show="!post" class="bx bx-chevron-down text-xl ml-auto"></i>
                    <i x-show="post" class="bx bx-chevron-up text-xl ml-auto"></i>
                </div>
                <div x-show="post" class="text-sm grid grid-cols-1 gap-0.5" x-collapse.duration.200ms>
                    <a href="{{route('app.categories')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.categories')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bxs-category text-xl {{Route::is('app.categories')?'bx-tada':''}}'></i><span
                            class="">@lang('categories')</span>
                    </a>
                    <a href="{{route('app.posts')}}" wire:navigate
                       class="subNavMenuLink items-center {{Route::is('app.posts')?'subNavActive':'subNavInactive'}}">
                        <i class='bx bxs-pencil text-xl {{Route::is('app.posts')?'bx-tada':''}}'></i><span
                            class="">@lang('posts')</span>
                    </a>
                </div>
            </div>

        </div>
        <div class="p-4 space-y-2 md:p-8">
            <h6 class="text-lg font-medium text-primary dark:text-primary">Colors</h6>
            <div class="grid grid-cols-3 gap-2">
                <button
                    @click="setColors('cyan')"
                    class="w-10 h-10 rounded-full"
                    style="background-color: var(--color-cyan)"
                ></button>
                <button
                    @click="setColors('teal')"
                    class="w-10 h-10 rounded-full"
                    style="background-color: var(--color-teal)"
                ></button>
                <button
                    @click="setColors('green')"
                    class="w-10 h-10 rounded-full"
                    style="background-color: var(--color-green)"
                ></button>
                <button
                    @click="setColors('fuchsia')"
                    class="w-10 h-10 rounded-full"
                    style="background-color: var(--color-fuchsia)"
                ></button>
                <button
                    @click="setColors('blue')"
                    class="w-10 h-10 rounded-full"
                    style="background-color: var(--color-blue)"
                ></button>
                <button
                    @click="setColors('violet')"
                    class="w-10 h-10 rounded-full"
                    style="background-color: var(--color-violet)"
                ></button>
            </div>
        </div>
    </div>

</nav>
