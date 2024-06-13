<div class="mb-1 overflow-y-scroll scrollbar-none">
    <button wire:click="backupCreate()" class="capitalize text-center mx-auto my-8 rounded-full px-6 py-3 bg-blue-500 text-white">
        @lang('create backup')
    </button>
    <code>{{$output}}</code>
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
                    <td class="px-4 py-3 text-xs"> <code>{{$backup['file_name']}}</code> </td>
                    <td class="px-4 py-3 text-xs">{{$backup['file_size']}}</td>
                    <td class="px-4 py-3 text-xs">{{$backup['created_at']}}</td>
                    <td  class="px-4 py-3 text-sm">
                        <button @click="editModal('{{$backup['file_name']}}')"
                                class="text-gray-500 transition-colors duration-200 dark:hover:text-red-500 dark:text-gray-300 hover:text-red-500 focus:outline-none">
                            <x-h-o-pencil-square class="text-green-400"/>
                        </button>
                        <button
                            @click.prevent="$dispatch('delete', { title: 'Are you sure to delete', text: 'It is not revertable', icon: 'error',actionName: 'diskDelete', itemId: '{{$backup['file_name']}}' })"
                            class="text-gray-500 transition-colors duration-200 dark:hover:text-yellow-500 dark:text-gray-300 hover:text-yellow-500 focus:outline-none">
                            <x-h-o-trash class="text-red-400"/>
                        </button>
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
