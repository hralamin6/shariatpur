<div class="m-2 capitalize" x-data="dashboard({{ $items && $items->isNotEmpty() ? $items->last()->id : 'null' }})">

    <div class="container mx-auto p-6">


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
                        <div class="mb-1 h-96 overflow-y-scroll scrollbar-none" id="chatbox_body" x-data="{ open: false, imageUrl: '' }">
                            @php
                                $currentDate = null;
                                $currentHour = null;
                            @endphp
                            @foreach($items as $item)
                                @php
                                    $messageDate = $item->created_at->format('Y-m-d');
                                    $messageHour = $item->created_at->format('H');
                                @endphp

                                    <!-- Date Separator -->
                                @if ($currentDate !== $messageDate)
                                    <div class="text-center text-xs text-gray-500 dark:text-gray-400 my-2">
                                        {{ $item->created_at->format('F j, Y') }}
                                    </div>
                                    @php
                                        $currentDate = $messageDate;
                                        $currentHour = null;  // Reset hour when date changes
                                    @endphp
                                @endif

                                <!-- Time Separator -->
                                @if ($currentHour !== $messageHour)
                                    <div class="text-center text-xs text-gray-500 dark:text-gray-400 my-1">
                                        {{ $item->created_at->format('h:i A') }}
                                    </div>
                                    @php
                                        $currentHour = $messageHour;
                                    @endphp
                                @endif

                                <div class="py-2 px-4" id="{{$item->id}}" wire:key="{{$item->id}}">
                                    <!-- Chat bubble wrapper -->
                                    <div class="flex {{$item->user->id == auth()->id() ? 'justify-end' : 'justify-start'}} space-x-2 space-x-reverse">

                                        <!-- User avatar for received messages -->
                                        @if($item->user->id != auth()->id())
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden border border-gray-300 dark:border-gray-600">
                                                <img src="{{ getUserProfileImage($item->user) }}" alt="User" onerror="{{ getErrorProfile($item->user) }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif

                                        <!-- Message bubble -->
                                        <div class="flex flex-col space-y-1 max-w-xs">
                                            <div class="flex {{$item->user->id == auth()->id() ? 'justify-end' : ''}}">
                                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{$item->user->name}}</p>
                                            </div>
                                            <div class="flex gap-1 {{$item->user->id == auth()->id() ? 'justify-end' : ''}}">
                                                @if(auth()->id() == $item->user_id)
                                                    <button
                                                        wire:click="messageDelete({{ $item->id }})"
                                                        class="text-red-500 hover:text-red-700 transition-colors duration-300 text-xl bg-red-100 hover:bg-red-200 p-2 rounded-full">
                                                        &times;
                                                    </button>
                                                @endif
                                                <div class="relative px-4 py-2 rounded-lg shadow-md text-sm max-w-fit {{$item->user->id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200'}}">

                                                    {{$item->body}}

                                                    <!-- Tail-like part of the chat bubble -->
                                                    @if($item->user->id == auth()->id())
                                                        <span class="absolute bottom-0 right-0 -mb-1.5 mr-1 w-3 h-3 bg-blue-500 transform rotate-45"></span>
                                                    @else
                                                        <span class="absolute bottom-0 left-0 -mb-1.5 ml-1 w-3 h-3 bg-gray-200 dark:bg-gray-700 transform rotate-45"></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex {{$item->user->id == auth()->id() ? 'justify-end' : ''}}">
                                                <p class="text-xs text-gray-400 dark:text-gray-500">{{$item->created_at->format('h:i A')}}</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div
                                        class="flex {{ auth()->id() == $item->user_id ? 'justify-end' : 'justify-start' }} mb-2">
                                        @php
                                            $mediaCount = $item->getMedia('liveChatImages')->count();
                                        @endphp

                                        @if($mediaCount == 1)
                                            <!-- Single Image - Full width display -->
                                            @foreach($item->getMedia('liveChatImages') as $media)
                                                <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Image"
                                                     onerror="{{getErrorImage()}}"
                                                     class="w-full max-w-xs md:w-64 md:h-64 border-4 border-white dark:border-darker shadow-lg mb-4 rounded-lg cursor-pointer"
                                                     @click="open = true; imageUrl = '{{$media->getAvailableUrl(['thumb'])}}'">
                                            @endforeach

                                        @elseif($mediaCount == 2)
                                            <!-- Two Images - Side by side -->
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($item->getMedia('liveChatImages') as $media)
                                                    <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Image"
                                                         onerror="{{getErrorImage()}}"
                                                         class="w-full h-48 md:w-32 md:h-32 border-4 border-white dark:border-darker shadow-lg mb-4 rounded-lg cursor-pointer"
                                                         @click="open = true; imageUrl = '{{$media->getAvailableUrl(['thumb'])}}'">
                                                @endforeach
                                            </div>

                                        @else
                                            <!-- More than two images - Grid layout (2x2 grid or more) -->
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($item->getMedia('liveChatImages') as $media)
                                                    <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Image"
                                                         onerror="{{getErrorImage()}}"
                                                         class="w-full h-32 md:w-32 md:h-32 border-4 border-white dark:border-darker shadow-lg mb-4 rounded-lg cursor-pointer"
                                                         @click="open = true; imageUrl = '{{$media->getAvailableUrl(['thumb'])}}'">
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Full Image Preview Modal -->
                                        <div x-show="open == true"
                                             class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                                             x-transition>
                                            <div class="relative">
                                                <!-- Full-size image -->
                                                <img :src="imageUrl" alt="Full Image"
                                                     class="max-w-full max-h-screen rounded-lg shadow-lg">

                                                <!-- Close button -->
                                                <button @click="open = false"
                                                        class="absolute top-2 right-2 text-red-500 text-4xl">&times;
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                        <div class="border dark:border-gray-600 p-2">
                            <div x-cloak x-show="typing" class="flex items-center">
                                <span x-text="whisperTyper" class="text-gray-500"> </span>
                                <span class="text-gray-500 lowercase px-2"> is typing </span>
                                <div
                                    class="w-2 h-3 bg-gray-400 dark:bg-gray-400 rounded-full mr-2 animate-pulse"></div>
                                <div
                                    class="w-2 h-3 bg-gray-400 dark:bg-gray-400 rounded-full mr-2 animate-pulse"></div>
                                <div class="w-2 h-3 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>

                            </div>
                            <div class="flex items-center justify-center">
                                <!-- Image URL Input -->
                                <div>
                                    <x-text-input placeholder="Image link" errorName="image_url" id="image_url"
                                                  wire:model="image_url" type="url" class="py-2.5"/>
                                </div>

                                <!-- File Upload Section with Progress Bar -->
                                <div class="flex flex-col" x-data="{ isUploading: false, progress: 5 }"
                                     x-on:livewire-upload-start="isUploading = true"
                                     x-on:livewire-upload-finish="isUploading = false"
                                     x-on:livewire-upload-error="isUploading = false"
                                     x-on:livewire-upload-progress="progress = $event.detail.progress">

                                    <!-- Progress Bar -->
                                    <div x-show="isUploading" class="w-full mt-2">
                                        <div class="relative pt-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="text-xs font-semibold text-blue-600 dark:text-blue-400"
                                                     x-text="progress + '%'"></div>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full">
                                                <div
                                                    class="bg-blue-600 dark:bg-blue-400 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                                    x-bind:style="'width: ' + progress + '%'"
                                                    x-text="progress + '%'"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Input -->
                                    <x-text-input multiple errorName="photo" type="file" wire:model="photo"
                                                  accept="image/*"/>
                                </div>
                            </div>

                            <form wire:submit.prevent="sendMessage"  x-on:submit="whisperTypingEnd()"
                                  class="flex justify-between p-1 bg-gray-100 dark:bg-darker rounded-lg shadow-lg">

                                <!-- Input Section -->

                                <!-- Message Input -->
                                <x-text-input errorName="body" autocomplete="off"
                                              x-on:click.outside="whisperTypingEnd()"
                                              x-on:input.debounce.2000ms="whisperTypingStart()"
                                              wire:model="body"
                                              placeholder="Type a message..."
                                              class="w-full bg-transparent border-none focus:outline-none dark:text-white text-gray-700"
                                              type="text" name="message" id="message"/>
                                <button type="submit" wire:loading.remove.delay  wire:target="sendMessage, photo"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-full ml-2 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-blue-300 hover:bg-blue-600 transition duration-200">
                                    <span>@lang('send')</span>
                                </button>
                            </form>

                        </div>
                    </div>
                </aside>
            </div>

        </div>
        <div class=" rounded-xl mt-4" x-data="{openTable: $persist(true)}">
            <aside class="border dark:border-gray-600 row-span-4 bg-white dark:bg-darkSidebar">
                <div class="flex justify-between gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2">
                    <p class="text-gray-600 dark:text-gray-200">Currently Logged-in Users</p>
                    <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
            <span class="px-1 mt-1 mb-0.5 text-white pb-0.5 font-semibold text-xs bg-pink-400 rounded-lg">
                {{ $loggedInUsers->count() }} Active Users
            </span>
                    </div>
                </div>

                <div class="mb-1 overflow-y-scroll scrollbar-none">
                    <div class="w-full overflow-x-auto">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-darkSidebar">
                                <th class="px-4 py-3">User</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">IP Address</th>
                                <th class="px-4 py-3">Last Activity</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-darkSidebar">
                            @foreach($loggedInUsers as $loggedInUser)
                                <tr class="text-gray-700 dark:text-gray-400">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center text-sm">
                                            <!-- Avatar with inset shadow -->
                                            <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block w-10 h-10 rounded-full bg-purple-600 border dark:border-gray-600 shadow-xl overflow-hidden flex items-center justify-center">
                                                <img src="{{ getUserProfileImage($loggedInUser->user) }}" alt=""
                                                     onerror="{{ getErrorProfile($loggedInUser->user) }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                            </div>
                                            <div>
                                                <p class="font-semibold">{{ $loggedInUser->user->name }}</p>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $loggedInUser->user->role->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $loggedInUser->user->email }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $loggedInUser->ip_address }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ \Carbon\Carbon::createFromTimestamp($loggedInUser->last_activity)->diffForHumans() }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">

                                            <button type="button" wire:click="logoutUser('{{$loggedInUser->session_id}}')" class="rounded-md px-2 py-1 bg-red-600 text-sm text-white">Logout</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-center flex justify-between bg-white border dark:border-gray-600 dark:bg-darkSidebar py-2 bg-gray-50 px-4">
                    <a href="" class="rounded-md px-2 py-1 bg-indigo-600 text-sm text-white">View All Users</a>
                </div>
            </aside>

        </div>

    </div>
        @includeIf('livewire.app.statistics')

