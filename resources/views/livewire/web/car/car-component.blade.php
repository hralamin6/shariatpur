<div class="max-w-7xl mx-auto">
    <x-sponsor wire:ignore  title="car"/>

    <!-- Top Bar: Search + Filters -->
    <div class="mx-auto mb-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:max-w-xl">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl'></i>
                <input
                    type="search"
                    wire:model.live.debounce.800ms="search"
                    placeholder="Search by car name, driver, phone, or address"
                    class="block w-full rounded-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/40 px-4 py-2 pl-10 shadow-sm transition"
                />
                @if(!empty($search))
                    <button type="button" wire:click="$set('search','')" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition" aria-label="Clear search">
                        <i class='bx bx-x text-xl'></i>
                    </button>
                @endif
            </div>
            <div class="w-full md:w-[28rem] grid grid-cols-1 md:grid-cols-2 gap-3">
                <select wire:model.live="filter_type_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
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

    <!-- Car Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($cars as $car)
            <div wire:key="car-{{ $car->id }}" class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition-all duration-300 hover:shadow-xl hover:border-primary">
                <div class="p-5 flex-grow">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 h-20 w-28 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 flex items-center justify-center overflow-hidden">
                            @php $img = method_exists($car,'getFirstMediaUrl') ? $car->getFirstMediaUrl('car','avatar') : ''; @endphp
                            @if($img)
                                <img src="{{ $img }}" alt="Car photo" onerror="{{getErrorImage()}}" class="h-full w-full object-cover" />
                            @else
                                <i class='bx bxs-car text-3xl'></i>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $car->name }}</h3>
                            <p class="text-xs text-primary font-semibold truncate">{{ $car->type?->name }}</p>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-600 dark:text-gray-300">
                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">{{ $car->ac ? 'AC' : 'Non-AC' }}</span>
                                @if($car->seat_number)
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">Seats: {{ (int)$car->seat_number }}</span>
                                @endif
                                @if($car->weight_capacity)
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">Capacity: {{ (int)$car->weight_capacity }} kg</span>
                                @endif
                            </div>
                            @if($car->rent_price)
                                <p class="mt-2 text-sm font-semibold text-emerald-600">৳ {{ number_format((int)$car->rent_price) }} / day</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-3 flex items-center justify-between gap-2">
                    <div class="flex gap-2">
                        @if($car->map)
                            <a class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600" target="_blank" rel="noopener" href="{{ $car->map }}">Map</a>
                        @endif
                        @if($car->phone)
                            <a class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 text-xs font-bold hover:bg-amber-200 dark:hover:bg-amber-800" href="tel:{{ $car->phone }}">Call</a>
                        @endif
                        <button type="button" class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600" wire:click="showDetails({{ $car->id }})">@lang('Details')</button>
                    </div>

                    @if(auth()->check() && ($car->user_id === auth()->id() || optional(auth()->user()->role)->slug === 'admin'))
                        <div class="flex items-center gap-1">
                            <button type="button" class="p-1.5 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 transition-colors" title="Edit" wire:click="selectCarForEdit({{ $car->id }})">
                                <i class='bx bxs-edit text-lg'></i>
                            </button>
                            <button type="button" class="p-1.5 rounded-full bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 transition-colors" title="Delete" wire:click="confirmDelete({{ $car->id }})">
                                <i class='bx bxs-trash text-lg'></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full flex items-center justify-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                <span class="text-gray-500 dark:text-gray-400">No cars found</span>
            </div>
        @endforelse
    </div>

    @auth
        <button wire:click="openCarForm" class="fixed bottom-20 right-6 h-14 w-14 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary/90 transition z-30" aria-label="Add Car">
            <i class='bx bx-plus text-3xl'></i>
        </button>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="fixed bottom-20 right-6 h-14 w-14 bg-primary text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary/90 transition z-30" aria-label="Login to add car">
            <i class='bx bx-log-in text-3xl'></i>
        </a>
    @endguest

    <!-- Unified Create/Update Modal -->
    <x-modal name="car-form" :show="false" maxWidth="2xl" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ $selectedId ? 'Edit Car' : 'Add New Car' }}</h2>
            <form wire:submit.prevent="{{ $selectedId ? 'updateCar' : 'createCar' }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <select wire:model.defer="car_type_id" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                            <option value="">Select Type</option>
                            @foreach($types as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                        @error('car_type_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                </div>

                <!-- Photo (URL or File) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model.defer="name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Car name or title">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Driver Name</label>
                    <input type="text" wire:model.defer="driver_name" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Driver name">
                    @error('driver_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="e.g., 01xxxxxxxxx">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">AC</label>
                    <select wire:model.defer="ac" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                        <option value="0">Non-AC</option>
                        <option value="1">AC</option>
                    </select>
                    @error('ac')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seats</label>
                    <input type="number" min="0" wire:model.defer="seat_number" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('seat_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacity (kg)</label>
                    <input type="number" min="0" wire:model.defer="weight_capacity" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary">
                    @error('weight_capacity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rent Price (৳)</label>
                    <input type="number" min="0" wire:model.defer="rent_price" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="e.g., 2500">
                    @error('rent_price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                    <input type="text" wire:model.defer="address" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="Street, area, etc.">
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Map URL</label>
                    <input type="url" wire:model.defer="map" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="https://maps.google.com/...">
                    @error('map')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
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
                    <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'car-form')">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow">{{ $selectedId ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Delete Modal -->
    <x-modal name="delete-car" :show="false" maxWidth="sm" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Delete Car</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this car?</p>
            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" @click="$dispatch('close-modal', 'delete-car')">Cancel</button>
                <button type="button" wire:click="deleteSelectedCar" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 shadow">Delete</button>
            </div>
        </div>
    </x-modal>

    <!-- Car Details Modal -->
    <x-modal name="car-details" :show="false" maxWidth="2xl" focusable>
        <div class="p-0">
            <div class="relative bg-primary/10 dark:bg-gray-800 p-6 sm:p-8">
                <div class="flex items-center gap-5">
                    <div class="relative h-24 w-36 rounded-lg overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg flex-shrink-0">
                        @php $photo = $carDetails['photo_url'] ?? null; @endphp
                        @if(!empty($photo))
                            <img src="{{ $photo }}" onerror="{{getErrorImage()}}" alt="Car photo" class="h-full w-full object-cover" />
                        @else
                            <div class="h-full w-full bg-amber/20 flex items-center justify-center">
                                <i class='bx bxs-car text-4xl text-amber-500'></i>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $carDetails['name'] ?? '' }}</h2>
                        <p class="text-sm text-primary font-semibold">{{ $carDetails['type'] ?? '' }} • {{ $carDetails['upazila'] ?? '' }}</p>
                        <div class="mt-2 flex flex-wrap gap-2 text-xs">
                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-gray-700 dark:text-gray-200">{{ !empty($carDetails['ac']) ? 'AC' : 'Non-AC' }}</span>
                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-gray-700 dark:text-gray-200">Seats: {{ $carDetails['seats'] ?? 0 }}</span>
                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-gray-700 dark:text-gray-200">Capacity: {{ $carDetails['capacity'] ?? 0 }} kg</span>
                            @if(($carDetails['rent_price'] ?? 0) > 0)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 rounded">৳ {{ number_format((int)$carDetails['rent_price']) }}/day</span>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="absolute top-4 right-4 p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" @click="$dispatch('close-modal', 'car-details')">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white dark:bg-gray-900">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bxs-user-account text-primary'></i>
                            <span class="font-medium">@lang('Driver')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $carDetails['driver_name'] ?? '' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bxs-phone-call text-primary'></i>
                            <span class="font-medium">@lang('Phone')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $carDetails['phone'] ?? '' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bxs-map-pin text-primary'></i>
                            <span class="font-medium">@lang('Address')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $carDetails['address'] ?? '' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <i class='bx bxs-user text-primary'></i>
                            <span class="font-medium">@lang('Posted By')</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $carDetails['created_by'] ?? '' }} • {{ $carDetails['created_at'] ?? '' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if(($carDetails['map'] ?? null))
                        <a href="{{ $carDetails['map'] }}" target="_blank" rel="noopener" class="px-4 py-2 border border-teal-500 text-teal-600 rounded-lg text-sm font-semibold hover:bg-teal-500/10 transition">@lang('Open Google Map')</a>
                    @endif
                    @if(($carDetails['phone'] ?? null))
                        <a href="tel:{{ $carDetails['phone'] }}" class="px-4 py-2 bg-teal-500 text-white rounded-lg text-sm font-semibold hover:bg-teal-600 transition">@lang('Call Now')</a>
                    @endif
                </div>
            </div>
        </div>
    </x-modal>
</div>

