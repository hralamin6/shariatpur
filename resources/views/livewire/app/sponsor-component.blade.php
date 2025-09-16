<div class="m-0 md:m-2 capitalize" x-data="sponsor()">
    <div class="flex justify-between gap-4 mb-2 capitalize">
        <p class="text-gray-700 dark:text-gray-200 text-xl font-semibold">@lang("all sponsors")</p>
        <div class="flex text-sm gap-1">
            <a href="{{route('app.dashboard')}}" wire:navigate class="text-blue-500 dark:text-blue-400">@lang("dashboard")</a>
            <span class="text-gray-500 dark:text-gray-200">/</span>
            <span class="text-gray-500 dark:text-gray-300">@lang("sponsors")</span>
        </div>
    </div>

    <main class="h-full">
        @can("app.sponsors.create")
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="flex items-center gap-x-3">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">@lang('sponsors')</h2>
                    <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">@lang('total'): {{ $items->total() }}</span>
                </div>
                <div class="flex items-center mt-4 gap-x-3">
                    <button x-cloak @click="toggleModal"
                            class="capitalize flex items-center justify-center w-1/2 px-5 py-2 text-sm tracking-wide text-white transition-colors duration-200 bg-blue-500 rounded-lg shrink-0 sm:w-auto gap-x-2 hover:bg-blue-600 dark:hover:bg-blue-500 dark:bg-blue-600">
                        <i class='bx bx-plus font-thin text-xl'></i>
                        <span>@lang('add new')</span>
                    </button>
                </div>
            </div>
        @endcan

        <div class="mt-6 md:flex md:items-center md:justify-between capitalize">
            <div class="inline-flex overflow-hidden bg-white border divide-x rounded-lg dark:bg-darker rtl:flex-row-reverse dark:border-gray-700 dark:divide-gray-700">
                <button wire:click="$set('itemStatus', null)"
                        class="capitalize px-5 py-2 text-xs font-medium transition-colors duration-200 sm:text-sm text-gray-600 {{!$itemStatus?'bg-gray-100 dark:bg-gray-800 dark:text-gray-300':'dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100'}} ">
                    @lang('all')
                </button>
                <button wire:click="$set('itemStatus', 'active')"
                        class="capitalize px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm {{$itemStatus=='active'?'bg-gray-100 dark:bg-gray-800 dark:text-gray-300':'dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100'}}">
                    @lang('active')
                </button>
                <button wire:click="$set('itemStatus', 'inactive')"
                        class="capitalize px-5 py-2 text-xs font-medium text-gray-600 transition-colors duration-200 sm:text-sm {{$itemStatus=='inactive'?'bg-gray-100 dark:bg-gray-800 dark:text-gray-300':'dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100'}}">
                    @lang('inactive')
                </button>
            </div>

            <div class="flex items-center justify-between space-x-2 capitalize">
                <div class="mt-4 md:mt-0 w-24 md:w-48">
                    <input wire:model.live.debounce.500ms="itemPerPage" type="number" class="block w-full py-1.5 text-gray-500 bg-white border border-gray-200 rounded-lg md:w-40 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"/>
                </div>
                <div class="relative flex items-center mt-4 md:mt-0">
                    <span class="absolute">
                        <i class='bx bx-search text-xl mx-3 text-gray-400 dark:text-gray-600'></i>
                    </span>
                    <input wire:model.live.debounce.500ms="search" type="text" placeholder="Search"
                           class="block w-full py-1.5 pr-5 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-1/2 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    <select id="searchBy" wire:model.live="searchBy" class="block w-full py-1.5 pr-5 text-gray-500 bg-white border border-gray-200 rounded-lg md:w-1/2 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                        <option value="name">@lang('name')</option>
                        <option value="title">@lang('title')</option>
                        <option value="phone">@lang('phone')</option>
                        <option value="email">@lang('email')</option>
                    </select>
                </div>
            </div>
        </div>

        <section class="sm:p-4 md:p-0">
            <div class="flex flex-col mt-6">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                            <table class="border border-collapse min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr class="text-center object-cover items-center h-10 text-nowrap">
                                    <th class="pl-2 flex items-center justify-between mt-4">
                                        @can("app.sponsors.delete")
                                            <input x-model="selectPage" type="checkbox" class="justify-between text-blue-500 border-gray-300 rounded dark:bg-darker dark:ring-offset-gray-900 dark:border-gray-500">
                                        @endcan
                                        <div x-cloak x-show="rows.length > 0" class="flex items-center justify-center" x-data="{bulk: false}">
                                            <div class="relative inline-block">
                                                <button @click="bulk=!bulk" class="relative z-10 block px-2 text-gray-700 border border-transparent rounded-md dark:text-white focus:border-blue-500 focus:ring-opacity-40 dark:focus:ring-opacity-40 focus:ring-blue-300 dark:focus:ring-blue-400 focus:ring focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-800 dark:text-white" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                    </svg>
                                                </button>
                                                <div x-show="bulk" class="absolute left-0 z-20 w-48 py-2 mt-2 bg-white rounded-md shadow-xl dark:bg-gray-800" @click.outside="bulk= false">
                                                    <a @click="$dispatch('delete', { title: 'Are you sure to delete', text: 'It is not revertable', icon: 'error',actionName: 'deleteMultiple', itemId: '' })"
                                                       class="cursor-pointer block px-4 py-3 text-sm text-gray-600 capitalize transition-colors duration-200 transform dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white">
                                                        Delete </a>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <x-field :OB="$orderBy" :OD="$orderDirection" :field="'name'">@lang('name')</x-field>
                                    <x-field :OB="$orderBy" :OD="$orderDirection" :field="'title'">@lang('title')</x-field>
                                    <x-field :OB="$orderBy" :OD="$orderDirection" :field="'phone'">@lang('phone')</x-field>
                                    <x-field :OB="$orderBy" :OD="$orderDirection" :field="'email'">@lang('email')</x-field>
                                    <x-field :OB="$orderBy" :OD="$orderDirection" :field="'status'">@lang('status')</x-field>
                                    <x-field :OB="$orderBy" :OD="$orderDirection" :field="'expired_at'">@lang('expired at')</x-field>
                                    <x-field>@lang('action')</x-field>
                                </tr>
                                </thead>
                                <tbody class="bg-white truncate divide-y divide-gray-200 dark:divide-gray-700 dark:bg-darker">
                                @forelse($items as $i => $item)
                                    <tr id="item-id-{{$item->id}}" class="text-gray-700 dark:text-gray-300 capitalize text-center object-cover items-center" :class="{'bg-gray-200 dark:bg-gray-600': rows.includes('{{$item->id}}') }">
                                        <td class="max-w-48 truncate pl-2">
                                            @can("app.sponsors.delete")
                                                <input x-model="rows" value="{{$item->id}}" id="{{ $item->id }}" type="checkbox" class="justify-between text-blue-500 border-gray-300 rounded dark:bg-darker dark:ring-offset-gray-900 dark:border-gray-500">
                                            @endcan
                                        </td>

                                        <td class="max-w-48 truncate px-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <div class="flex items-center gap-x-2 overflow-x-auto">
                                                @foreach($item->getMedia('sponsorImages') as $k => $media)
                                                    <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Sponsor Image" onerror="{{getErrorImage()}}" class="w-8 h-8 border-1 border-white dark:border-darker shadow-lg mb-4">
                                                @endforeach
                                                <h2 class="font-medium text-gray-800 dark:text-white flex-grow">{{ $item->name }}</h2>
                                            </div>
                                        </td>
                                        <td class="max-w-48 truncate px-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ str($item->title)->words(5) }}</td>
                                        <td class="max-w-48 truncate px-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ $item->phone }}</td>
                                        <td class="max-w-48 truncate px-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ $item->email }}</td>
                                        <td class="max-w-48 truncate px-12 text-sm font-medium text-gray-700 whitespace-nowrap">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full gap-x-2 bg-emerald-100/60 dark:bg-gray-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                <button type="button" wire:click="changeStatus({{ $item->id }})" class="cursor-pointer text-sm font-normal {{ $item->status=='active'?'text-emerald-500':'text-pink-500' }} ">{{ $item->status }}</button>
                                            </div>
                                        </td>
                                        <td class="max-w-48 truncate px-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">{{ optional($item->expired_at)->diffForHumans(null, true, true) ?? '-' }}</td>
                                        <td class="max-w-48 truncate px-4 text-sm whitespace-nowrap">
                                            <div class="flex justify-between text-center gap-x-6">
                                                @can("app.sponsors.edit")
                                                    <button @click="editModal('{{$item->id}}')">
                                                        <i class='bx bx-pencil text-2xl font-medium text-yellow-700 transition-colors duration-200 dark:hover:text-yellow-300 hover:text-yellow-500'></i>
                                                    </button>
                                                @endcan
                                                @can("app.sponsors.delete")
                                                    <button @click.prevent="$dispatch('delete', { title: 'Are you sure to delete', text: 'It is not revertable', icon: 'error',actionName: 'deleteSingle', itemId: {{$item->id}} })">
                                                        <i class='bx bx-trash text-2xl font-medium text-red-700 transition-colors duration-200 dark:hover:text-red-300 hover:text-red-500'></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">@lang('No sponsors found.')</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="mx-auto my-4 px-4 overflow-y-auto">{{ $items->links() }}</div>
    </main>

    <div x-cloak x-show="isOpen">
        <div class="fixed inset-0 z-10 flex items_end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
        <div class="inset-0 rounded-2xl transition duration-150 ease-in-out z-50 absolute" id="modal">
            <div @click.outside="closeModal" class="container mx-auto w-11/12 md:w-8/12 max-w-4xl ">
                <form @submit.prevent="editMode? $wire.editData(): $wire.saveData()" class="relative py-3 px-5 md:px-10 bg-white dark:bg-darker shadow-md rounded border border-gray-400 dark:border-gray-600 capitalize">
                    <h1 x-cloak x-show="!editMode" class="text-gray-800 dark:text-gray-200 font-lg font-semibold text-center mb-4">@lang('add new sponsor')</h1>
                    <h1 x-cloak x-show="editMode" class="text-gray-800 dark:text-gray-200 font-lg font-semibold text-center mb-4">@lang('edit sponsor')</h1>

                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <div>
                            <label class="text-gray-700 dark:text-gray-200" for="name">@lang('name')</label>
                            <x-text-input errorName="name" x-ref="inputName" id="name" wire:model.live.debounce.800ms="name" type="text"/>
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-200" for="title">@lang('title')</label>
                            <x-text-input errorName="title" id="title" wire:model="title" type="text"/>
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-200" for="phone">@lang('phone')</label>
                            <x-text-input errorName="phone" id="phone" wire:model="phone" type="text"/>
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-200" for="email">@lang('email')</label>
                            <x-text-input errorName="email" id="email" wire:model="email" type="email"/>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-gray-700 dark:text-gray-200" for="address">@lang('address')</label>
                            <x-text-input errorName="address" id="address" wire:model="address" type="text"/>
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-200" for="status">@lang('status')</label>
                            <x-select id="status" wire:model="status">
                                <option value="active">@lang('active')</option>
                                <option value="inactive">@lang('inactive')</option>
                            </x-select>
                        </div>
                        <div>
                            <label class="text-gray-700 dark:text-gray-200" for="expired_at">@lang('expired at')</label>
                            <x-text-input errorName="expired_at" id="expired_at" wire:model="expired_at" type="datetime-local"/>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-2 items-center justify-between overflow-x-auto max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-darker shadow-lg rounded-lg p-4">
                        <div class="flex gap-2">
                            @if($photo)
                                @foreach($photo as $p)
                                    <img src="{{ $p->temporaryUrl() }}" alt="Preview" class="mt-2 w-16 h-16 md:w-24 md:h-24 object-cover"/>
                                @endforeach
                            @else
                                @if($sponsor!==null)
                                    @foreach($sponsor->getMedia('sponsorImages') as $k => $media)
                                        <img src="{{$media->getAvailableUrl(['thumb'])}}" alt="Image"  onerror="{{getErrorImage()}}"
                                             class="w-16 h-16 md:w-24 md:h-24 rounded-full border-4 border-white dark:border-darker shadow-lg mb-4">
                                        <button class="text-pink-500" wire:click.prevent="deleteMedia({{$sponsor}}, {{$k}})">X</button>
                                    @endforeach
                                @endif
                            @endif
                        </div>

                        <div>
                            <div>
                                <x-text-input errorName="image_url" placeholder="image link" id="image_url" wire:model="image_url" type="url"/>
                            </div>
                            <div class="flex flex-col items-center" x-data="{ isUploading: false, progress: 5 }"
                                 x-on:livewire-upload-start="isUploading = true"
                                 x-on:livewire-upload-finish="isUploading = false"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <div x-show="isUploading" class="w-full mt-4">
                                    <div class="relative pt-1">
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs font-semibold text-blue-600 dark:text-blue-400" x-text="progress + '%' "></div>
                                        </div>
                                        <div class="flex w-full bg-gray-200 dark:bg-gray-700 rounded-full">
                                            <div class="bg-blue-600 dark:bg-blue-400 text-xs font-medium text-blue-100 text-center p-0.5 leading_none rounded-full"
                                                 x-bind:style="'width: ' + progress + '%'"
                                                 x-text="progress + '%'">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <x-text-input errorName="photo" multiple type="file" wire:model="photo" accept="image/*" class="mb-4"></x-text-input>
                            </div>
                        </div>

                    </div>
                    <div class="flex items-center justify-between w-full mt-4">
                        <button type="button" @click="closeModal" class="bg-red-600 focus:ring-gray-400 transition duration-150 text-white ease-in-out hover:bg-red-300 rounded px-8 py-2 text-sm">Cancel</button>
                        <button wire:loading.remove wire:target="editData,saveData" type="submit" class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 transition duration-150 ease-in-out hover:bg-indigo-600 bg-indigo-700 rounded text-white px-8 py-2 text-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
    <script>
        Alpine.data('sponsor', () => ({
            init() {
                $wire.on('dataAdded', (e) => {
                    this.isOpen = false
                    this.editMode = false
                    $nextTick(() => {
                        element = document.getElementById(e.dataId)
                        element.scrollIntoView({ behavior: 'smooth' });
                        element.classList.add('animate-pulse');
                    });
                    setTimeout(() => { element.classList.remove('animate-pulse'); }, 5000)
                })
            },
            isOpen: false,
            editMode: false,
            rows: @entangle('selectedRows'),
            selectPage: @entangle('selectPageRows').live,
            toggleModal() {
                this.isOpen = !this.isOpen;
                this.$nextTick(() => { this.$refs.inputName.focus() })
            },
            closeModal() {
                this.isOpen = false;
                this.editMode = false;
                $wire.resetData()
            },
            editModal(id) {
                this.$wire.loadData(id);
                this.isOpen = true;
                this.editMode = true;
            }
        }))
        document.addEventListener('delete', function (event) {
            itemId = event.detail.itemId
            actionName = event.detail.actionName
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire[actionName](itemId)
                }
            })
        });
    </script>
    @endscript
</div>

