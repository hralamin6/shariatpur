<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <body>
    <div class="min-h-screen bg-gray-100 capitalize">
        <div class="h-16 bg-white shadow-sm px-8 fixed w-full top-0 flex items-center">
            <div class="relative">
                <i class='bx bx-search absolute left-2 top-2 text-2xl text-gray-400' ></i>
                <input type="text" class="block w-72 shadow border-none rounded-3xl focus:outline-none py-2 bg-gray-100 text-base text-gray-600">
            </div>
            <div class="ml-auto flex items-center">
                <div class="">
                    <i class='bx bx-bell text-2xl cursor-pointer text-gray-600 hover:text-gray-900' ></i>
                </div>
                <div class="ml-4 relative">
                    <div class="cursor-pointer">
                        <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name=John+Doe" alt="user">
                    </div>
                    <div class=" absolute z-50 mt-2 rounded-md shadow-lg w-48 right-0 py-1 bg-white space-y-1">
                        <div class="px-4 py-2 text-xs text-gray-400">@lang('manage account')</div>
                        <a href="" class="block px-4 py-2 text-sm leading-4 text-gray-700 hover:bg-gray-100 transition">@lang('profile')</a>
                        <a href="" class="block px-4 py-2 text-sm leading-4 text-gray-700 hover:bg-gray-100 transition">@lang('logout')</a>
                    </div>
                </div>
            </div>

        </div>

    </div>
    {{ $slot }}
    </body>
</html>
