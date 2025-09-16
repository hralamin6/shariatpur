<div>
    <div class="mx-auto">
        <x-sponsor wire:ignore  title="institution"/>

        <!-- Search + Filter Bar -->
        <div class="flex gap-4 justify-between mb-4 bg-gray-100 dark:bg-gray-900 z-20 py-4">
            <div class="relative max-w-2xl mx-auto">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="text" wire:model.live.debounce.600ms="search" placeholder="Search institutions..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
            </div>
            <div class="max-w-xs mx-auto">
                <select wire:model.live="filter_type_id" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                    <option value="">All Types</option>
                    @foreach($types as $typeOption)
                        <option value="{{ $typeOption->id }}">{{ $typeOption->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="max-w-xs mx-auto">
                <select wire:model.live="filter_upazila_id" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                    <option value="">All Upazilas</option>
                    @foreach($upazilas as $upa)
                        <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Institutions List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($institutions as $ins)
                <div wire:key="institution-{{ $ins->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex flex-col group relative">
                    @php $canManage = auth()->check() && ($ins->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp

                    <div class="absolute top-2 right-2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        @if($canManage)
                            <button type="button" class="p-1.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow" title="Edit" wire:click="selectInstitutionForEdit({{ $ins->id }})">
                                <i class='bx bxs-edit text-base'></i>
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-600 text-white hover:bg-red-700 shadow" title="Delete" wire:click="confirmDelete({{ $ins->id }})">
                                <i class='bx bxs-trash text-base'></i>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-14 w-14 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-semibold overflow-hidden">
                            @php $img = method_exists($ins,'getFirstMediaUrl') ? $ins->getFirstMediaUrl('institution','avatar') : ''; @endphp
                            @if(!empty($img))
                                <img src="{{ $img }}" onerror="{{getErrorImage()}}" alt="Institution" class="h-14 w-14 object-cover" />
                            @else
                                <i class='bx bx-building-house text-2xl'></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $ins->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $ins->type?->name ?? '—' }} @if($ins->established_at)• {{ $ins->established_at->format('Y') }} @endif</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $ins->upazila?->name }}</p>
                        </div>
                    </div>

                    <div class="flex-grow">
                        @if($ins->address)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 truncate">{{ $ins->address }}</p>
                        @endif
                        @if($ins->details)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $ins->details }}</p>
                        @endif
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        @if($ins->map)
                            <a href="{{ $ins->map }}" target="_blank" rel="noopener" class="flex-1 text-center px-4 py-2 border border-indigo-500 text-indigo-600 rounded-lg text-sm font-semibold hover:bg-indigo-500/10 transition">Map</a>
                        @else
                            <button type="button" disabled class="flex-1 text-center px-4 py-2 border border-gray-300 text-gray-400 rounded-lg text-sm font-semibold cursor-not-allowed">Map</button>
                        @endif
                        @if($ins->phone)
                            <a href="tel:{{ $ins->phone }}" class="flex-1 text-center px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm font-semibold hover:bg-indigo-600 transition">Call</a>
                        @else
                            <button type="button" disabled class="flex-1 text-center px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-semibold cursor-not-allowed">Call</button>
                        @endif
                        <button type="button" class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition" wire:click="showDetails({{ $ins->id }})">Details</button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400">No institutions found</span>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            <!-- no pagination for now -->
        </div>
    </div>

    <!-- Floating Action Button -->
    @auth
        <button wire:click="openInstitutionForm" class="fixed bottom-20 right-6 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Add Institution">
            <i class='bx bx-plus text-3xl'></i>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Login to add institution">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <!-- Institution Form Modal -->
    <x-modal name="institution-form" :show="false" maxWidth="xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Institution' : 'Add New Institution' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateInstitution' : 'createInstitution' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" onerror="{{getErrorImage()}}" class="h-20 w-20 rounded-md object-cover border" />
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Institution name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                    <select wire:model.defer="institution_type_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Type</option>
                        @foreach($types as $typeOption)
                            <option value="{{ $typeOption->id }}">{{ $typeOption->name }}</option>
                        @endforeach
                    </select>
                    @error('institution_type_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Established</label>
                    <input type="date" wire:model.defer="established_at" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('established_at')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" wire:model.defer="email" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                    <input type="url" wire:model.defer="website" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://...">
                    @error('website')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <textarea wire:model.defer="address" rows="2" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Map URL</label>
                    <input type="url" wire:model.defer="map" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500" placeholder="https://maps.google.com/...">
                    @error('map')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Details</label>
                    <textarea wire:model.defer="details" rows="3" class="mt-1 w-full rounded-md border-gray-300 dark;border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('details')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'institution-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600 shadow">{{ $selectedId ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-institution" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Institution</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this institution?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-institution')">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow" wire:click="deleteInstitution">Delete</button>
            </div>
        </div>
    </x-modal>

    <!-- Institution Details Modal -->
    <x-modal name="institution-details" :show="false" maxWidth="xl" focusable>
        {{--
    Redesigned Institution Details Modal.
    - Uses dark classes as per user preference.
    - Modern, user-friendly layout with improved visual hierarchy.
--}}
        <div class="relative flex flex-col bg-white dark:bg-gray-900">
            <div wire:loading class="absolute inset-0 z-20 flex items-center justify-center bg-white/70 dark:bg-gray-900/70">
                <div class="h-10 w-10 animate-spin rounded-full border-4 border-indigo-500 border-t-transparent"></div>
                <span class="sr-only">Loading</span>
            </div>

            <div class="p-6 sm:p-8 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700/50">
                <div class="relative text-center">
                    <button
                        type="button"
                        class="absolute -top-2 -right-2 sm:-top-4 sm:-right-4 z-10 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors"
                        @click="$dispatch('close-modal', 'institution-details')"
                        aria-label="Close"
                    >
                        <i class='bx bx-x text-2xl'></i>
                    </button>

                    <div class="relative h-24 w-24 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg mx-auto mb-4">
                        @php $photo = $institutionDetails['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" onerror="{{ getErrorImage() }}" alt="Institution photo" class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full bg-indigo-100 dark:bg-gray-700 flex items-center justify-center">
                                <i class='bx bx-building-house text-4xl text-indigo-600 dark:text-indigo-300'></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-2 mb-2">
                        @if(($institutionDetails['established_at'] ?? null))
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-2.5 py-1 text-xs font-medium">
                        <i class="bx bx-calendar text-base"></i>
                        <span>@lang('Established'): {{ $institutionDetails['established_at'] }}</span>
                    </span>
                        @endif
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-3">
                        {{ $institutionDetails['name'] ?? '' }}
                    </h2>

                    @if(($institutionDetails['address'] ?? null))
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 max-w-lg mx-auto">
                            {{ $institutionDetails['address'] }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8 max-h-[60vh] overflow-y-auto">
                @if(($institutionDetails['details'] ?? null))
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">@lang('About the Institution')</h3>
                        <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            {{-- Using the 'prose' class from Tailwind Typography for better text rendering --}}
                            <p>{{ $institutionDetails['details'] }}</p>
                        </div>
                    </div>
                @endif

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 pb-2 border-b border-gray-200 dark:border-gray-700">@lang('Information')</h3>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-gray-800 flex items-center justify-center">
                            <i class='bx bx-phone-call text-xl text-indigo-600 dark:text-indigo-300'></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">@lang('Contact')</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                @if(($institutionDetails['phone'] ?? null))
                                    <a href="tel:{{ $institutionDetails['phone'] }}" class="hover:underline text-indigo-500">{{ $institutionDetails['phone'] }}</a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if(($institutionDetails['email'] ?? null))
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-gray-800 flex items-center justify-center">
                                <i class='bx bx-envelope text-xl text-indigo-600 dark:text-indigo-300'></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">@lang('Email')</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <a href="mailto:{{ $institutionDetails['email'] }}" class="hover:underline text-indigo-500">{{ $institutionDetails['email'] }}</a>
                                </p>
                            </div>
                        </div>
                    @endif

                    @if(($institutionDetails['website'] ?? null))
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-gray-800 flex items-center justify-center">
                                <i class='bx bx-globe text-xl text-indigo-600 dark:text-indigo-300'></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">@lang('Website')</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <a href="{{ $institutionDetails['website'] }}" class="hover:underline text-indigo-500" target="_blank" rel="noopener noreferrer">@lang('Visit Website')</a>
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-gray-800 flex items-center justify-center">
                            <i class='bx bx-map-pin text-xl text-indigo-600 dark:text-indigo-300'></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 dark:text-gray-200">@lang('Map Location')</p>
                            @if(($institutionDetails['map'] ?? null))
                                <div class="flex items-center gap-2 mt-1">
                                    <a href="{{ $institutionDetails['map'] }}" target="_blank" rel="noopener noreferrer" class="text-sm text-indigo-500 hover:underline truncate">
                                        @lang('View on Google Maps')
                                    </a>
                                    <div x-data="{ copied: false }">
                                        <button
                                            type="button"
                                            class="p-1.5 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700"
                                            @click="navigator.clipboard.writeText(@js($institutionDetails['map'] ?? '')); copied = true; setTimeout(() => copied = false, 2000)"
                                            :title="copied ? '@lang('Copied!')' : '@lang('Copy link')'"
                                        >
                                            <i class="bx" :class="copied ? 'bx-check text-green-500' : 'bx-copy'"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-400 dark:text-gray-500">—</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700/50">
                <div class="flex flex-wrap items-center justify-center gap-3">
                    @if(($institutionDetails['phone'] ?? null))
                        <a href="tel:{{ $institutionDetails['phone'] }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                            <i class="bx bx-phone"></i>
                            <span>@lang('Call Now')</span>
                        </a>
                    @endif

                    @if(($institutionDetails['map'] ?? null))
                        <a href="{{ $institutionDetails['map'] }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-indigo-500 text-indigo-600 dark:text-indigo-300 rounded-lg text-sm font-semibold hover:bg-indigo-500/10 transition">
                            <i class="bx bx-map-alt"></i>
                            <span>@lang('Open Map')</span>
                        </a>
                    @endif

                    @if(($institutionDetails['address'] ?? null))
                        <div x-data="{ copied: false }">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                @click="navigator.clipboard.writeText(@js($institutionDetails['address'] ?? '')); copied = true; setTimeout(() => copied = false, 2000)"
                            >
                                <i class="bx" :class="copied ? 'bx-check-double text-green-500' : 'bx-copy'"></i>
                                <span x-text="copied ? '@lang('Address Copied!')' : '@lang('Copy Address')'"></span>
                            </button>
                        </div>
                    @endif
                </div>
                @if(($institutionDetails['created_by'] ?? null))
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">@lang('Added by'): {{ $institutionDetails['created_by'] }}</p>
                @endif
            </div>
        </div>
    </x-modal>
</div>
