<div class="p-6 bg-gray-100 dark:bg-darker min-h-screen">
    <div class="flex gap-2 justify-center">
        <button wire:click="$set('activeLocale', 'en')" class="text-xs px-4 py-2 bg-green-500 text-white dark:bg-green-700 rounded hover:bg-green-600 dark:hover:bg-green-800 capitalize
                {{ $activeLocale == 'en' ? 'bg-green-700 dark:bg-green-900' : '' }}
                ">
            @lang("en")
        </button>
        <button wire:click="$set('activeLocale', 'bn')" class="text-xs px-4 py-2 bg-green-500 text-white dark:bg-green-700 rounded hover:bg-green-600 dark:hover:bg-green-800 capitalize
                {{ $activeLocale == 'bn' ? 'bg-green-700 dark:bg-green-900' : '' }}
                ">
            @lang("bn")
        </button>
        @role('admin')
        <button wire:click="$set('activeLocale', 'ar')" class="text-xs px-4 py-2 bg-green-500 text-white dark:bg-green-700 rounded hover:bg-green-600 dark:hover:bg-green-800 capitalize
                {{ $activeLocale == 'ar' ? 'bg-green-700 dark:bg-green-900' : '' }}
                ">
            @lang("ar")
        </button>
        @endrole

    </div>
    <!-- Import Translations Section -->
    <div class="my-2 grid grid-cols-2 lg:grid-cols-4 gap-2 justify-between capitalize">
        <button type="button" wire:click="importTranslations" class="px-4 py-2 bg-primary text-white font-semibold rounded-md hover:bg-primary-dark">@lang("import from json")</button>
        <button wire:click="importBlade" class="px-4 py-2 bg-primary text-white font-semibold rounded-md hover:bg-primary-dark">@lang("import from blade")</button>
        <button wire:click="exportTranslations" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">@lang("export to database")</button>
        <button wire:click="clearTranslations" class="px-4 py-2 bg-red-500 text-white font-semibold rounded-md hover:bg-red-600">@lang("clear database")</button>

        @if (session()->has('message'))
            <div class="mt-4 text-green-500 dark:text-green-400">{{ session('message') }}</div>
        @endif
    </div>
    <div>
        <div class="flex items-center justify-between space-x-2 capitalize">
            <div class=" mt-4 md:mt-0 w-24 md:w-48">
                <input wire:model.live.debounce.500ms="itemPerPage" type="number" class="block w-full py-1.5 text-gray-500 bg-white border border-gray-200 rounded-lg md:w-40 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40"/>
            </div>
            <div class="relative flex items-center mt-4 md:mt-0">
            <span class="absolute">
                <i class='bx bx-search text-xl mx-3 text-gray-400 dark:text-gray-600'></i>
            </span>
                <input wire:model.live.debounce.500ms="search" type="text" placeholder="Search"
                       class="block w-full py-1.5 pr-5 text-gray-700 bg-white border border-gray-200 rounded-lg md:w-1/2 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                <select id="searchBy" wire:model.live="searchBy" class="block w-full py-1.5 pr-5 text-gray-500 bg-white border border-gray-200 rounded-lg md:w-1/2 placeholder-gray-400/70 pl-11 rtl:pr-11 rtl:pl-5 dark:bg-darker dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 dark:focus:border-blue-300 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
                    <option value="key">@lang('key')</option>
                    <option value="value">@lang('value')</option>
                </select>
            </div>
        </div>
    </div>


    <!-- Create / Edit Form -->
    @if($editTranslationId)
        <div class=" shadow-md rounded-lg p-4 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">{{ $editTranslationId ? 'Edit' : 'Create' }} Translation</h2>
            <form wire:submit.prevent="{{ $editTranslationId ? 'updateTranslation' : 'createTranslation' }}" class="space-y-4">

                <div>
                    <label for="key" class="block text-gray-700 dark:text-gray-200">Key:</label>
                    <x-text-input errorName="key" type="text" wire:model="key" id="key" />
                </div>

                <div>
                    <label for="value" class="block text-gray-700 dark:text-gray-200">Value:</label>
                    <x-text-input errorName="value" type="text" wire:model="value" id="value" />
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600">{{ $editTranslationId ? 'Update' : 'Create' }} @lang("Edit")</button>
            </form>
        </div>
    @endif


    <!-- Translations Table -->
    <div class=" shadow-md rounded-lg p-4 overflow-x-auto dark:scrollbar-thin-dark scrollbar-thin-light">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Translations</h2>
        <table class="min-w-full  border-collapse border border-gray-300 dark:border-gray-700">
            <thead>
            <tr class="text-center object-cover items-center h-10 text-nowrap">
                <x-field>@lang('id')</x-field>
                <x-field :OB="$orderBy" :OD="$orderDirection"
                         :field="'key'">@lang('key')</x-field>
                <x-field :OB="$orderBy" :OD="$orderDirection"
                         :field="'value'">@lang('value')</x-field>
                <x-field>@lang('action')</x-field>
            </tr>
            </thead>
            <tbody>
            @foreach ($translations as $translation)
                <tr>
                    <td class="border border-gray-500 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $translation->id }}</td>
                    <td class="border border-gray-500 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $translation->key }}</td>
                    <td class="border border-gray-500 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $translation->value }}</td>
                    <td class="border border-gray-500 px-4 py-2">
                        <button wire:click="editTranslation({{ $translation->id }})" class="px-2 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</button>
                        <button wire:click="deleteTranslation({{ $translation->id }})" onclick="return confirm('Are you sure?')" class="px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">Delete</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mx-auto my-4 px-4 overflow-y-auto">{{ $translations->links() }}</div>

</div>