</div>
    @script
    <script>
        Alpine.data('dashboard', (last) => ({
            init() {
                this.tap();
                this.scrollToLast(this.lastId);
                Echo.private(`chat`)
                    .listenForWhisper('typing', (e) => {
                        if (e.typing) {
                            this.whisperTyper = e.typer
                            this.typing = true
                        } else {
                            this.typing = false
                        }
                        setTimeout(() => {
                            this.typing = false;
                        }, 5000)
                    });
                $wire.on('scrollBottom', (e) => {
                    $nextTick(() => {
                        element = document.getElementById(e.message_id)
                        element.scrollIntoView({behavior: 'smooth'})
                        element.classList.add('animate-pulse');
                    });
                    setTimeout(() => {
                        element.classList.remove('animate-pulse');
                    }, 3000)

                });
                $wire.on('updatedHeight', (e) => {

                    $nextTick(() => {
                        // alert(e)
                        element = document.getElementById(e)
                        element.scrollIntoView({behavior: 'smooth', block: 'nearest'});

                        // element.scrollIntoView({behavior: 'smooth'})
                        element.classList.add('animate-pulse');
                        // element.scrollTop = element.scrollTop + 620;
                    });
                });

            },
            lastId: last,
            sending: 0,
            height: '',
            whisperTyper: '',
            typing: false,
            activated: false,
            whisperTypingStart() {
                this.sending = Echo.private(`chat`).whisper('typing', {
                    typer: '{{auth()->user()->name}}',
                    typing: true
                });
            },
            whisperTypingEnd() {
                this.sending = Echo.private(`chat`).whisper('typing', {
                    typing: false
                })
            },


            tap() {
                const element = document.getElementById('chatbox_body');
                element.addEventListener('scroll', () => {
                    const top = element.scrollTop;
                    if (top == 0) {
                        this.height = element.scrollHeight
                        this.$wire.loadMore();
                    }
                });
            },
            scrollToLast(e) {
                $nextTick(() => {
                    element = document.getElementById(e)
                    element.classList.add('animate-pulse');
                    element.scrollIntoView({behavior: 'instant'})
                });
                setTimeout(() => {
                    element.classList.remove('animate-pulse');
                }, 3000)
            },

        }))
    </script>
    @endscript
</div>

