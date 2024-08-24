<div class="m-2">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 capitalize">
        <a href="{{route('users')}}?search=customer&searchBy=type" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-purple-500 px-4 py-3 text-white">
                <x-h-o-users class="h-8 w-8"/>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">@lang('total customers')</p>
                <p class="">{{$users->where('type', 'customer')->count()}}</p>
            </div>
        </a>
        <a href="{{route('users')}}?search=supplier&searchBy=type" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-pink-500 px-4 py-3 text-white">
                <x-h-o-user-group class="h-8 w-8"/>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">@lang('total suppliers')</p>
                <p class="">{{$users->where('type', 'supplier')->count()}}</p>
            </div>
        </a>
        <a href="{{route('products')}}" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
            <div class="rounded-md bg-pink-500 px-4 py-3 text-white">
                <x-h-o-bars-4 class="h-8 w-8"/>
            </div>
            <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                <p class="">@lang('total medicines')</p>
                <p class="">12</p>
            </div>
        </a>
        <a href="{{route('products')}}?search=0&searchBy=quantity" class=" flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-md">
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
            <div>
                <div class="mt-4  flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-lg">
                    <div class="rounded-md bg-purple-500 px-4 py-3 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                        <p class="">CPU Traffic</p>
                        <p class="">10%</p>
                    </div>
                </div>
                <div class="mt-4  flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-lg">
                    <div class="rounded-md bg-purple-500 px-4 py-3 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                        <p class="">CPU Traffic</p>
                        <p class="">10%</p>
                    </div>
                </div>
                <div class="mt-4  flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-lg">
                    <div class="rounded-md bg-purple-500 px-4 py-3 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                        <p class="">CPU Traffic</p>
                        <p class="">10%</p>
                    </div>
                </div>
                <div class="mt-4  flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-lg">
                    <div class="rounded-md bg-purple-500 px-4 py-3 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                        <p class="">CPU Traffic</p>
                        <p class="">10%</p>
                    </div>
                </div>
                <div class="mt-4  flex gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2 rounded-lg">
                    <div class="rounded-md bg-purple-500 px-4 py-3 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col gap-2 text-sm text-gray-500 font-semibold dark:text-gray-200">
                        <p class="">CPU Traffic</p>
                        <p class="">10%</p>
                    </div>
                </div>
            </div>
            <div class=" rounded-xl mt-4" x-data="{openMembers: $persist(true)}">
                <aside class="border dark:border-gray-600 row-span-4 bg-white dark:bg-darkSidebar">
                    <div class="flex justify-between gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2">
                        <p class="text-gray-600 dark:text-gray-200">New Members</p>
                        <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
                            <span class="px-1 mt-1 mb-0.5 text-white pb-0.5 font-semibold text-xs bg-pink-400 rounded-lg">10 New Members</span>
                            <button class="" @click="openMembers = !openMembers">
                                <svg x-show="openMembers" xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="!openMembers" xmlns="http://www.w3.org/2000/svg" class="h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    <div x-show="openMembers" x-collapse>
                        <div class="mb-1 overflow-y-scroll scrollbar-none grid grid-cols-4">
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>
                            <div class="mx-auto my-2 flex flex-col justify-center content-center text-center">
                                <div class="rounded-full w-20 h-20 bg-gray-600"></div>
                                <div class="">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">user name</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">today</p>
                                </div>
                            </div>

                        </div>
                        <div class="text-center bg-white border dark:border-gray-600 dark:bg-darkSidebar py-2 bg-gray-50 px-4">
                            <a href="" class="text-blue-500">View All Users</a>
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
                        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                            <div class="w-full">
                                <h1
                                    class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
                                >
                                    Create account
                                </h1>
                                <label class="block text-sm">
                                    <span class="text-gray-700 dark:text-gray-400">Email</span>
                                    <input
                                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                        placeholder="Jane Doe"
                                    />
                                </label>
                                <label class="block mt-4 text-sm">
                                    <span class="text-gray-700 dark:text-gray-400">Password</span>
                                    <input
                                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                        placeholder="***************"
                                        type="password"
                                    />
                                </label>
                                <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                  Confirm password
                </span>
                                    <input
                                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                        placeholder="***************"
                                        type="password"
                                    />
                                </label>

                                <div class="flex mt-6 text-sm">
                                    <label class="flex items-center dark:text-gray-400">
                                        <input
                                            type="checkbox"
                                            class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                        />
                                        <span class="ml-2">
                    I agree to the
                    <span class="underline">privacy policy</span>
                  </span>
                                    </label>
                                </div>

                                <!-- You should use a button here, as the anchor is only used for the example  -->
                                <a
                                    class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                                    href="./login.html"
                                >
                                    Create account
                                </a>

                                <hr class="my-8" />

                                <button
                                    class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
                                >
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        aria-hidden="true"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <path
                                            d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"
                                        />
                                    </svg>
                                    Github
                                </button>
                                <button
                                    class="flex items-center justify-center w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray"
                                >
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        aria-hidden="true"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <path
                                            d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"
                                        />
                                    </svg>
                                    Twitter
                                </button>

                                <p class="mt-4">
                                    <a
                                        class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline"
                                        href="./login.html"
                                    >
                                        Already have an account? Login
                                    </a>
                                </p>
                            </div>
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

