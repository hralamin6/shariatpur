<div>
    <div class="mx-auto">
        <!-- Search + Filter Bar -->
        <div class="flex flex-wrap gap-4 items-center justify-between mb-4 bg-gray-100 dark:bg-gray-900 z-20 py-4 px-3 rounded-lg">
            <div class="relative max-w-2xl grow">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search notices..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
            </div>
            <div class="flex gap-3">
                <select wire:model.live="filter_pinned" class="min-w-[140px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-8 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                    <option value="">Pinned: Any</option>
                    <option value="1">Pinned</option>
                    <option value="0">Not pinned</option>
                </select>
            </div>
        </div>

        <!-- Banner -->
        <div class="mb-6">
            <div class="relative rounded-lg overflow-hidden shadow-lg max-w-fit mx-auto">
                <img src="https://placehold.co/1200x400/6366f1/ffffff?text=Notices" alt="Notices" class="w-full h-auto object-cover">
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex space-x-2">
                    <span class="block w-2.5 h-2.5 bg-white rounded-full"></span>
                    <span class="block w-2.5 h-2.5 bg-white/60 rounded-full"></span>
                    <span class="block w-2.5 h-2.5 bg-white/60 rounded-full"></span>
                </div>
            </div>
        </div>

        <!-- Notices List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($notices as $notice)
                <div wire:key="notice-{{ $notice->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex flex-col relative">
                    @php $canManage = auth()->check() && ($notice->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp

                    <div class="absolute top-0 left-0 flex items-center gap-2">
                        @if($notice->pinned)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 px-2 py-1 rounded-full">
                                <i class='bx bxs-pin'></i> Pinned
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
                                </button>
                                <button type="button" class="p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 shadow-sm" title="Delete" wire:click="confirmDelete({{ $notice->id }})">
                                    <i class='bx bxs-trash text-base'></i>
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

    <!-- Floating Action Button -->
    @auth
        <button wire:click="openNoticeForm" class="fixed bottom-6 right-6 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Add Notice">
            <i class='bx bx-plus text-3xl'></i>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-6 right-6 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Login to add notice">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

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
                    <button type="submit" class="px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600 shadow">{{ $selectedId ? 'Update' : 'Save' }}</button>
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
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow" wire:click="deleteSelectedNotice">Delete</button>
            </div>
        </div>
    </x-modal>

    <!-- Notice Details Modal -->
    <x-modal name="notice-details" :show="false" maxWidth="xl" focusable>
        <div class="relative flex flex-col bg-white dark:bg-gray-900">
            <div class="p-6 sm:p-8 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700/50">
                <div class="relative text-center">
                    <button type="button" class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 z-10 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" @click="$dispatch('close-modal', 'notice-details')" aria-label="Close">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                    <div class="relative h-24 w-24 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg mx-auto mb-4">
                        @php $photo = $noticeDetails['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" alt="Notice photo" class="h-full w-full object-cover" onerror="this.src='{{ asset('images/placeholder.png') }}'" />
                        @else
                            <div class="h-full w-full bg-indigo-100 dark:bg-gray-700 flex items-center justify-center">
                                <i class='bx bx-bell text-4xl text-indigo-600 dark:text-indigo-300'></i>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-3">{{ $noticeDetails['title'] ?? '' }}</h2>

                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 max-h-[60vh] overflow-y-auto">
                @if(($noticeDetails['content'] ?? null))
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Details</h3>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            <p>{{ $noticeDetails['content'] }}</p>
                        </div>
                    </div>
                @endif

                @if(($noticeDetails['created_by'] ?? null))
                    <p class="text-xs text-gray-500 dark:text-gray-400">Added by: {{ $noticeDetails['created_by'] }}</p>
                @endif
            </div>
        </div>
    </x-modal>
</div>

