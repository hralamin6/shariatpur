<div>
    <div class="mx-auto">
        <x-sponsor wire:ignore  title="blood-donor"/>

        <!-- Search Bar + Filters -->
        <div class="flex flex-wrap gap-4 justify-between mb-4 bg-gray-100 dark:bg-gray-900 z-20 py-4">
            <div class="relative max-w-2xl mx-auto">
                <i class='bx bx-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input type="text" wire:model.live.debounce.600ms="search" placeholder="রক্তদাতা খুঁজুন..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm">
            </div>
            <div class="flex gap-2">
                <!-- Upazila Filter -->
                <select wire:model.live="filter_upazila_id" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm">
                    <option value="">All Upazilas</option>
                    @foreach($upazilas as $upa)
                        <option value="{{ $upa->id }}">{{ $upa->name }}</option>
                    @endforeach
                </select>
                <!-- Blood Group Filter -->
                <select wire:model.live="filter_blood_group" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg pl-4 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-sm">
                    <option value="">All Blood Groups</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
        </div>
        <!-- Blood Donors List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($donors as $donor)
                <div wire:key="donor-{{ $donor->id }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 flex flex-col group relative">
                    @php $canManage = auth()->check() && ($donor->id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'); @endphp

                    <div class="absolute top-2 right-2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition">
                        @if($canManage)
                            <button type="button" class="p-1.5 rounded-full bg-blue-600 text-white hover:bg-blue-700 shadow" title="Edit" wire:click="selectDonorForEdit({{ $donor->id }})">
                                <i class='bx bxs-edit text-base'></i>
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-600 text-white hover:bg-red-700 shadow" title="Deactivate" wire:click="confirmDelete({{ $donor->id }})">
                                <i class='bx bxs-trash text-base'></i>
                            </button>
                        @endif
                    </div>

                    <!-- Donor Status Badge -->
                    <div class="absolute top-2 left-2">
                        @if($donor->donor_status === 'available')
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Available</span>
                        @elseif($donor->donor_status === 'unavailable')
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Unavailable</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 mb-4 mt-6">
                        <div class="h-16 w-16 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-red-700 dark:text-red-300 font-bold text-xl overflow-hidden">
                            @php $profileImage = method_exists($donor,'getFirstMediaUrl') ? $donor->getFirstMediaUrl('profile', 'avatar') : ''; @endphp
                            @if(!empty($profileImage))
                                <img src="{{ getUserProfileImage($donor) }}" onerror="{{getErrorImage()}}" alt="Donor" class="h-16 w-16 rounded-full object-cover" />
                            @else
                                {{ $donor->blood_group ?? '?' }}
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <p class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $donor->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $donor->upazila?->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full font-semibold">
                                    {{ $donor->blood_group }}
                                </span>
                                @if($donor->canDonate())
                                    <span class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                        <i class='bx bx-check-circle mr-1'></i>
                                        Can Donate
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">
                                        <i class='bx bx-clock mr-1'></i>
                                        {{ $donor->getDaysUntilNextDonation() }} days left
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex-grow">
                        @if($donor->address)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 truncate">
                                <i class='bx bx-map mr-1'></i>
                                {{ $donor->address }}
                            </p>
                        @endif
                        @if($donor->total_donations > 0)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <i class='bx bx-heart mr-1'></i>
                                {{ $donor->total_donations }} donations
                            </p>
                        @endif
                        @if($donor->last_donate_date)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <i class='bx bx-calendar mr-1'></i>
                                Last donated: {{ $donor->last_donate_date->format('d M Y') }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-4 flex items-center gap-3">
                        @if($donor->phone)
                            <a href="tel:{{ $donor->phone }}" class="flex-1 text-center px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                                <i class='bx bx-phone mr-1'></i>
                                Call
                            </a>
                        @else
                            <button type="button" disabled class="flex-1 text-center px-4 py-2 bg-gray-200 text-gray-500 rounded-lg text-sm font-semibold cursor-not-allowed">
                                No Phone
                            </button>
                        @endif
                        <button type="button" class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition" wire:click="showDetails({{ $donor->id }})">
                            Details
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400">No blood donors found</span>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $donors->links() }}
        </div>
    </div>

    <!-- Floating Action Button -->
    @auth
        <button wire:click="openCreateModal" class="fixed bottom-20 right-6 h-14 w-14 bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition z-30" aria-label="Register as Blood Donor">
            <i class='bx bx-plus text-3xl'></i>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition z-30" aria-label="Login to register as donor">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <!-- Create/Update Donor Modal -->
    <x-modal name="donor-form" :show="false" maxWidth="xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Blood Donor' : 'Register Blood Donor' }}</h2>
            <form wire:submit.prevent="saveDonor" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Photo</label>
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
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="Full name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="e.g., 01XXXXXXXXX">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" wire:model.defer="email" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="name@mail.com">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Blood Group</label>
                    <select wire:model.defer="blood_group" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    @error('blood_group')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <textarea wire:model.defer="address" rows="2" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="Street, area, etc."></textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Donate Date</label>
                    <input type="date" wire:model.defer="last_donate_date" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500">
                    @error('last_donate_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Donations</label>
                    <input type="number" min="0" wire:model.defer="total_donations" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500">
                    @error('total_donations')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.defer="donor_status" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                    @error('donor_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Details</label>
                    <textarea wire:model.defer="donor_details" rows="3" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-red-500 focus:ring-red-500" placeholder="Short description..."></textarea>
                    @error('donor_details')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 mt-2 flex items-center justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'donor-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow">{{ $selectedId ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-donor" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Confirm Deactivation</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to deactivate this blood donor profile? This action will remove them from the active donors list.</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-donor')">Cancel</button>
                <button type="button" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow" wire:click="deleteDonor">Deactivate</button>
            </div>
        </div>
    </x-modal>

    <!-- Donor Details Modal -->
    <x-modal name="donor-details" :show="false" maxWidth="xl" focusable>
        <div class="p-0">
            <div class="relative bg-red-50 dark:bg-gray-800 p-6 sm:p-8">
                <div class="flex items-center gap-5">
                    <div class="relative h-20 w-20 sm:h-28 sm:w-28 rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg flex-shrink-0">
                        @php $photo = $donorDetails['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" onerror="{{getErrorImage()}}" alt="Donor photo" class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full bg-red-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-red-600">{{ $donorDetails['blood_group'] ?? '?' }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $donorDetails['name'] ?? '' }}</h2>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            @if(($donorDetails['blood_group'] ?? null))
                                <span class="inline-flex items-center px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full font-semibold">{{ $donorDetails['blood_group'] }}</span>
                            @endif
                            @if(($donorDetails['donor_status'] ?? null) === 'available')
                                <span class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Available</span>
                            @elseif(($donorDetails['donor_status'] ?? null) === 'unavailable')
                                <span class="inline-flex items-center px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Unavailable</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                            @endif
                        </div>
                        <p class="text-sm text-red-600 font-semibold">{{ $donorDetails['upazila'] ?? '' }}</p>
                        @if(($donorDetails['address'] ?? null))
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">{{ $donorDetails['address'] }}</p>
                        @endif
                    </div>
                    <button type="button" class="absolute top-4 right-4 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" @click="$dispatch('close-modal', 'donor-details')">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white dark:bg-gray-900">
                @if(($donorDetails['donor_details'] ?? null))
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-3">@lang('Details')</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $donorDetails['donor_details'] }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bx-heart text-red-500'></i>
                            <span class="font-medium">@lang('Total Donations')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $donorDetails['total_donations'] ?? 0 }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bx-calendar text-red-500'></i>
                            <span class="font-medium">@lang('Last Donation')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $donorDetails['last_donate_date'] ?? 'Never' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if(($donorDetails['phone'] ?? null))
                        <a href="tel:{{ $donorDetails['phone'] }}" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">@lang('Call Now')</a>
                    @endif
                    @if(($donorDetails['email'] ?? null))
                        <a href="mailto:{{ $donorDetails['email'] }}" class="px-4 py-2 border border-red-500 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-500/10 transition">@lang('Email')</a>
                    @endif
                </div>
            </div>
        </div>
    </x-modal>
</div>
