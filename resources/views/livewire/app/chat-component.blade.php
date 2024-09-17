<div class="m-2" x-data="chat({{ $currentMessages && $currentMessages->isNotEmpty() ? $currentMessages->last()->id : 'null' }})">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <div
            class="col-span-3 lg:col-span-1 rounded-xl border border-gray-300 dark:border-gray-500 bg-white dark:bg-darkSidebar {{$selectedConversation?'hidden lg:block':''}}">
            <div
                class="h-16 border-b border-gray-300 dark:border-gray-500 flex justify-between gap-2 items-center capitalize px-2">
                <p class="text-2xl text-gray-700 dark:text-gray-300 font-medium">@lang('chat list')</p>
            </div>
            <div class="flex flex-col row-span-4">
                <div class="flex-1 overflow-y-auto px-4 py-6">
                    <ul class="space-y-2">
                        @forelse($conversations as $conversation)
                            @php
                                $unreadMessagesCount = $conversation->messages->where('read', 0)->where('receiver_id', auth()->user()->id)->count();
                                $isActive = isset($selectedConversation) && $selectedConversation->id == $conversation->id;
                            @endphp
                            <li class="relative flex items-center p-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 hover:dark:bg-gray-600 {{ $isActive ? 'bg-gray-300 dark:bg-gray-800' : '' }}">
                                <button
                                    wire:click.prevent="loadConversation({{ $conversation }}, '{{ $this->getChatUserInstance($conversation, $name = 'id') }}')"
                                    type="button" class="flex items-center w-full text-left">
                                    <img class="rounded-full h-12 w-12 object-cover"
                                         src="https://ui-avatars.com/api/?name={{ $this->getChatUserInstance($conversation, $name = 'name') }}"
                                         alt="">
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-center mb-1">
                                            <h2 class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                                {{ $this->getChatUserInstance($conversation, $name = 'name') }}
                                            </h2>
                                            @if($unreadMessagesCount)
                                                <span
                                                    class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full">
                                        {{ $unreadMessagesCount }}
                                    </span>
                                            @endif
                                        </div>
                                        <div
                                            class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                            <p class="truncate flex-1">
                                                {{ $conversation->messages->last()?->body }}
                                            </p>
                                            <p class="ml-2 whitespace-nowrap">
                                                {{ $conversation->messages->last()?->created_at->shortAbsoluteDiffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                                <div x-cloak
                                     x-show="typing && whisperReceiver === {{ $this->getChatUserInstance($conversation, $name = 'id') }}"
                                     class="absolute right-4 flex items-center space-x-1">
                                    <div class="w-2 h-2 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>
                                    <div class="w-2 h-2 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>
                                    <div class="w-2 h-2 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>
                                </div>
                            </li>
                        @empty
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">No conversation found</p>
                            <a href="{{route('app.users')}}" wire:navigate
                               class="text-center text-lg text-green-600 dark:text-gray-400">@lang('select user to start conversation')</a>
                        @endforelse
                    </ul>
                </div>
            </div>


        </div>
        @if ($selectedConversation)
            <div class="col-span-3 lg:col-span-2" x-data="{openChat: $persist(true)}">
                <aside class="rounded-xl border border-gray-300 dark:border-gray-500 bg-white dark:bg-darkSidebar">
                    <div
                        class="h-16 border-b dark:border-gray-500 flex justify-between gap-2 items-center capitalize px-6">
                        <button wire:click.prevent="resetComponent" class="text-gray-500 dark:text-gray-300">
                            <i class='bx bx-arrow-back text-2xl font-medium'></i>

                        </button>
                        <div class="flex gap-2 items-center justify-start">
                            <img class="rounded-full p-2 h-16"
                                 src="https://ui-avatars.com/api/?name={{$this->getChatUserInstance($selectedConversation, $name = 'name')}}"
                                 alt="">
                            <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300 truncate"> {{ $this->getChatUserInstance($selectedConversation, $name = 'name') }}</h2>
                        </div>
                        <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
                            <button class="" @click="openChat = !openChat">

                                <i x-show="openChat" class='bx bx-minus text-2xl font-medium'></i>
                                <i x-show="!openChat" class='bx bx-plus text-2xl font-medium'></i>
                            </button>


                        </div>
                    </div>
                    <div x-show="openChat" x-collapse>
                        {{--                        @persist('scrollbar')--}}
                        <div id="chatbox_body" x-data="{ open: false, imageUrl: '' }"
                             class="scroll-bottom h-96 overflow-y-scroll p-4 dark:scrollbar-thin-dark scrollbar-thin-light">
                            @php
                                $currentDate = null;
                                $currentHour = null;
                            @endphp
                            @foreach ($currentMessages as $message)
                                @php
                                    $messageDate = $message->created_at->format('Y-m-d');
                                    $messageHour = $message->created_at->format('H');
                                @endphp

                                    <!-- Date Separator -->
                                @if ($currentDate !== $messageDate)
                                    <div class="text-center text-xs text-gray-500 dark:text-gray-400 my-2">
                                        {{ $message->created_at->format('F j, Y') }}
                                    </div>
                                    @php
                                        $currentDate = $messageDate;
                                        $currentHour = null;  // Reset hour when date changes
                                    @endphp
                                @endif

                                <!-- Time Separator -->
                                @if ($currentHour !== $messageHour)
                                    <div class="text-center text-xs text-gray-500 dark:text-gray-400 my-1">
                                        {{ $message->created_at->format('h:i A') }}
                                    </div>
                                    @php
                                        $currentHour = $messageHour;
                                    @endphp
                                @endif

                                <!-- Message -->
                                <div id="{{$message->id}}" wire:key="{{$message->id}}" >
                                    <!-- Message Body -->
                                    <div
                                        class="flex items-center gap-4 {{ auth()->id() == $message->sender_id ? 'justify-end' : 'justify-start' }} mb-4">

                                        <!-- Delete Button with Hover Effect -->
                                        @if(auth()->id() == $message->sender_id)
                                            <button
                                                wire:click="messageDelete({{ $message->id }})"
                                                class="text-red-500 hover:text-red-700 transition-colors duration-300 text-xl bg-red-100 hover:bg-red-200 p-2 rounded-full">
                                                &times;
                                            </button>
                                        @endif

                                        <!-- Message Bubble -->
                                        <div class="relative max-w-xs rounded-lg px-4 py-3 shadow-md
        {{ auth()->id() == $message->sender_id ? 'bg-blue-500 text-white dark:bg-blue-600' : 'bg-gray-300 text-black dark:bg-gray-700 dark:text-white' }}">

                                            <!-- Message Body -->
                                            <p class="leading-tight break-words">
                                                {{ $message->body }}
                                            </p>
                                        </div>
                                    </div>


                                    <!-- Message Images -->
                                    <div
                                        class="flex {{ auth()->id() == $message->sender_id ? 'justify-end' : 'justify-start' }} mb-2" >
                                        @php
                                            $mediaCount = $message->getMedia('chatImages')->count();
                                        @endphp

                                        @if($mediaCount == 1)
                                            <!-- Single Image - Full width display -->
                                            @foreach($message->getMedia('chatImages') as $media)
                                                <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Image"
                                                     onerror="{{getErrorImage()}}"
                                                     class="w-full max-w-xs md:w-64 md:h-64 border-4 border-white dark:border-darker shadow-lg mb-4 rounded-lg cursor-pointer"
                                                     @click="open = true; imageUrl = '{{$media->getAvailableUrl(['thumb'])}}'">
                                            @endforeach

                                        @elseif($mediaCount == 2)
                                            <!-- Two Images - Side by side -->
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($message->getMedia('chatImages') as $media)
                                                    <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Image"
                                                         onerror="{{getErrorImage()}}"
                                                         class="w-full h-48 md:w-32 md:h-32 border-4 border-white dark:border-darker shadow-lg mb-4 rounded-lg cursor-pointer"
                                                         @click="open = true; imageUrl = '{{$media->getAvailableUrl(['thumb'])}}'">
                                                @endforeach
                                            </div>

                                        @else
                                            <!-- More than two images - Grid layout (2x2 grid or more) -->
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($message->getMedia('chatImages') as $media)
                                                    <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Image"
                                                         onerror="{{getErrorImage()}}"
                                                         class="w-full h-32 md:w-32 md:h-32 border-4 border-white dark:border-darker shadow-lg mb-4 rounded-lg cursor-pointer"
                                                         @click="open = true; imageUrl = '{{$media->getAvailableUrl(['thumb'])}}'">
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Full Image Preview Modal -->
                                        <div x-show="open==true"
                                             class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                                             x-transition @click.outside="open = false">
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
                            @if($currentMessages && $currentMessages->isNotEmpty())
                                @if($currentMessages->last()->sender_id == auth()->id()&& $currentMessages->last()->read == 1)
                                    <p class="text-xs text-green-500 dark:text-green-300 float-right"><i
                                            class='bx bx-show text-2xl font-medium'></i></p>
                                @elseif($currentMessages->last()->sender_id == auth()->id()&& $currentMessages->last()->read == 0)
                                    <p class="text-xs text-red-500 dark:text-red-300 float-right"><i
                                            class='bx bx-low-vision text-2xl font-medium'></i></p>
                                @endif
                            @endif
                        </div>
                        {{--                        @endpersist--}}
                        @endif
                        @if ($selectedConversation)
                            <div class="border dark:border-gray-600 p-2">
                                <div x-cloak x-show="typing && whisperReceiver === receiverId"
                                     class="flex items-center">
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
                        @endif
                    </div>
                </aside>
            </div>

    </div>
    @script
    <script>
        Alpine.data('chat', (last) => ({
            init() {
                this.tap();
                {{--                @if($currentMessages && $currentMessages->isNotEmpty())--}}
                    this.scrollToLast(this.lastId);
                {{--                @endif--}}
                Echo.private(`chat.${this.receivingId}`)
                    .listenForWhisper('typing', (e) => {
                        if (e.typing) {
                            this.whisperReceiver = e.receiver
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
            whisperReceiver: '',
            receivingId: {{auth()->id()}},
            receiverId: {{$receiver}},
            typing: false,
            activated: false,
            whisperTypingStart() {
                this.sending = Echo.private(`chat.${this.receiverId}`).whisper('typing', {
                    receiver: this.receivingId,
                    typing: true
                });
            },
            whisperTypingEnd() {
                this.sending = Echo.private(`chat.${this.receiverId}`).whisper('typing', {
                    typing: false
                })
            },


            tap() {
                const element = document.getElementById('chatbox_body');
                element.addEventListener('scroll', () => {
                    const top = element.scrollTop;
                    if (top == 0) {
                        this.height = element.scrollHeight
                        // alert(this.height)
                        // element.scrollTop = element.scrollTop + 620;
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
