@extends('components.layouts.base')

@section('body')
    <nav x-data="{ isOpen: false }" class="relative bg-white shadow dark:bg-gray-800">

    @include('components.layouts.navbar')

        <div class="flex flex-col flex-1 w-full">
            <main class="overflow-y-auto overflow-x-hidden h-screen dark:bg-darkBg dark:scrollbar-thin-dark scrollbar-thin-light">
                <div class="m-2">
                    @yield('content')

                    @isset($slot)
                        {{ $slot }}
                    @endisset
                </div>
            </main>
        </div>
    </nav>


@endsection
