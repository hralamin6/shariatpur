<div class="m-2">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 capitalize">
        <a href="{{route('app.users')}}?search=customer&searchBy=type" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-purple-500 px-4 py-3 text-white">
                <x-h-o-users class="h-8 w-8"/>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">@lang('total customers')</p>
                <p class="">{{$users->where('type', 'customer')->count()}}</p>
            </div>
        </a>
        <a href="{{route('app.users')}}?search=supplier&searchBy=type" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-pink-500 px-4 py-3 text-white">
                <x-h-o-user-group class="h-8 w-8"/>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">@lang('total suppliers')</p>
                <p class="">{{$users->where('type', 'supplier')->count()}}</p>
            </div>
        </a>
        <a href="{{route('app.users')}}" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-pink-500 px-4 py-3 text-white">
                <x-h-o-bars-4 class="h-8 w-8"/>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">@lang('total medicines')</p>
                <p class="">12</p>
            </div>
        </a>
        <a href="{{route('app.users')}}?search=0&searchBy=quantity" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-pink-500 px-4 py-3 text-white">
                <x-h-o-bars-4 class="h-8 w-8"/>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">@lang('total medicines')</p>
                <p class="">00</p>
            </div>
        </a>
        <div class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-green-500 px-4 py-3 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">Sales</p>
                <p class="">25</p>
            </div>
        </div>
        <div class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-yellow-500 px-4 py-3 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">Users</p>
                <p class="">255</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="">
            <div class=" rounded-xl mt-4" x-data="{openChat: $persist(true)}">
                <aside class="border dark:border-gray-600 row-span-4 bg-white dark:bg-darkSidebar">
                    <div class="flex justify-between gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2">
                        <p class="text-gray-600 dark:text-gray-200">Direct Chat</p>
                        <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
                            <span class="px-0.5 mt-1 mb-0.5 text-white pb-0.5 font-semibold text-xs bg-pink-400 rounded-lg">10</span>
                            <button class="">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </button>
                            <button class="" @click="openChat = !openChat">
                                <svg x-show="openChat" xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="!openChat" xmlns="http://www.w3.org/2000/svg" class="h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>

                            <button class="">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                        </div>
                    </div>
                    <div x-show="openChat" x-collapse>
                        <div class="mb-1 h-96 overflow-y-scroll scrollbar-none">
                            <div class="py-1  px-4">
                                <div class="flex justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, nobis!
                                    </div>
                                </div>
                            </div>
                            <div class="py-1  px-4">
                                <div class="flex flex-row-reverse justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex flex-row-reverse justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit
                                    </div>
                                </div>
                            </div>
                            <div class="py-1  px-4">
                                <div class="flex justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, nobis!
                                    </div>
                                </div>
                            </div>
                            <div class="py-1  px-4">
                                <div class="flex flex-row-reverse justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex flex-row-reverse justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit
                                    </div>
                                </div>
                            </div>
                            <div class="py-1  px-4">
                                <div class="flex justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, nobis!
                                    </div>
                                </div>
                            </div>
                            <div class="py-1  px-4">
                                <div class="flex flex-row-reverse justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex flex-row-reverse justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit
                                    </div>
                                </div>
                            </div>
                            <div class="py-1  px-4">
                                <div class="flex justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, nobis!
                                    </div>
                                </div>
                            </div>
                            <div class="py-1  px-4">
                                <div class="flex flex-row-reverse justify-between gap-3 space-y-1.5">
                                    <p class="font-semibold text-xs dark:text-gray-200">Alexander Pierce</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">23 Jan 2:00 pm</p>
                                </div>
                                <div class="flex flex-row-reverse justify-start gap-3">
                                    <div class="rounded-full h-9 w-9 bg-indigo-700"></div>
                                    <div class="dark:bg-gray-600 bg-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        Lorem ipsum dolor sit
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" bg-white border dark:border-gray-600 dark:bg-darkSidebar py-2 bg-gray-50 px-4">
                            <div class="flex justify-center mx-4">
                                <input class="border border-gray-300 rounded-l-md h-9 dark:bg-gray-600 dark:border-gray-500" type="text" name="message" id="message">
                                <button class="border border-gray-300 capitalize px-3 py-1 bg-blue-500 rounded-r-md text-white dark:border-gray-500">Send</button>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

        </div>
        <div class=" rounded-xl mt-4" x-data="{openTable: $persist(true)}">
            <aside class="border dark:border-gray-600 row-span-4 bg-white dark:bg-darkSidebar">
                <div class="flex justify-between gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2">
                    <p class="text-gray-600 dark:text-gray-200">Products Table</p>
                    <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
                        <span class="px-1 mt-1 mb-0.5 text-white pb-0.5 font-semibold text-xs bg-pink-400 rounded-lg">10 New Members</span>
                        <button class="" @click="openTable = !openTable">
                            <svg x-show="openTable" xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <svg x-show="!openTable" xmlns="http://www.w3.org/2000/svg" class="h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <button class="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div x-show="openTable" x-collapse>
                    <div class="mb-1 overflow-y-scroll scrollbar-none">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                <tr
                                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-darkSidebar"
                                >
                                    <th class="px-4 py-3">Client</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Date</th>
                                </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y dark:divide-gray-700 dark:bg-darkSidebar"
                                >
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            <!-- Avatar with inset shadow -->
                                            <div
                                                class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                            >
                                                <img
                                                    class="object-cover w-full h-full rounded-full"
                                                    src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                    alt=""
                                                    loading="lazy"
                                                />
                                                <div
                                                    class="absolute inset-0 rounded-full shadow-inner"
                                                    aria-hidden="true"
                                                ></div>
                                            </div>
                                            <div>
                                                <p class="font-semibold">Hans Burger</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    10x Developer
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        $ 863.45
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                        <span
                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                        >
                          Approved
                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        6/10/2020
                                    </td>
                                </tr>

                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            <!-- Avatar with inset shadow -->
                                            <div
                                                class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                            >
                                                <img
                                                    class="object-cover w-full h-full rounded-full"
                                                    src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&facepad=3&fit=facearea&s=707b9c33066bf8808c934c8ab394dff6"
                                                    alt=""
                                                    loading="lazy"
                                                />
                                                <div
                                                    class="absolute inset-0 rounded-full shadow-inner"
                                                    aria-hidden="true"
                                                ></div>
                                            </div>
                                            <div>
                                                <p class="font-semibold">Jolina Angelie</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    Unemployed
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        $ 369.95
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                        <span
                            class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600"
                        >
                          Pending
                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        6/10/2020
                                    </td>
                                </tr>

                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            <!-- Avatar with inset shadow -->
                                            <div
                                                class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                            >
                                                <img
                                                    class="object-cover w-full h-full rounded-full"
                                                    src="https://images.unsplash.com/photo-1551069613-1904dbdcda11?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                    alt=""
                                                    loading="lazy"
                                                />
                                                <div
                                                    class="absolute inset-0 rounded-full shadow-inner"
                                                    aria-hidden="true"
                                                ></div>
                                            </div>
                                            <div>
                                                <p class="font-semibold">Sarah Curry</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    Designer
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        $ 86.00
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                        <span
                            class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700"
                        >
                          Denied
                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        6/10/2020
                                    </td>
                                </tr>

                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            <!-- Avatar with inset shadow -->
                                            <div
                                                class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                            >
                                                <img
                                                    class="object-cover w-full h-full rounded-full"
                                                    src="https://images.unsplash.com/photo-1551006917-3b4c078c47c9?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                    alt=""
                                                    loading="lazy"
                                                />
                                                <div
                                                    class="absolute inset-0 rounded-full shadow-inner"
                                                    aria-hidden="true"
                                                ></div>
                                            </div>
                                            <div>
                                                <p class="font-semibold">Rulia Joberts</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    Actress
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        $ 1276.45
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                        <span
                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100"
                        >
                          Approved
                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        6/10/2020
                                    </td>
                                </tr>

                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            <!-- Avatar with inset shadow -->
                                            <div
                                                class="relative hidden w-8 h-8 mr-3 rounded-full md:block"
                                            >
                                                <img
                                                    class="object-cover w-full h-full rounded-full"
                                                    src="https://images.unsplash.com/photo-1546456073-6712f79251bb?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                    alt=""
                                                    loading="lazy"
                                                />
                                                <div
                                                    class="absolute inset-0 rounded-full shadow-inner"
                                                    aria-hidden="true"
                                                ></div>
                                            </div>
                                            <div>
                                                <p class="font-semibold">Wenzel Dashington</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    Actor
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        $ 863.45
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                        <span
                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700"
                        >
                          Expired
                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        6/10/2020
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="text-center flex justify-between bg-white border dark:border-gray-600 dark:bg-darkSidebar py-2 bg-gray-50 px-4">
                    <a href="" class="rounded-md px-2 py-1 bg-purple-600 text-sm text-white">Place New Order</a>
                    <a href="" class="rounded-md px-2 py-1 bg-indigo-600 text-sm text-white">View All Users</a>
                </div>
            </aside>
        </div>

    </div>
</div>

