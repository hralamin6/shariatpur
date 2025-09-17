<div>
    <div class="mx-auto">
        <x-sponsor wire:ignore  title="notice"/>

        <!-- Search + Filter Bar -->
        <div class="flex flex-wrap gap-4 items-center justify-between bg-gray-100 dark:bg-gray-900 z-20 py-4 px-3 rounded-lg">
            <div class="relative max-w-2xl grow">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search notices..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
            </div>
        </div>

        <!-- Banner -->

        <!-- Notices List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($notices as $notice)
                <div wire:key="notice-{{ $notice->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex flex-col relative">
                    @php $canManage = auth()->check() && ($notice->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp

                    <div class="absolute top-0 left-0 flex items-center gap-2">
                        @if($notice->pinned)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 px-2 py-1 rounded-full">
                                <i class='bx bxs-pin'></i>
                            </span>
                        @endif
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-semibold overflow-hidden">
                            @php $img = method_exists($notice,'getFirstMediaUrl') ? $notice->getFirstMediaUrl('notice','avatar') : ''; @endphp
                            @if(!empty($img))
                                <img src="{{ $img }}" alt="Notice" class="h-12 w-12 object-cover" onerror="this.src='{{ asset('images/placeholder.png') }}'" />
                            @else
                                <i class='bx bx-bell text-xl'></i>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $notice->title }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($notice->created_at)->format('d M Y, h:i A') }}</p>
                        </div>
                        @if($canManage)
                            <div class="flex items-center gap-2">
                                <button type="button" class="p-1.5 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 shadow-sm" title="Edit" wire:click="selectNoticeForEdit({{ $notice->id }})">
                                    <i class='bx bxs-edit text-base'></i>
                                    <x-loader target="selectNoticeForEdit({{ $notice->id }})" />
                                </button>
                                <button type="button" class="p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 shadow-sm" title="Delete" wire:click="confirmDelete({{ $notice->id }})">
                                    <i class='bx bxs-trash text-base'></i>
                                    <x-loader target="confirmDelete({{ $notice->id }})" />
                                </button>
                            </div>
                        @endif
                    </div>

                    @if($notice->summary)
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $notice->summary }}</p>
                    @endif

                    <div class="mt-4 flex items-center gap-3">
                        <a wire:navigate href="{{route('web.notice.details', $notice->id)}}" type="button" class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">Details</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400">No notices found</span>
                </div>
            @endforelse
        </div>

        <div class="mt-8"></div>
    </div>

    <x-plus wireClick="openNoticeForm"/>
    <!-- Notice Form Modal -->
    <x-modal name="notice-form" :show="false" maxWidth="xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Notice' : 'Add New Notice' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateNotice' : 'createNotice' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image</label>
                    <input placeholder="image link" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-2" wire:loading.remove wire:target="photo">
                        @if($image_url)
                            <img src="{{ $image_url }}" alt="Preview" class="h-20 w-20 rounded-md object-cover border" onerror="this.src='{{ asset('images/placeholder.png') }}'" />
                        @elseif($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-20 w-20 rounded-md object-cover border" onerror="this.src='{{ asset('images/placeholder.png') }}'" />
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" wire:model.defer="title" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Notice title">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                    <textarea rows="4" wire:model.defer="content" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Full notice content..."></textarea>
                    @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-2">
                    <input id="pinned" type="checkbox" wire:model.defer="pinned" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="pinned" class="text-sm text-gray-700 dark:text-gray-300">Pinned</label>
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'notice-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600 shadow">
                        {{ $selectedId ? 'Update' : 'Save' }}
                        <x-loader target="{{ $selectedId ? 'updateNotice' : 'createNotice' }}" />
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-notice" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Notice</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this notice?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-notice')">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow" wire:click="deleteSelectedNotice">
                    Delete
                    <x-loader target="deleteSelectedNotice" />
                </button>
            </div>
        </div>
    </x-modal>
</div>
