{{--<div class="container mx-auto px-4 py-8">--}}
{{--    <h1 class="text-4xl font-bold mb-4 text-gray-900 dark:text-gray-100">--}}
{{--        {{ $page->title }}--}}
{{--    </h1>--}}
{{--    <article class="trix-content prose dark:prose-invert prose-h1:text-red-500 dark:prose-h1:text-red-200 ">{!! $page->content !!}</article>--}}

{{--    <a href="{{route('app.pages')}}" wire:navigate class="m-8">adsf</a>--}}
{{--    {{$page->content}}--}}
{{--</div>--}}

<div class="max-w-4xl mx-auto mt-8">
    <div class="bg-white dark:bg-darker shadow-lg rounded-lg p-4">
        <div class="border-b pb-4 mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Notifications</h2>
        </div>
        <!-- Filter Buttons -->
        <div class="mb-4 flex justify-start space-x-4">
            <button wire:click="setFilter('all')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 {{ $filter == 'all' ? 'bg-gray-300 dark:bg-gray-600' : '' }}">
                All
            </button>
            <button wire:click="setFilter('unread')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 {{ $filter == 'unread' ? 'bg-gray-300 dark:bg-gray-600' : '' }}">
                Unread
            </button>
            <button wire:click="setFilter('read')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 {{ $filter == 'read' ? 'bg-gray-300 dark:bg-gray-600' : '' }}">
                Read
            </button>
        </div>
        <!-- Notifications List -->
        <div class="divide-y divide-gray-300 dark:divide-gray-500">
            @forelse($notifications as $notification)
                <div x-data="{ open: false }" class="p-4 flex flex-col space-y-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <!-- Notification Header -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-700 dark:text-gray-300 cursor-pointer" @click="open = !open">
                               A {{$notification->data['className']}}
                                was been
                                {{$notification->data['type']}} by
                                <a class="text-blue-500" href="{{route('app.user.detail', \App\Models\User::find($notification->data['changedById']))}}" wire:navigate>
                                    {{$notification->data['changedByName']}}
                                    ({{$notification->data['changedByRole']}})
                                </a>

                            </p>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Actions: Mark as Read & Delete -->
                        <div class="flex space-x-2">
                            @if(!$notification->read_at)
                                <button wire:click="markAsRead('{{ $notification->id }}')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500">
                                    Mark as Read
                                </button>
                            @endif
                            <button wire:click="deleteNotification('{{ $notification->id }}')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-500">
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Model Details: Toggle Visibility -->
                    @if(isset($notification->data['model']))
                                            <div x-show="open" x-cloak class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                                <p class="text-gray-800 dark:text-gray-200"><strong>Model Details:</strong></p>
                                                @foreach($notification->data['model'] as $key => $value)
                                                    <p class="text-gray-600 dark:text-gray-400">{{ ucfirst(@$key) }}: {!! @$value !!}</p>
                                                @endforeach
                                            </div>
                    @endif
                </div>
            @empty
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                    No notifications found.
                </div>
            @endforelse
        </div>
    </div>
</div>



