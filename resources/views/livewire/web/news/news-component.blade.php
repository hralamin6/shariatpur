<div class="max-w-7xl mx-auto">
    <x-sponsor wire:ignore  title="news"/>

    <div class="mx-auto mb-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:max-w-xl">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="search" wire:model.live.debounce.800ms="search" placeholder="Search news title or content" class="block w-full rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/40 px-4 py-2 pl-10 shadow-sm transition" />
                @if(!empty($search))
                    <button type="button" wire:click="$set('search','')" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition" aria-label="Clear search">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                @endif
            </div>
            <div class="w-full md:w-[28rem] grid grid-cols-1 md:grid-cols-2 gap-3">
                <select wire:model.live="filter_category_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">All Categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filter_upazila_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">All Upazilas</option>
                    @foreach($upazilas as $upa)
                        <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($newsList as $item)
            <div wire:key="news-{{ $item->id }}" class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:border-primary">
                <a wire:navigate href="{{ route('web.news.details', $item->slug) }}" class="p-5 flex-grow block">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 h-20 w-28 rounded-lg bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-300 flex items-center justify-center overflow-hidden">
                            @php $img = method_exists($item,'getFirstMediaUrl') ? $item->getFirstMediaUrl('news','avatar') : null; @endphp
                            @if($img)
                                <img src="{{ $img }}" alt="News image" onerror="{{getErrorImage()}}" class="h-full w-full object-cover" />
                            @else
                                <i class='bx bxs-news text-3xl'></i>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $item->title }}</h3>
                            <p class="text-xs text-primary font-semibold truncate">{{ $item->category?->name }}</p>
                            @php $summary = str($item->content)->limit(100); @endphp
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ $summary }}</p>
                        </div>
                    </div>
                </a>
                <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-3 flex items-center justify-between gap-2">
                    <a wire:navigate href="{{ route('web.news.details', $item->slug) }}" class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600">@lang('Read')</a>
                    @if(auth()->check() && ($item->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'))
                        <div class="flex items-center gap-1">
                            <button type="button" class="p-1.5 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 transition-colors" title="Edit" wire:click="selectNewsForEdit({{ $item->id }})">
                                <i class='bx bxs-edit text-lg'></i>
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 transition-colors" title="Delete" wire:click="confirmDelete({{ $item->id }})">
                                <i class='bx bxs-trash text-lg'></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <span class="text-gray-500 dark:text-gray-400">No news found</span>
            </div>
        @endforelse
    </div>

    @auth
        <button wire:click="openNewsForm" class="fixed bottom-20 right-6 h-14 w-14 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary/90 transition z-30" aria-label="Add News">
            <i class='bx bx-plus text-3xl'></i>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary/90 transition z-30" aria-label="Login to add news">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <x-modal name="news-form" :show="false" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit News' : 'Add New News' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateNews' : 'createNews' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select wire:model.defer="news_category_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="">Select Category</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('news_category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upazila</label>
                    <select wire:model.defer="upazila_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="">Select Upazila</option>
                        @foreach($upazilas as $upa)
                            <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                        @endforeach
                    </select>
                    @error('upazila_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Image (URL or File) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                    <input placeholder="image link" id="image_url" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" />
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" />
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-2" wire:loading.remove wire:target="photo">
                        @if($image_url)
                            <img src="{{ $image_url }}" alt="Preview" onerror="{{getErrorImage()}}" class="h-24 w-36 rounded-md object-cover border" />
                        @elseif($photo)
                            <img src="{{ $photo->temporaryUrl() }}" onerror="{{getErrorImage()}}" alt="Preview" class="h-24 w-36 rounded-md object-cover border" />
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" wire:model.defer="title" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="News title">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                    <textarea rows="6" wire:model.defer="content" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Write content..."></textarea>
                    @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pinned</label>
                    <input type="checkbox" wire:model.defer="is_pinned" class="mt-2 h-4 w-4 text-primary border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'news-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow">{{ $selectedId ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="delete-news" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete News</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this news?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-news')">Cancel</button>
                <button type="button" wire:click="deleteSelectedNews" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow">Delete</button>
            </div>
        </div>
    </x-modal>
</div>

