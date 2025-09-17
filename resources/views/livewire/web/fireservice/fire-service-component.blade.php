<div>
    <div class="mx-auto">
        <x-sponsor wire:ignore  title="fire-service"/>

        <!-- Search Bar + Upazila Filter -->
        <div class="flex gap-4 justify-between mb-4 bg-gray-100 dark:bg-gray-900 z-20 py-4">
            <div class="relative max-w-2xl mx-auto">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="text" wire:model.live.debounce.600ms="search" placeholder="ফায়ার সার্ভিস খুঁজুন..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm">
            </div>
            <div class="max-w-2xl mx-auto">
                <select wire:model.live="filter_upazila_id" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm">
                    <option value="">All Upazilas</option>
                    @foreach($upazilas as $upa)
                        <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <!-- Fire Service List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($fireServices as $fs)
                <div wire:key="fs-{{ $fs->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex flex-col group relative">
                    @php $canManage = auth()->check() && ($fs->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp
                    <div class="absolute top-2 right-2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        @if($canManage)
                            <button type="button" class="p-1.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow inline-flex items-center gap-1" title="Edit" wire:click="selectFireServiceForEdit({{ $fs->id }})" wire:loading.attr="disabled" wire:target="selectFireServiceForEdit({{ $fs->id }})">
                                <i class='bx bxs-edit text-base' wire:loading.remove wire:target="selectFireServiceForEdit({{ $fs->id }})"></i>
                                <span class="inline-flex items-center" wire:loading.delay wire:target="selectFireServiceForEdit({{ $fs->id }})">
                                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                </span>
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-600 text-white hover:bg-red-700 shadow inline-flex items-center gap-1" title="Delete" wire:click="confirmDelete({{ $fs->id }})" wire:loading.attr="disabled" wire:target="confirmDelete({{ $fs->id }})">
                                <i class='bx bxs-trash text-base' wire:loading.remove wire:target="confirmDelete({{ $fs->id }})"></i>
                                <span class="inline-flex items-center" wire:loading.delay wire:target="confirmDelete({{ $fs->id }})">
                                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                </span>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-red-700 dark:text-red-300 font-semibold">
                            <img src="{{getUserProfileImage($fs->user)}}" alt="User" onerror="{{getErrorProfile($fs->user)}}" class="h-10 w-10 rounded-full" />
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $fs->user?->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($fs->created_at)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex-grow flex flex-col sm:flex-row gap-4">
                        <div class="sm:w-32 md:w-40 flex-shrink-0 h-32">
                            @php $img = method_exists($fs,'getFirstMediaUrl') ? $fs->getFirstMediaUrl('fireservice','avatar') : ''; @endphp
                            @if(!empty($img))
                                <img src="{{ $img }}" alt="Fire service image" onerror="{{getErrorImage()}}" class="w-full h-32 sm:h-full object-cover rounded-md">
                            @else
                                <img src="https://placehold.co/600x400/EF4444/FFFFFF?text=Fire+Service" alt="Fire service placeholder" class="w-full h-32 sm:h-full object-cover rounded-md">
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col">
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $fs->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $fs->upazila?->name }}</p>
                                @if($fs->address)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $fs->address }}</p>
                                @endif
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                @if($fs->map)
                                    <a href="{{ $fs->map }}" target="_blank" rel="noopener" class="w-full text-center px-4 py-2 border border-red-500 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-500/10 transition">Google Map</a>
                                @else
                                    <button type="button" disabled class="w-full text-center px-4 py-2 border border-gray-300 text-gray-400 rounded-lg text-sm font-semibold cursor-not-allowed">Google Map</button>
                                @endif
                                @if($fs->phone)
                                    <a href="tel:{{ $fs->phone }}" class="w-full text-center px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition">Hotline</a>
                                @else
                                    <button type="button" disabled class="w-full text-center px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-semibold cursor-not-allowed">Hotline</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400">No fire service found</span>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Floating Action Button -->
    @auth
        <button wire:click="openFireServiceForm" class="fixed bottom-20 right-6 h-14 w-14 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition z-30 relative" aria-label="Add Fire Service" wire:loading.attr="disabled" wire:target="openFireServiceForm">
            <i class='bx bx-plus text-3xl' wire:loading.remove wire:target="openFireServiceForm"></i>
            <span class="absolute inset-0 flex items-center justify-center" wire:loading.delay wire:target="openFireServiceForm">
                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
            </span>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition z-30" aria-label="Login to add fire service">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <!-- Create/Update Fire Service Modal -->
    <x-modal name="fire-service-form" :show="false" maxWidth="xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Fire Service' : 'Add New Fire Service' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateFireService' : 'createFireService' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upazila</label>
                    <select wire:model.defer="upazila_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500">
                        <option value="">Select Upazila</option>
                        @foreach($upazilas as $upa)
                            <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                        @endforeach
                    </select>
                    @error('upazila_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Photo (URL or File) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                    <input placeholder="image link" id="image_url" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" />
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" />
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-2" wire:loading.remove wire:target="photo">
                        @if($image_url)
                            <img src="{{ $image_url }}" alt="Preview" onerror="{{getErrorImage()}}" class="h-20 w-20 rounded-md object-cover border" />
                        @elseif($photo)
                            <img src="{{ $photo->temporaryUrl() }}" onerror="{{getErrorImage()}}" alt="Preview" class="h-20 w-20 rounded-md object-cover border" />
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="Fire service name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="mt-1 w-full rounded_md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="e.g., 0123456789">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <textarea wire:model.defer="address" rows="3" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="Street, area, etc."></textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Map URL</label>
                    <input type="url" wire:model.defer="map" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="https://maps.google.com/...">
                    @error('map')<p class="mt-1 text_sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'fire-service-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-red-500 text-white hover:bg-red-600 shadow inline-flex items-center gap-2" wire:loading.attr="disabled" wire:target="createFireService,updateFireService">
                        <span wire:loading.remove wire:target="createFireService,updateFireService">{{ $selectedId ? 'Update' : 'Save' }}</span>
                        <span class="inline-flex items-center" wire:loading.delay wire:target="createFireService,updateFireService">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                            <span class="sr-only">Saving</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-fire-service" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Fire Service</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this fire service?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-fire-service')">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow inline-flex items-center gap-2" wire:click="deleteSelectedFireService" wire:loading.attr="disabled" wire:target="deleteSelectedFireService">
                    <span wire:loading.remove wire:target="deleteSelectedFireService">Delete</span>
                    <span class="inline-flex items-center" wire:loading.delay wire:target="deleteSelectedFireService">
                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        <span class="sr-only">Deleting</span>
                    </span>
                </button>
            </div>
        </div>
    </x-modal>
</div>
