<div>
    <div class="mx-auto">
        <x-sponsor wire:ignore title="land" />
        <div class="flex gap-4 justify-between mb-4 bg-gray-100 dark:bg-gray-900 z-20 py-4">
            <div class="relative max-w-2xl mx-auto">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="text" wire:model.live.debounce.600ms="search" placeholder="প্লট/জমি খুঁজুন..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm">
            </div>
            <div class="max-w-xs mx-auto">
                <select wire:model.live="filter_upazila_id" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm">
                    <option value="">All Upazilas</option>
                    @foreach($upazilas as $upa)
                        <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Land List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($lands as $land)
                <div wire:key="land-{{ $land->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex flex-col group relative">
                    @php $canManage = auth()->check() && ($land->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp

                    <div class="absolute top-2 right-2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        @if($canManage)
                            <button type="button" class="p-1.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow" title="Edit" wire:click="selectLandForEdit({{ $land->id }})">
                                <i class='bx bxs-edit text-base'></i>
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-600 text-white hover:bg-red-700 shadow" title="Delete" wire:click="confirmDelete({{ $land->id }})">
                                <i class='bx bxs-trash text-base'></i>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-semibold">
                            <img src="{{ getUserProfileImage($land->user) }}" alt="User" onerror="{{ getErrorProfile($land->user) }}" class="h-10 w-10 rounded-full" />
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $land->user?->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ optional($land->created_at)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex-grow flex flex-col sm:flex-row gap-4">
                        <div class="sm:w-32 md:w-40 flex-shrink-0 h-32">
                            @php $img = method_exists($land,'getFirstMediaUrl') ? $land->getFirstMediaUrl('land','avatar') : ''; @endphp
                            @if(!empty($img))
                                <img src="{{ $img }}" onerror="{{ getErrorImage() }}" alt="Land image" class="w-full h-32 sm:h-full object-cover rounded-md">
                            @else
                                <img src="https://placehold.co/600x400/10B981/FFFFFF?text=Land" alt="Land placeholder" class="w-full h-32 sm:h-full object-cover rounded-md">
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col">
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $land->title }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $land->upazila?->name }}</p>
                                <div class="mt-1 grid grid-cols-2 gap-1 text-xs text-gray-600 dark:text-gray-400">
                                    @if($land->area)<div><span class="font-semibold">Area:</span> {{ $land->area }}</div>@endif
                                    @if(!is_null($land->price))<div><span class="font-semibold">Price:</span> {{ number_format($land->price) }} ৳</div>@endif
                                </div>
                                @if($land->address)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $land->address }}</p>
                                @endif
                                @if($land->details)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $land->details }}</p>
                                @endif
                            </div>
                            <div class="mt-4 flex items-center gap-3">
                                @if($land->map)
                                    <a href="{{ $land->map }}" target="_blank" rel="noopener" class="w-full text-center px-4 py-2 border border-emerald-500 text-emerald-600 rounded-lg text-sm font-semibold hover:bg-emerald-500/10 transition">Google Map</a>
                                @else
                                    <button type="button" disabled class="w-full text-center px-4 py-2 border border-gray-300 text-gray-400 rounded-lg text-sm font-semibold cursor-not-allowed">Google Map</button>
                                @endif
                                @if($land->phone)
                                    <a href="tel:{{ $land->phone }}" class="w-full text-center px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-semibold hover:bg-emerald-600 transition">Call</a>
                                @else
                                    <button type="button" disabled class="w-full text-center px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-semibold cursor-not-allowed">Call</button>
                                @endif
                                <button type="button" class="w-full text-center px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition" wire:click="showDetails({{ $land->id }})">
                                    @lang('Details')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400">No lands found</span>
                </div>
            @endforelse
        </div>
    </div>

    <!-- FAB -->
    @auth
        <button wire:click="openLandForm" class="fixed bottom-20 right-6 h-14 w-14 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-emerald-600 transition z-30" aria-label="Add Land">
            <i class='bx bx-plus text-3xl'></i>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-emerald-600 transition z-30" aria-label="Login to add land">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <!-- Create/Update Modal -->
    <x-modal name="land-form" :show="false" maxWidth="xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Land' : 'Add New Land' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateLand' : 'createLand' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upazila</label>
                    <select wire:model.defer="upazila_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500">
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
                    <input placeholder="image link" id="image_url" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" />
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-2" wire:loading.remove wire:target="photo">
                        @if($image_url)
                            <img src="{{ $image_url }}" alt="Preview" onerror="{{ getErrorImage() }}" class="h-20 w-20 rounded-md object-cover border" />
                        @elseif($photo)
                            <img src="{{ $photo->temporaryUrl() }}" onerror="{{ getErrorImage() }}" alt="Preview" class="h-20 w-20 rounded-md object-cover border" />
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" wire:model.defer="title" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Plot/Land title">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Area</label>
                    <input type="text" wire:model.defer="area" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g., 5 Katha / 3000 sq ft">
                    @error('area')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (৳)</label>
                    <input type="number" min="0" wire:model.defer="price" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g., 1000000">
                    @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <textarea wire:model.defer="address" rows="2" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Street, area, etc."></textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g., 01XXXXXXXXX">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Map URL</label>
                    <input type="url" wire:model.defer="map" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" placeholder="https://maps.google.com/...">
                    @error('map')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Details</label>
                    <textarea wire:model.defer="details" rows="3" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Short description..."></textarea>
                    @error('details')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'land-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-emerald-500 text-white hover:bg-emerald-600 shadow">{{ $selectedId ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Modal -->
    <x-modal name="delete-land" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Land</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this entry?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-land')">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow" wire:click="deleteSelectedLand">Delete</button>
            </div>
        </div>
    </x-modal>

    <!-- Details Modal -->
    <x-modal name="land-details" :show="false" maxWidth="xl" focusable>
        <div class="p-0">
            <div class="relative bg-emerald-50 dark:bg-gray-800 p-6 sm:p-8">
                <div class="flex items-center gap-5">
                    <div class="relative h-20 w-28 sm:h-28 sm:w-40 rounded-lg overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg flex-shrink-0">
                        @php $photo = $landDetails['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" onerror="{{ getErrorImage() }}" alt="Land photo" class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full bg-emerald-100 flex items-center justify-center">
                                <i class='bx bx-map-alt text-4xl text-emerald-600'></i>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $landDetails['title'] ?? '' }}</h2>
                        <p class="text-sm text-emerald-600 font-semibold">{{ $landDetails['upazila'] ?? '' }}</p>
                        @if(($landDetails['address'] ?? null))
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">{{ $landDetails['address'] }}</p>
                        @endif
                        @if(($landDetails['created_by'] ?? null))
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">@lang('Added by'): {{ $landDetails['created_by'] }}</p>
                        @endif
                    </div>
                    <button type="button" class="absolute top-4 right-4 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" @click="$dispatch('close-modal', 'land-details')">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white dark:bg-gray-900">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bx-layer text-emerald-600'></i>
                            <span class="font-medium">@lang('Area')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $landDetails['area'] ?? '' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bx-money text-emerald-600'></i>
                            <span class="font-medium">@lang('Price')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ isset($landDetails['price']) ? number_format($landDetails['price']).' ৳' : '' }}</p>
                    </div>
                </div>

                @if(($landDetails['details'] ?? null))
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">@lang('Details')</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $landDetails['details'] }}</p>
                    </div>
                @endif

                <div class="flex items-center gap-3">
                    @if(($landDetails['map'] ?? null))
                        <a href="{{ $landDetails['map'] }}" target="_blank" rel="noopener" class="px-4 py-2 border border-emerald-500 text-emerald-600 rounded-lg text-sm font-semibold hover:bg-emerald-500/10 transition">@lang('Open Google Map')</a>
                    @endif
                    @if(($landDetails['phone'] ?? null))
                        <a href="tel:{{ $landDetails['phone'] }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-semibold hover:bg-emerald-600 transition">@lang('Call Now')</a>
                    @endif
                </div>
            </div>
        </div>
    </x-modal>
</div>

