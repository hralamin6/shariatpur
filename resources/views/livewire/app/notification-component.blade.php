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
        <div class="border-b pb-4 mb-4 flex justify-between gap-2">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Notifications</h2>
            <div class="flex gap-2 justify-center">
                <button wire:click="$set('activity', 'notification')" class="text-xs px-4 py-2 bg-green-500 text-white dark:bg-green-700 rounded hover:bg-green-600 dark:hover:bg-green-800 capitalize
                {{ $activity == 'notification' ? 'bg-green-700 dark:bg-green-900' : '' }}
                ">
                    @lang("notifications")
                </button>
                <button wire:click="$set('activity', 'myActivity')" class="text-xs px-4 py-2 bg-green-500 text-white dark:bg-green-700 rounded hover:bg-green-600 dark:hover:bg-green-800 capitalize
                {{ $activity == 'myActivity' ? 'bg-green-700 dark:bg-green-900' : '' }}
                ">
                    @lang("my activities")
                </button>
                @role('admin')
                <button wire:click="$set('activity', 'allActivity')" class="text-xs px-4 py-2 bg-green-500 text-white dark:bg-green-700 rounded hover:bg-green-600 dark:hover:bg-green-800 capitalize
                {{ $activity == 'allActivity' ? 'bg-green-700 dark:bg-green-900' : '' }}
                ">
                    @lang("all activities")
                </button>
                @endrole

            </div>

        </div>
        <!-- Filter Buttons -->
        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-2 justify-between text-xs">
            <div class="mb-4 flex justify-start space-x-4">
                <button wire:click="$set('filter', 'all')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 {{ $filter == 'all' ? 'dark:bg-slate-500 bg-gray-300  ' : '' }}">
                    All
                </button>
                <button wire:click="$set('filter', 'unread')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 {{ $filter == 'unread' ? 'dark:bg-slate-500 bg-gray-300  ' : '' }}">
                    Unread
                </button>
                <button wire:click="$set('filter', 'read')" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 {{ $filter == 'read' ? 'dark:bg-slate-500 bg-gray-300  ' : '' }}">
                    Read
                </button>
            </div>

            <div class="mb-4 flex justify-start space-x-4">
                <button wire:click="$set('type', '')" class="px-4 capitalize py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 {{ $type == '' ? 'dark:bg-slate-500 bg-gray-300  ' : '' }}">
                    @lang('all')
                </button>
                <button wire:click="$set('type', 'edited')" class="px-4 capitalize py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 {{ $type == 'edited' ? 'dark:bg-slate-500 bg-gray-300  ' : '' }}">
                    @lang('edited')
                 </button>
                <button wire:click="$set('type', 'deleted')" class="px-4 capitalize py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 {{ $type == 'deleted' ? 'dark:bg-slate-500 bg-gray-300  ' : '' }}">
                    @lang('deleted')
                </button>
                <button wire:click="$set('type', 'created')" class="px-4 capitalize py-2 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 {{ $type == 'created' ? 'dark:bg-slate-500 bg-gray-300  ' : '' }}">
                    @lang('created')

                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="divide-y divide-gray-300 dark:divide-gray-500">
            @forelse($notifications as $notification)
                <div x-data="{ open: false }" class="p-4 flex flex-col space-y-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <!-- Notification Header -->
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-700 dark:text-gray-300 cursor-pointer text-sm" @click="open = !open">
                                <a href="{{@$notification->data['link']}}" wire:navigate>
                                    {{$notification->data['message']}}
                                </a>

                                by
                                <a class="text-blue-500" href="{{route('app.user.detail', \App\Models\User::find($notification->data['changedById']))}}" wire:navigate>

                                    <code>{{$notification->data['changedByName']}} ({{$notification->data['changedByRole']}})</code>

                                </a>
                            </p>

                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Actions: Mark as Read & Delete -->
                        <div class="flex space-x-2 text-xs">
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
                                                @foreach(@$notification->data['model'] as $key => $value)
                                                    <article class="text-gray-600 dark:text-gray-400 trix-content prose dark:prose-invert">{{ ucfirst(@$key) }}: {!! @$value !!}</article>
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
        <div class="my-4 flex justify-between gap-4 text-xs" >
            <button wire:click="markAllAsRead" class="px-4 py-2 bg-green-500 text-white dark:bg-green-700 rounded hover:bg-green-600 dark:hover:bg-green-800">
                Mark All as Read
            </button>
            <button wire:click="deleteAll" class="px-4 py-2 bg-red-500 text-white dark:bg-red-700 rounded hover:bg-red-600 dark:hover:bg-red-800">
                Delete all
            </button>
        </div>
        <div class="mx-auto my-4 px-4 overflow-y-auto">{{ $notifications->links() }}</div>

    </div>
</div>



