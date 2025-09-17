<div class="max-w-7xl mx-auto">
    <x-sponsor wire:ignore  title="launch"/>
    <div class="mx-auto mb-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:max-w-xl">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input
                    type="search"
                    wire:model.live.debounce.800ms="search"
                    placeholder="Search by launch name, phone, or details"
                    class="block w-full rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/40 px-4 py-2 pl-10 shadow-sm transition"
                />
                @if(!empty($search))
                    <button type="button" wire:click="$set('search','')" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition" aria-label="Clear search">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                @endif
            </div>
            <div class="w-full md:w-80">
                <select wire:model.live="filter_route_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">All Routes</option>
                    @foreach($routes as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Launch Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($launches as $launch)
            <div wire:key="launch-{{ $launch->id }}" class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:border-primary">
                <div class="p-5 flex-grow">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 h-24 rounded-full bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-300 flex items-center justify-center">
                            <img src="{{ $launch->getFirstMediaUrl('launch', 'avatar') }}" alt="Preview" onerror="{{getErrorImage()}}" class="h-20 w-20 rounded-md object-cover border" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $launch->name }}</h3>
                            <p class="text-xs text-primary font-semibold truncate">{{ $launch->route?->name }}</p>
                            @if($launch->details)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 line-clamp-2">{{ Str::limit(strip_tags($launch->details), 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-3 flex items-center justify-between gap-2">
                    <div class="flex gap-2">
                        @if($launch->map_one)
                            <a class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600" target="_blank" rel="noopener" href="{{ $launch->map_one }}">Map 1</a>
                        @endif
                        @if($launch->map_two)
                            <a class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600" target="_blank" rel="noopener" href="{{ $launch->map_two }}">Map 2</a>
                        @endif
                        @if($launch->phone)
                            <a class="px-3 py-1 rounded-full bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300 text-xs font-bold hover:bg-cyan-200 dark:hover:bg-cyan-800" href="tel:{{ $launch->phone }}">Call</a>
                        @endif
                    </div>

                    <div class="flex items-center gap-1">
                        <button type="button" class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 inline-flex items-center gap-2" title="Details" wire:click="showDetails({{ $launch->id }})">
                            @lang('Details')
                            <x-loader target="showDetails({{ $launch->id }})" />
                        </button>
                        @if(auth()->check() && ($launch->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'))
                            <button type="button" class="p-1.5 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 transition-colors inline-flex items-center gap-1" title="Edit" wire:click="selectLaunchForEdit({{ $launch->id }})">
                                <i class='bx bxs-edit text-lg'></i>
                                <x-loader target="selectLaunchForEdit({{ $launch->id }})" />
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 transition-colors inline-flex items-center gap-1" title="Delete" wire:click="confirmDelete({{ $launch->id }})">
                                <i class='bx bxs-trash text-lg'></i>
                                <x-loader target="confirmDelete({{ $launch->id }})" />
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <span class="text-gray-500 dark:text-gray-400">No launches found</span>
            </div>
        @endforelse
    </div>

    @auth
        <button wire:click="openLaunchForm" class="fixed bottom-20 right-6 h-14 w-14 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary/90 transition z-30" aria-label="Add Launch">
            <i class='bx bx-plus text-3xl'></i>
            <x-loader target="openLaunchForm" />
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary/90 transition z-30" aria-label="Login to add launch">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <!-- Unified Create/Update Modal -->
    <x-modal name="launch-form" :show="false" maxWidth="xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Launch' : 'Add New Launch' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateLaunch' : 'createLaunch' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Route</label>
                    <select wire:model.defer="launch_route_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="">Select Route</option>
                        @foreach($routes as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('launch_route_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Launch name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="e.g., 0123456789">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2" wire:ignore>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Details</label>
                    <trix-editor class="formatted-content  border border-gray-500" x-data x-on:trix-change="$dispatch('input', event.target.value)"
                                 wire:model.debounce.1000ms="details" wire:key="uniqueKey2"></trix-editor>
                    @error('details')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Map URL 1</label>
                    <input type="url" wire:model.defer="map_one" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="https://maps.google.com/...">
                    @error('map_one')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text_sm font-medium text-gray-700 dark:text-gray-300">Google Map URL 2</label>
                    <input type="url" wire:model.defer="map_two" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="https://maps.google.com/...">
                    @error('map_two')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Photo (URL or File) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                    <input placeholder="image link" id="image_url" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500" />
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded_md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-teal-500 focus:ring-teal-500" />
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

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'launch-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow inline-flex items-center gap-2">
                        <span>{{ $selectedId ? 'Update' : 'Save' }}</span>
                        <x-loader target="{{ $selectedId ? 'updateLaunch' : 'createLaunch' }}" />
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Modal -->
    <x-modal name="delete-launch" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Launch</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this launch?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-launch')">Cancel</button>
                <button type="button" wire:click="deleteSelectedLaunch" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow">
                    Delete
                    <x-loader target="deleteSelectedLaunch" />
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Launch Details Modal -->
    <x-modal name="launch-details" :show="false" maxWidth="xl" focusable>
        <div class="p-0">
            <div class="relative bg-primary/10 dark:bg-gray-800 p-6 sm:p-8">
                <div class="flex items-center gap-5">
                    <div class="relative h-20 w-28 sm:h-28 sm:w-40 rounded-lg overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg flex-shrink-0">
                        @php $photo = $launchDetails['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" onerror="{{getErrorImage()}}" alt="Launch photo" class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full bg-primary/20 flex items-center justify_center">
                                <i class='bx bxs-ship text-4xl text-primary'></i>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $launchDetails['name'] ?? '' }}</h2>
                        <p class="text-sm text-primary font-semibold">{{ $launchDetails['route'] ?? '' }}</p>
                        @if(($launchDetails['created_by'] ?? null))
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">@lang('Added by'): {{ $launchDetails['created_by'] }}</p>
                        @endif
                        @if(($launchDetails['created_at'] ?? null))
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $launchDetails['created_at'] }}</p>
                        @endif
                    </div>
                    <button type="button" class="absolute top-4 right-4 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" @click="$dispatch('close-modal', 'launch-details')">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white dark:bg-gray-900">
                @if(($launchDetails['details'] ?? null))
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">@lang('Details')</h4>
                        <div class="prose dark:prose-invert max-w-none">
                            {!! $launchDetails['details'] !!}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items_center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bxs-phone-call text-primary'></i>
                            <span class="font-medium">@lang('Phone')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $launchDetails['phone'] ?? '' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bx-navigation text-primary'></i>
                            <span class="font-medium">@lang('Maps')</span>
                        </div>
                        <div class="mt-2 flex gap-2">
                            @if(($launchDetails['map_one'] ?? null))
                                <a href="{{ $launchDetails['map_one'] }}" target="_blank" rel="noopener" class="px-3 py-1 border border-primary text-primary rounded-full text-xs font-semibold hover:bg-primary/10 transition">@lang('Map 1')</a>
                            @endif
                            @if(($launchDetails['map_two'] ?? null))
                                <a href="{{ $launchDetails['map_two'] }}" target="_blank" rel="noopener" class="px-3 py-1 border border-primary text-primary rounded-full text-xs font-semibold hover:bg-primary/10 transition">@lang('Map 2')</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if(($launchDetails['phone'] ?? null))
                        <a href="tel:{{ $launchDetails['phone'] }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition">@lang('Call Now')</a>
                    @endif
                </div>
            </div>
        </div>
    </x-modal>
</div>
