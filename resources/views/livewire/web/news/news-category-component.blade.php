<div class="max-w-7xl mx-auto">
    <x-sponsor wire:ignore  title="news-category"/>


    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        @forelse($categories as $category)
            <div wire:key="news-cat-{{ $category->id }}" class="border border-gray-200 dark:border-gray-700 hover:border-primary flex flex-col bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                @php $canManage = auth()->check() && ($category->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp

                <a wire:navigate href="{{ route('web.news', $category->id) }}" class="p-4 flex-grow flex flex-col items-center justify-center text-center">
                    <div class="text-4xl mb-2 text-sky-500">
                        <i class='bx bxs-news'></i>
                    </div>
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $category->name }}</div>
                </a>

                @if($canManage)
                    <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-2 flex items-center justify-center gap-2">
                        @can('app.news_categories.edit')
                            <button type="button" class="p-1.5 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 shadow-sm" title="Edit" wire:click.stop="selectCategoryForEdit({{ $category->id }})" @click.stop>
                                <i class='bx bxs-edit text-base'></i>
                                <x-loader target="selectCategoryForEdit({{ $category->id }})" />
                            </button>
                        @endcan
                        @can('app.news_categories.delete')
                            <button type="button" class="p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 shadow-sm" title="Delete" wire:click="confirmDelete({{ $category->id }})">
                                <i class='bx bxs-trash text-base'></i>
                                <x-loader target="confirmDelete({{ $category->id }})" />
                            </button>
                        @endcan
                    </div>
                @endif
            </div>
        @empty
            <div class="flex items-center justify-center col-span-full p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <span class="text-gray-500 dark:text-gray-400">No news categories available</span>
            </div>
        @endforelse
    </div>

    @auth
        <div x-data class="fixed bottom-20 right-6 z-40">
            <button type="button" class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary text-white shadow-lg hover:shadow-xl hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition" @click="$dispatch('open-modal', 'create-news-category')" wire:click="resetForm" aria-label="Add News Category">
                <i class="bx bx-plus text-3xl bx-tada"></i>
                <x-loader target="resetForm" />
            </button>
        </div>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary text-white shadow-lg hover:shadow-xl hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition" aria-label="Login to add">
            <i class="bx bx-plus text-3xl bx-tada"></i>
        </a>
    @endguest

    <x-modal name="create-news-category" :show="false" maxWidth="md" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New News Category</h2>
            <form wire:submit.prevent="createCategory" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="e.g., Local, National, Sports">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'create-news-category')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow">Save
                        <x-loader target="createCategory" />
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="edit-news-category" :show="false" maxWidth="md" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit News Category</h2>
            <form wire:submit.prevent="updateCategory" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'edit-news-category')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow">Update
                        <x-loader target="updateCategory" />
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="delete-news-category" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete News Category</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this category?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-news-category')">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow" wire:click="deleteSelectedCategory">Delete
                    <x-loader target="deleteSelectedCategory" />
                </button>
            </div>
        </div>
    </x-modal>
</div>
