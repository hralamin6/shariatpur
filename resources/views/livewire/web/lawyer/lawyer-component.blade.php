<div>
    <div class="mx-auto">
        <x-sponsor wire:ignore  title="lawyer"/>

        <!-- Search + Filter Bar -->
        <div class="flex gap-4 justify-between mb-4 bg-gray-100 dark:bg-gray-900 z-20 py-4">
            <div class="relative max-w-2xl mx-auto">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="text" wire:model.live.debounce.600ms="search" placeholder="আইনজীবী খুঁজুন..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
            </div>
            <!-- Upazila Filter -->
            <div class="max-w-2xl mx-auto">
                <select wire:model.live="filter_upazila_id" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                    <option value="">All Upazilas</option>
                    @foreach($upazilas as $upa)
                        <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Lawyer List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($lawyers as $law)
                <div wire:key="lawyer-{{ $law->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex flex-col group relative">
                    @php $canManage = auth()->check() && ($law->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp

                    <div class="absolute top-2 right-2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        @if($canManage)
                            <button type="button" class="p-1.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow" title="Edit" wire:click="selectLawyerForEdit({{ $law->id }})">
                                <i class='bx bxs-edit text-base'></i>
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-600 text-white hover:bg-red-700 shadow" title="Delete" wire:click="confirmDelete({{ $law->id }})">
                                <i class='bx bxs-trash text-base'></i>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-semibold overflow-hidden">
                            @php $img = method_exists($law,'getFirstMediaUrl') ? $law->getFirstMediaUrl('lawyer','avatar') : ''; @endphp
                            @if(!empty($img))
                                <img src="{{ $img }}" onerror="{{getErrorImage()}}" alt="Lawyer" class="h-12 w-12 rounded-full object-cover" />
                            @else
                                <i class='bx bxs-user-badge text-xl'></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $law->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $law->designation ?: '—' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $law->thana ?: '' }} {{ $law->upazila?->name ? '• '.$law->upazila?->name : '' }}</p>
                        </div>
                    </div>

                    <div class="flex-grow">
                        @if($law->address)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <i class='bx bx-map mr-1'></i>
                                {{ $law->address }}
                            </p>
                        @endif
                        @if($law->details)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $law->details }}</p>
                        @endif
                    </div>

                    <div class="mt-4 grid grid-cols-3 gap-3">
                        @if($law->map)
                            <a href="{{ $law->map }}" target="_blank" rel="noopener" class="text-center px-4 py-2 border border-indigo-500 text-indigo-600 rounded-lg text-sm font-semibold hover:bg-indigo-500/10 transition">Map</a>
                        @else
                            <button type="button" disabled class="text-center px-4 py-2 border border-gray-300 text-gray-400 rounded-lg text-sm font-semibold cursor-not-allowed">Map</button>
                        @endif
                        @if($law->phone)
                            <a href="tel:{{ $law->phone }}" class="text-center px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm font-semibold hover:bg-indigo-600 transition">Call</a>
                        @else
                            <button type="button" disabled class="text-center px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-semibold cursor-not-allowed">Call</button>
                        @endif
                        <button type="button" class="text-center px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition" wire:click="showDetails({{ $law->id }})">
                            @lang('Details')
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400">No lawyer entries found</span>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Floating Action Button -->
    @auth
        <button wire:click="openLawyerForm" class="fixed bottom-20 right-6 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Add Lawyer">
            <i class='bx bx-plus text-3xl'></i>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Login to add lawyer">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <!-- Create/Update Lawyer Modal -->
    <x-modal name="lawyer-form" :show="false" maxWidth="xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Lawyer' : 'Add New Lawyer' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateLawyer' : 'createLawyer' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upazila</label>
                    <select wire:model.defer="upazila_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
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
                    <input placeholder="image link" id="image_url" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" />
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lawyer Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation</label>
                    <input type="text" wire:model.defer="designation" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Advocate / Barrister">
                    @error('designation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber/Court</label>
                    <input type="text" wire:model.defer="thana" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Chamber / Court name">
                    @error('thana')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., 0123456789">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alt Phone</label>
                    <input type="text" wire:model.defer="alt_phone" class="mt-1 w-full rounded-md border-gray-300 dark;border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional">
                    @error('alt_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <textarea wire:model.defer="address" rows="2" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Office address"></textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Map URL</label>
                    <input type="url" wire:model.defer="map" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://maps.google.com/...">
                    @error('map')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Details</label>
                    <textarea wire:model.defer="details" rows="3" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Short description..."></textarea>
                    @error('details')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'lawyer-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600 shadow">{{ $selectedId ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-lawyer" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Lawyer</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this entry?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-lawyer')">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow" wire:click="deleteSelectedLawyer">Delete</button>
            </div>
        </div>
    </x-modal>

    <!-- Lawyer Details Modal -->
    <x-modal name="lawyer-details" :show="false" maxWidth="xl" focusable>
        <div class="p-0">
            <div class="relative bg-indigo-50 dark:bg-gray-800 p-6 sm:p-8">
                <div class="flex items-center gap-5">
                    <div class="relative h-20 w-28 sm:h-28 sm:w-40 rounded-lg overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg flex-shrink-0">
                        @php $photo = $lawyerDetails['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" onerror="{{getErrorImage()}}" alt="Lawyer photo" class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full bg-indigo-100 flex items-center justify-center">
                                <i class='bx bxs-user-badge text-4xl text-indigo-600'></i>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $lawyerDetails['name'] ?? '' }}</h2>
                        @if(($lawyerDetails['designation'] ?? null))
                            <p class="text-sm text-indigo-600 font-semibold">{{ $lawyerDetails['designation'] }}</p>
                        @endif
                        @if(($lawyerDetails['thana'] ?? null) || ($lawyerDetails['upazila'] ?? null))
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">{{ $lawyerDetails['thana'] ?? '' }} {{ ($lawyerDetails['thana'] ?? null) && ($lawyerDetails['upazila'] ?? null) ? '•' : '' }} {{ $lawyerDetails['upazila'] ?? '' }}</p>
                        @endif
                        @if(($lawyerDetails['address'] ?? null))
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $lawyerDetails['address'] }}</p>
                        @endif
                        @if(($lawyerDetails['created_by'] ?? null))
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">@lang('Added by'): {{ $lawyerDetails['created_by'] }}</p>
                        @endif
                    </div>
                    <button type="button" class="absolute top-4 right-4 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" @click="$dispatch('close-modal', 'lawyer-details')">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white dark:bg-gray-900">
                @if(($lawyerDetails['details'] ?? null))
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">@lang('Details')</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $lawyerDetails['details'] }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bxs-phone-call text-indigo-600'></i>
                            <span class="font-medium">@lang('Phone')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $lawyerDetails['phone'] ?? '' }}</p>
                        @if(($lawyerDetails['alt_phone'] ?? null))
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Alt: {{ $lawyerDetails['alt_phone'] }}</p>
                        @endif
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bxs-map text-indigo-600'></i>
                            <span class="font-medium">@lang('Map')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300 truncate">{{ $lawyerDetails['map'] ?? '' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if(($lawyerDetails['map'] ?? null))
                        <a href="{{ $lawyerDetails['map'] }}" target="_blank" rel="noopener" class="px-4 py-2 border border-indigo-500 text-indigo-600 rounded-lg text-sm font-semibold hover:bg-indigo-500/10 transition">@lang('Open Google Map')</a>
                    @endif
                    @if(($lawyerDetails['phone'] ?? null))
                        <a href="tel:{{ $lawyerDetails['phone'] }}" class="px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm font-semibold hover:bg-indigo-600 transition">@lang('Call Now')</a>
                    @endif
                </div>
            </div>
        </div>
    </x-modal>
</div>

