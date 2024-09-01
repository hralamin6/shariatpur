<div class="m-2" x-data="chat({{ $messages && $messages->isNotEmpty() ? $messages->last()->id : 'null' }})">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <div class="col-span-3 lg:col-span-1 rounded-xl border border-gray-300 dark:border-gray-500 bg-white dark:bg-darkSidebar {{$selectedConversation?'hidden lg:block':''}}">
            <div class="h-16 border-b border-gray-300 dark:border-gray-500 flex justify-between gap-2 items-center capitalize px-2">
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
                                <button wire:click.prevent="loadConversation({{ $conversation }}, '{{ $this->getChatUserInstance($conversation, $name = 'id') }}')" type="button" class="flex items-center w-full text-left">
                                    <img class="rounded-full h-12 w-12 object-cover" src="https://ui-avatars.com/api/?name={{ $this->getChatUserInstance($conversation, $name = 'name') }}" alt="">
                                    <div class="ml-3 flex-1">
                                        <div class="flex justify-between items-center mb-1">
                                            <h2 class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">
                                                {{ $this->getChatUserInstance($conversation, $name = 'name') }}
                                            </h2>
                                            @if($unreadMessagesCount)
                                                <span class="flex-shrink-0 w-5 h-5 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full">
                                        {{ $unreadMessagesCount }}
                                    </span>
                                            @endif
                                        </div>
                                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                                            <p class="truncate flex-1">
                                                {{ $conversation->messages->last()?->body }}
                                            </p>
                                            <p class="ml-2 whitespace-nowrap">
                                                {{ $conversation->messages->last()?->created_at->shortAbsoluteDiffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                                <div x-cloak x-show="typing && whisperReceiver === {{ $this->getChatUserInstance($conversation, $name = 'id') }}" class="absolute right-4 flex items-center space-x-1">
                                    <div class="w-2 h-2 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>
                                    <div class="w-2 h-2 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>
                                    <div class="w-2 h-2 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>
                                </div>
                            </li>
                        @empty
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400">No conversation found</p>
                            <a href="{{route('app.users')}}" wire:navigate class="text-center text-lg text-green-600 dark:text-gray-400">@lang('select user to start conversation')</a>
                        @endforelse
                    </ul>
                </div>
            </div>


        </div>
        @if ($selectedConversation)
            <div class="col-span-3 lg:col-span-2" x-data="{openChat: $persist(true)}">
                <aside class="rounded-xl border border-gray-300 dark:border-gray-500 bg-white dark:bg-darkSidebar">
                    <div class="h-16 border-b dark:border-gray-500 flex justify-between gap-2 items-center capitalize px-6">
                        <button wire:click.prevent="resetComponent" class="text-gray-500 dark:text-gray-300">
                            <i class='bx bx-arrow-back text-2xl font-medium'></i>

                        </button>
                        <div class="flex gap-2 items-center justify-start">
                            <img class="rounded-full p-2 h-16" src="https://ui-avatars.com/api/?name={{$this->getChatUserInstance($selectedConversation, $name = 'name')}}" alt="">
                            <h2 class="text-lg font-medium text-gray-700 dark:text-gray-300 truncate"> {{ $this->getChatUserInstance($selectedConversation, $name = 'name') }}</h2>
                        </div>
                        <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
                            <button class="" @click="openChat = !openChat">

                                <i x-show="openChat" class='bx bx-minus text-2xl font-medium'></i>
                                <i x-show="!openChat"class='bx bx-plus text-2xl font-medium'></i>
                            </button>


                        </div>
                    </div>
                    <div x-show="openChat" x-collapse>
{{--                        @persist('scrollbar')--}}
                        <div id="chatbox_body" class="scroll-bottom h-96 overflow-y-scroll p-4 dark:scrollbar-thin-dark scrollbar-thin-light">
                            @php
                                $currentDate = null;
                                $currentHour = null;
                            @endphp
                            @foreach ($messages as $message)
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
                                <div id="{{$message->id}}" class="flex {{ auth()->id() == $message->sender_id ? 'justify-end' : 'justify-start' }} mb-2">
                                    <div class="max-w-xs rounded-lg px-4 py-2 text-white {{ auth()->id() == $message->sender_id ? 'bg-blue-500 dark:bg-blue-600' : 'bg-gray-300 dark:bg-gray-700' }}">
                                        {{ $message->body }}
                                    </div>
                                </div>
                            @endforeach
                                    @if($messages && $messages->isNotEmpty())
                                    @if($messages->last()->sender_id == auth()->id()&& $messages->last()->read == 1)
                                        <p class="text-xs text-green-500 dark:text-green-300 float-right"><i class='bx bx-show text-2xl font-medium'></i></p>
                                    @elseif($messages->last()->sender_id == auth()->id()&& $messages->last()->read == 0)
                                        <p class="text-xs text-red-500 dark:text-red-300 float-right"><i class='bx bx-low-vision text-2xl font-medium'></i></p>
                                    @endif
                                    @endif
                        </div>
{{--                        @endpersist--}}
                        @endif
                        @if ($selectedConversation)
                            <div class="border dark:border-gray-600 p-2">
                                <div x-cloak x-show="typing && whisperReceiver === receiverId" class="flex items-center">
                                    <div class="w-2 h-3 bg-gray-400 dark:bg-gray-400 rounded-full mr-2 animate-pulse"></div>
                                    <div class="w-2 h-3 bg-gray-400 dark:bg-gray-400 rounded-full mr-2 animate-pulse"></div>
                                    <div class="w-2 h-3 bg-gray-400 dark:bg-gray-400 rounded-full animate-pulse"></div>
                                </div>
                                <form wire:submit.prevent="sendMessage" x-on:submit="whisperTypingEnd()" class="flex gap-2 justify-center mx-4">
                                    <input
                                        x-on:click.outside="whisperTypingEnd()"
                                        x-on:input.debounce.2000ms="whisperTypingStart()"
                                        wire:model="body" class="border border-gray-300 rounded-l-md h-9 dark:bg-gray-600 dark:border-gray-500" type="text" name="message" id="message">
                                    <button type="submit" class="border border-gray-300 capitalize px-3 py-1 bg-blue-500 rounded-r-md text-white dark:border-gray-500">
                                        <span>@lang('send')</span>  <span wire:loading wire:target="sendMessage">...</span>
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
{{--                @if($messages && $messages->isNotEmpty())--}}
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
                        setTimeout(() => { this.typing = false; }, 5000)
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
                        element.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                        // element.scrollIntoView({behavior: 'smooth'})
                        element.classList.add('animate-pulse');
                        // element.scrollTop = element.scrollTop + 620;
                    });
                });

            },
            lastId:last,
            sending:0,
            height:'',
            whisperReceiver:'',
            receivingId: {{auth()->id()}},
            receiverId: {{$receiver}},
            typing:false,
            activated:false,
            whisperTypingStart(){
                this.sending = Echo.private(`chat.${this.receiverId}`) .whisper('typing', {
                    receiver: this.receivingId,
                    typing: true
                });
            },
            whisperTypingEnd(){
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
