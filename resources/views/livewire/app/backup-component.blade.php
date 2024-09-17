<div class="mb-1 overflow-y-scroll scrollbar-none">
    <div class="flex justify-between gap-4 mb-2 capitalize">
        <p class="text-gray-700 dark:text-gray-200 text-xl font-semibold">@lang("all backups")</p>
        <div class="flex text-sm gap-1">
            <a href="{{route('app.backups')}}" wire:navigate class="text-blue-500 dark:text-blue-400">@lang("dashboard")</a>
            <span class="text-gray-500 dark:text-gray-200">/</span>
            <span class="text-gray-500 dark:text-gray-300">@lang("backups")</span>
        </div>
    </div>
    <div class="mx-auto my-2 text-center flex justify-between">
        @can("app.backups.create")
            <x-secondary-button
                wire:click="backupCreate()">
                <!-- Button text -->
                <span class="block" wire:loading.remove wire:target="backupCreate">@lang('create backup')</span>

                <!-- Loading spinner -->
                <svg
                    class="w-5 h-5 mx-auto text-white animate-spin block"
                    wire:loading wire:target="backupCreate"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6h2a4 4 0 01-4-4z"></path>
                </svg>
            </x-secondary-button>
        @endcan

        @can("app.backups.delete")
            <x-secondary-button wire:click.prevent="backupClean" class="!bg-red-500">
                @lang('clear backup')
            </x-secondary-button>
        @endcan
            @can("app.backups.create")
                <x-secondary-button
                    wire:click="backupCreateDb()">
                    <!-- Button text -->
                    <span class="block" wire:loading.remove wire:target="backupCreateDb">@lang('create db backup')</span>

                    <!-- Loading spinner -->
                    <svg
                        class="w-5 h-5 mx-auto text-white animate-spin block"
                        wire:loading wire:target="backupCreateDb"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6h2a4 4 0 01-4-4z"></path>
                    </svg>
                </x-secondary-button>
            @endcan

    </div>


{{--    <code>{{$output}}</code>--}}
    <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
            <thead>
            <tr
                class="text-xs capitalize font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-darkSidebar"
            >
                <th class="px-4 py-3">@lang('serial')</th>
                <th class="px-4 py-3">@lang('file name')</th>
                <th class="px-4 py-3">@lang('size')</th>
                <th class="px-4 py-3">@lang('created at')</th>
                <th class="px-4 py-3">@lang('action')</th>
            </tr>
            </thead>
            <tbody
                class="bg-white divide-y dark:divide-gray-700 dark:bg-darkSidebar"
            >
            @foreach($backups as $i => $backup)
                <tr wire:key="{{$i}}" class="text-gray-700 dark:text-gray-400 capitalize">
                    <td class="px-4 py-3 text-xs">{{$i+1}}</td>
                    <td class="px-4 py-3 text-xs text-purple-500"> <code>{{$backup['file_name']}}</code> </td>
                    <td class="px-4 py-3 text-xs">{{$backup['file_size']}}</td>
                    <td class="px-4 py-3 text-xs">{{$backup['created_at']}}</td>
                    <td  class="px-4 py-3 text-sm">
                        @can("app.backups.download")
                            <button wire:loading.remove wire:target="backupDownload" wire:click.prevent="backupDownload('{{$backup['file_name']}}')"
                                    class="text-green-500 transition-colors duration-200 dark:hover:text-green-600 dark:text-green-500 hover:text-green-600 focus:outline-none">
                                <i class='bx bx-download text-2xl'></i>
                            </button>
                            <svg
                                class="w-5 h-5 mx-auto text-white animate-spin block"
                                wire:loading wire:target="backupDownload"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6h2a4 4 0 01-4-4z"></path>
                            </svg>
                        @endcan

                        @can("app.backups.delete")
                            <button
                                @click.prevent="$dispatch('delete', { title: 'Are you sure to delete', text: 'It is not revertable', icon: 'error',actionName: 'diskDelete', itemId: '{{$backup['file_name']}}' })"
                                class="text-red-500 transition-colors duration-200 dark:hover:text-red-600 dark:text-red-300 hover:text-red-600 focus:outline-none">
                                <i class='bx bx-trash text-2xl'></i>
                            </button>
                        @endcan
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
    @script
    <script>

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
