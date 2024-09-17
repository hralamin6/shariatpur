@extends('components.layouts.base')

@section('body')
    <div class="dark:bg-darkBg flex bg-lightBg  "
         :class="{ 'overflow-hidden': nav }"
    >
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
    </div>


@endsection
