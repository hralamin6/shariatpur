<div class="max-w-7xl mx-auto">
    <x-sponsor wire:ignore  title="doctor"/>

    <!-- Header -->
    <div class="mx-auto mb-6 px-4 sm:px-6 lg:px-8" role="search">
        <div class="flex justify-center">
            <div class="relative w-full max-w-xl">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input
                    type="search"
                    wire:model.live.debounce.1000="search"
                    placeholder="Search by name or chamber"
                    aria-label="Search doctors"
                    autocomplete="off"
                    class="block w-full rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/50 px-4 py-2 pl-10 shadow-sm transition"
                />
                @if(!empty($search))
                    <button
                        type="button"
                        wire:click="$set('search','')"
                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        aria-label="Clear search"
                    >
                        <i class='bx bx-x text-xl'></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @php
        $badge = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200';
        $chip = 'px-2 py-0.5 rounded-md text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200';
    @endphp

    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($doctors as $doctor)
            <div wire:key="doctor-{{ $doctor->id }}" class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:border-primary">
                <div class="p-5 flex-grow">
                    <div class="flex items-start gap-4">
                        <!-- Doctor's Avatar -->
                        <div class="relative flex-shrink-0">
                            <img src="{{$doctor->getFirstMediaUrl('doctor', 'avatar')}}" onerror="{{getErrorImage()}}" class="h-16 w-16 rounded-full object-cover border-2 border-primary/20 shadow-md" />
                        </div>

                        <!-- Doctor's Info -->
                        <div class="min-w-0 flex-1">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $doctor->name }}</h3>
                                <p class="text-sm text-primary font-medium">{{ $doctor->category?->name }}</p>
                            </div>

                            @if($doctor->qualification)
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $doctor->qualification }}</p>
                            @endif
                            @if($doctor->current_position)
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $doctor->current_position }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Footer: Chambers & Actions -->
                <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-2">
                    <div class="flex items-center justify-between text-center">
                        <!-- Chamber Call Buttons -->
                        <!-- Action Buttons -->
                            @if(auth()->check() && ($doctor->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'))
                                <button type="button" class="px-2 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 transition-colors" title="Edit" wire:click="selectDoctorForEdit({{ $doctor->id }})">
                                    <i class='bx bxs-edit text-lg'></i>
                                    <x-loader target="selectDoctorForEdit({{ $doctor->id }})" />
                                </button>
                                <button type="button" class="px-2 rounded-full bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 transition-colors" title="Delete" wire:click="confirmDelete({{ $doctor->id }})">
                                    <i class='bx bxs-trash text-lg'></i>
                                    <x-loader target="confirmDelete({{ $doctor->id }})" />
                                </button>
                            @endif
                            <button type="button" class="px-3 py-1 mx-auto rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors text-sm" title="Details" wire:click="showDetails({{ $doctor->id }})">
                                @lang('Show Details')
                                <x-loader target="showDetails({{ $doctor->id }})" />
                            </button>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <span class="text-gray-500 dark:text-gray-400">No doctors available</span>
            </div>
        @endforelse
    </div>

    <!-- Floating Add Doctor Button -->
    @auth
        <div x-data class="fixed bottom-20 right-6 z-40">
            <button type="button"
                    class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary text-white shadow-lg hover:shadow-xl hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition"
                    @click="$dispatch('open-modal', 'create-doctor')"
                    aria-label="Add Doctor">
                <i class="bx bx-plus text-3xl bx-tada"></i>
            </button>
        </div>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary text-white shadow-lg hover:shadow-xl hover:bg-primary/90 focus:outline-none focus:ring-4 focus:ring-primary/30 transition" aria-label="Login to add doctor">
            <i class="bx bx-log-in text-3xl"></i>
        </a>
    @endguest

    <!-- Create Doctor Modal -->
    <x-modal name="create-doctor" :show="false" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Doctor</h2>
            <form wire:submit.prevent="createDoctor" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                    <input placeholder="image link" errorName="image_url"  id="image_url" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary"/>
                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" />
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-2" wire:loading.remove wire:target="photo">
                        @if($image_url)
                            <img src="{{ $image_url }}" onerror="{{getErrorImage()}}" alt="Preview" class="h-20 w-20 rounded-full object-cover border" />
                        @elseif($photo)
                            <img src="{{ $photo->temporaryUrl() }}" onerror="{{getErrorImage()}}" alt="Preview" class="h-20 w-20 rounded-full object-cover border" />
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Doctor name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Qualification</label>
                    <input type="text" wire:model.defer="qualification" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="e.g., MBBS, FCPS">
                    @error('qualification')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Position</label>
                    <input type="text" wire:model.defer="current_position" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="e.g., Consultant at ...">
                    @error('current_position')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber One</label>
                    <input type="text" wire:model.defer="chamber_one" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Address">
                    @error('chamber_one')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber One Phone</label>
                    <input type="text" wire:model.defer="chamber_one_phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Phone">
                    @error('chamber_one_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text_sm font-medium text-gray-700 dark:text-gray-300">Chamber Two</label>
                    <input type="text" wire:model.defer="chamber_two" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Address">
                    @error('chamber_two')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber Two Phone</label>
                    <input type="text" wire:model.defer="chamber_two_phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Phone">
                    @error('chamber_two_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber Three</label>
                    <input type="text" wire:model.defer="chamber_three" class="mt-1 w_full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Address">
                    @error('chamber_three')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber Three Phone</label>
                    <input type="text" wire:model.defer="chamber_three_phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Phone">
                    @error('chamber_three_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'create-doctor')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow">Save</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Edit Doctor Modal -->
    <x-modal name="edit-doctor" :show="false" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Doctor</h2>
            <form wire:submit.prevent="updateDoctor" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select wire:model.defer="doctor_category_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                    <input placeholder="image link" errorName="image_url"  id="image_url" wire:model.live="image_url" type="url" class="mb-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary"/>

                    <input type="file" wire:model="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" />
                    @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @error('image_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-2" wire:loading.remove wire:target="photo">
                        @if($image_url)
                            <img src="{{ $image_url }}" onerror="{{getErrorImage()}}" alt="Preview" class="h-20 w-20 rounded-full object-cover border" />
                        @elseif($photo)
                            <img src="{{ $photo->temporaryUrl() }}" onerror="{{getErrorImage()}}" alt="Preview" class="h-20 w-20 rounded-full object-cover border" />
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Qualification</label>
                    <input type="text" wire:model.defer="qualification" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('qualification')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Position</label>
                    <input type="text" wire:model.defer="current_position" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('current_position')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber One</label>
                    <input type="text" wire:model.defer="chamber_one" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('chamber_one')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber One Phone</label>
                    <input type="text" wire:model.defer="chamber_one_phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('chamber_one_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber Two</label>
                    <input type="text" wire:model.defer="chamber_two" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('chamber_two')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber Two Phone</label>
                    <input type="text" wire:model.defer="chamber_two_phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('chamber_two_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber Three</label>
                    <input type="text" wire:model.defer="chamber_three" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('chamber_three')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chamber Three Phone</label>
                    <input type="text" wire:model.defer="chamber_three_phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('chamber_three_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'edit-doctor')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow">Update</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-doctor" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Doctor</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this doctor?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-doctor')">Cancel</button>
                <button type="button" wire:click="deleteSelectedDoctor" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow">Delete</button>
            </div>
        </div>
    </x-modal>

    <!-- Doctor Details Modal -->
    <x-modal name="doctor-details" :show="false" maxWidth="md" focusable>
        <div class="bg-gray-50 dark:bg-gray-900 border border-green-500">
            <!-- Header Section -->
            <div class="relative bg-primary/10 dark:bg-gray-800 p-6 sm:p-8">
                <div class="flex items-center gap-5">
                    <div class="relative h-20 w-20 sm:h-24 sm:w-24 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg flex-shrink-0">
                        @php $photo = $details['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" onerror="{{getErrorImage()}}"  class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full bg-primary/20 flex items-center justify-center">
                                <span class="text-3xl sm:text-4xl font-bold text-primary">{{ isset($details['name']) ? strtoupper(\Illuminate\Support\Str::substr($details['name'],0,1)) : '' }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $details['name'] ?? '' }}</h2>
                        <p class="text-base text-primary font-semibold">{{ $details['category'] ?? '' }}</p>
                        <div class="mt-2">
{{--                     <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ ($details['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">--}}
{{--                        <i class='bx bxs-circle mr-1.5 text-xs'></i>--}}
{{--                        {{ ucfirst($details['status'] ?? 'active') }}--}}
{{--                    </span>--}}
                        </div>
                    </div>
                </div>
                <button type="button" class="absolute top-4 right-4 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" @click="$dispatch('close-modal', 'doctor-details')">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Details Content -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Professional Information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Professional Information</h4>
                    <div class="space-y-3 text-sm border-l-2 border-primary/20 pl-4">
                        @if(!empty($details['qualification']))
                            <div class="flex items-start gap-3">
                                <i class='bx bxs-graduation text-primary text-lg mt-0.5'></i>
                                <div>
                                    <p class="font-medium text-gray-500 dark:text-gray-400">Qualification</p>
                                    <p class="text-gray-800 dark:text-gray-200">{{ $details['qualification'] }}</p>
                                </div>
                            </div>
                        @endif
                        @if(!empty($details['current_position']))
                            <div class="flex items-start gap-3">
                                <i class='bx bxs-briefcase text-primary text-lg mt-0.5'></i>
                                <div>
                                    <p class="font-medium text-gray-500 dark:text-gray-400">Current Position</p>
                                    <p class="text-gray-800 dark:text-gray-200">{{ $details['current_position'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Chamber Information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">Chamber Details</h4>
                    <div class="space-y-3">
                        @if(!empty($details['chamber_one']) || !empty($details['chamber_one_phone']))
                            <div class="p-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <p class="font-semibold text-gray-700 dark:text-gray-200">Chamber 1</p>
                                <p class="text-gray-600 dark:text-gray-300">{{ $details['chamber_one'] }}</p>
                                @if(!empty($details['chamber_one_phone']))
                                    <a href="tel:{{ $details['chamber_one_phone'] }}" class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary hover:bg-primary/20 text-xs font-bold transition-colors">
                                        <i class='bx bxs-phone-call'></i>
                                        <span>{{ $details['chamber_one_phone'] }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                        @if(!empty($details['chamber_two']) || !empty($details['chamber_two_phone']))
                            <div class="p-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <p class="font-semibold text-gray-700 dark:text-gray-200">Chamber 2</p>
                                <p class="text-gray-600 dark:text-gray-300">{{ $details['chamber_two'] }}</p>
                                @if(!empty($details['chamber_two_phone']))
                                    <a href="tel:{{ $details['chamber_two_phone'] }}" class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary hover:bg-primary/20 text-xs font-bold transition-colors">
                                        <i class='bx bxs-phone-call'></i>
                                        <span>{{ $details['chamber_two_phone'] }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                        @if(!empty($details['chamber_three']) || !empty($details['chamber_three_phone']))
                            <div class="p-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <p class="font-semibold text-gray-700 dark:text-gray-200">Chamber 3</p>
                                <p class="text-gray-600 dark:text-gray-300">{{ $details['chamber_three'] }}</p>
                                @if(!empty($details['chamber_three_phone']))
                                    <a href="tel:{{ $details['chamber_three_phone'] }}" class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary hover:bg-primary/20 text-xs font-bold transition-colors">
                                        <i class='bx bxs-phone-call'></i>
                                        <span>{{ $details['chamber_three_phone'] }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @if(!empty($details['created_by']))
                    <div class="text-center text-xs text-gray-500 dark:text-gray-400 pt-4">
                        Added by {{ $details['created_by'] }}
                    </div>
                @endif
            </div>
        </div>

    </x-modal>
</div>
