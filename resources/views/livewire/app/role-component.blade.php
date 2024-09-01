<div class="m-0 md:m-2" x-data="role()">
    <div class="flex justify-between gap-4 mb-2 capitalize">
        <p class="text-gray-700 dark:text-gray-200 text-xl font-semibold">@lang("all roles")</p>
        <div class="flex text-sm gap-1">
            <a href="{{route('app.roles')}}" wire:navigate class="text-blue-500 dark:text-blue-400">@lang("dashboard")</a>
            <span class="text-gray-500 dark:text-gray-200">/</span>
            <span class="text-gray-500 dark:text-gray-300">@lang("roles")</span>
        </div>
    </div>    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class=" rounded-xl mt-4" x-data="{openTable: $persist(true)}">
            <aside class="border dark:border-gray-600 row-span-4 bg-white dark:bg-darkSidebar capitalize">
                <div class="flex justify-between gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2">
                    <p class="text-gray-600 dark:text-gray-200">@lang('roles table')</p>
                    <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
                        @can("app.roles.create")
                            <a  @click="isOpen=!isOpen" class="cursor-pointer">
                                <span x-show="!isOpen" class="bg-primary text-white text-xs px-2 py-1 rounded-full">@lang('add new')</span>
                                <span x-show="isOpen" class="bg-secondary text-white text-xs px-2 py-1 rounded-full">@lang('close')</span>
                            </a>
                        @endcan
                        <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full dark:bg-gray-800 dark:text-blue-400">@lang('total'): {{ $items->total() }}</span>
                        <button class="" @click="openTable = !openTable">
                            <svg x-show="openTable" xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <svg x-show="!openTable" xmlns="http://www.w3.org/2000/svg" class="h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        <button class="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div x-show="openTable" x-collapse>
                    <div class="mb-1 overflow-y-scroll scrollbar-none">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                <tr
                                    class="text-xs capitalize font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-darkSidebar"
                                >
                                    <th class="px-4 py-3">@lang('serial')</th>
                                    <th class="px-4 py-3">@lang('name')</th>
                                    <th class="px-4 py-3">@lang('count')</th>
                                    <th class="px-4 py-3">@lang('update')</th>
                                    <th class="px-4 py-3">@lang('action')</th>
                                </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y dark:divide-gray-700 dark:bg-darkSidebar"
                                >
                                @foreach($items as $i => $item)
                                    <tr  wire:key="{{$i}}" id="item-id-{{$item->id}}" class="text-gray-700 dark:text-gray-400 capitalize">
                                        <td class="px-4 py-3 text-xs">{{$i+1}}</td>
                                        <td class="px-4 py-3 text-xs">{{$item->name}}</td>
                                        <td class="px-4 py-3 text-sm">{{$item->permissions->count()}}</td>
                                        <td class="px-4 py-3 text-sm">{{$item->updated_at->diffForHumans(null, true, true)}}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-x-6">
                                                @can("app.roles.edit")
                                                    <button @click="editModal('{{$item->id}}')" class="">
                                                        <i class='bx bx-pencil text-2xl font-medium text-yellow-700 transition-colors duration-200 dark:hover:text-yellow-300 hover:text-yellow-500'></i>
                                                    </button>
                                                @endcan

                                                @if($item->deletable)
                                                    @can("app.roles.delete")
                                                        <button @click.prevent="$dispatch('delete', { title: 'Are you sure to delete', text: 'you will loss this data forever', icon: 'error',actionName: 'deleteSingle', itemId: {{$item->id}} })">
                                                            <i class='bx bx-trash text-2xl font-medium text-red-700 transition-colors duration-200 dark:hover:text-red-300 hover:text-red-500'></i>

                                                        </button>
                                                    @endcan

                                                @endif

                                            </div>
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </aside>
        </div>

        <div x-show="isOpen" class=" rounded-xl mt-4" x-data="{formTable: $persist(true)}">
            <aside class="border dark:border-gray-600 row-span-4 bg-white dark:bg-darkSidebar capitalize">
                <div class="flex justify-between gap-3 bg-white border dark:border-gray-600 dark:bg-darkSidebar px-4 py-2">
                    <p x-show="!editMode" class="text-gray-600 dark:text-gray-200">@lang("add new role")</p>
                    <p x-show="editMode" class="text-gray-600 dark:text-gray-200">@lang("edit role")</p>
                    <div class="flex justify-center gap-4 text-gray-500 dark:text-gray-300">
                        <button class="" @click="formTable = !formTable">
                            <svg x-show="formTable" xmlns="http://www.w3.org/2000/svg" class="h-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <svg x-show="!formTable" xmlns="http://www.w3.org/2000/svg" class="h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div x-show="formTable" x-collapse>
                    <div class="mb-1 overflow-y-scroll scrollbar-none capitalize">
                        <div class="flex items-center justify-center p-6">
                            <div class="w-full">
                                <label  class="block text-sm">
                                    <span class="text-gray-700 dark:text-gray-400">@lang('role name')</span>
                                    <x-text-input wire:model="name" :error="'name'" class=""/>
{{--                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />--}}

                                </label>
                                <label class="flex items-center ml-4 items-center text-center m-2 text-md">
                                    <input  type="checkbox" wire:model.live="allCheckBox"
                                            class="text-purple-600 text-sm w-3 h-3 outline-none focus:outline-none form-checkbox focus:border-purple-400 focus:shadow-outline-purple dark:focus:shadow-outline-gray"/>
                                    <span class="ml-2 text-gray-500 dark:text-gray-300 text-sm">@lang('all permissions')</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-1 mt-2" x-data="{allCheck:false}">

                                    @forelse($modules as $i => $module)
                                        <div wire:key="{{$i}}" x-data="{ moduleChecked: false}"
                                             class="flex flex-col p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700 shadow-md transition-transform transform hover:scale-105">
                                            <div>
                                                <h3 class="text-gray-600 dark:text-gray-200 text-md font-semibold">
                                                    <label class="flex items-center">
                                                        <input wire:key="{{$module->id}}" type="checkbox" wire:model.live="moduleCheckBoxes.{{$module->id}}" value="1"
{{--                                                               x-model="moduleChecked"--}}
{{--                                                               @click="moduleChecked=!moduleChecked, $wire.toggleModule({{$module->id}}, moduleChecked)"--}}
                                                               class="text-purple-600 text-lg w-3 h-3 outline-none focus:outline-none form-checkbox focus:border-purple-400 focus:shadow-outline-purple dark:focus:shadow-outline-gray"/>
                                                        <span class="ml-2"> <span class="dark:text-gray-300 text-gray-500">{{$module->name}}</span></span>
                                                    </label>
                                                </h3>
                                            </div>
                                            <div class="mt-2 space-y-2">
                                                @forelse($module->permissions as $j => $m_permission)
                                                    <label wire:key="{{$j}}" class="flex items-center ml-4">
                                                        <input type="checkbox"
                                                               value="{{$m_permission->id}}"
{{--                                                               x-model="permissions"--}}
                                                               wire:model="permissions"
                                                               class="text-purple-600 text-sm w-3 h-3 outline-none focus:outline-none form-checkbox focus:border-purple-400 focus:shadow-outline-purple dark:focus:shadow-outline-gray"/>
                                                        <span class="ml-2 text-gray-500 dark:text-gray-300 text-sm">{{$m_permission->name}}</span>
                                                    </label>
                                                    <x-input-error :messages="$errors->get('permissions.'.$j)" class="mt-2" />
                                                @empty
                                                    <p class="text-gray-500 dark:text-gray-300"> @lang("No permissions found") </p>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-center">
                                            <p class="text-gray-500 dark:text-gray-300">@lang("No modules found")</p>
                                        </div>
                                    @endforelse

                                </div>
                                <x-input-error :messages="$errors->get('permissions')" class="mt-2" />

                                <!-- You should use a button here, as the anchor is only used for the example  -->
                                <button @if($role) wire:click="updateRole" @else wire:click="createRole" @endif
                                    class="block capitalize w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                                >@lang('submit')</button>
                            </div>
                        </div>


                    </div>
                </div>
            </aside>
        </div>
    </div>
    @script
    <script>
        Alpine.data('role', () => ({
            init() {
                $wire.on('dataAdded', (e) => {
                    this.isOpen = false
                    this.editMode = false
                    element = document.getElementById(e.dataId)
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth' });
                        $nextTick(() => {
                            element.classList.add('animate-pulse');
                        });

                    }
                    setTimeout(() => {

                        element.classList.remove('animate-pulse');

                    }, 4000)
                })
            },
            isOpen: false,
            editMode: false,
            {{--rows: @entangle('selectedRows'),--}}
            {{--selectPage: @entangle('selectPageRows').live,--}}
            toggleModal() {
                this.isOpen = !this.isOpen;
                this.$nextTick(() => {
                    this.$refs.inputName.focus()
                })
            },
            closeModal() {
                this.isOpen = false;
                this.editMode = false;
                $wire.resetData()
            },
            editModal(id) {
                this.$wire.editRole(id);
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

