@extends('components.layouts.app')

@push('head')
    <!-- Additional CSS and Scripts -->
    <style>
        a.status-1 {
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto py-6 px-4" x-data="setup()">
        <header class="flex items-center justify-between py-4 bg-gray-800 text-white">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <a href="{{ action('\Barryvdh\TranslationManager\Controller@getIndex') }}" class="ml-4 text-xl font-bold">Translation Manager</a>
            </div>
            <button @click="toggleTheme" class="text-white focus:outline-none">
                Toggle Theme
            </button>
        </header>

        <div class="mt-6">
            <p class="mb-4">Warning, translations are not visible until they are exported back to the app/lang file, using <code>php artisan translation:export</code> command or publish button.</p>

            <div x-show="successImport" class="bg-green-500 text-white p-4 rounded mb-4">
                <p>Done importing, processed <strong class="counter">N</strong> items! Reload this page to refresh the groups!</p>
            </div>

            <div x-show="successFind" class="bg-green-500 text-white p-4 rounded mb-4">
                <p>Done searching for translations, found <strong class="counter">N</strong> items!</p>
            </div>

            <div x-show="successPublish" class="bg-green-500 text-white p-4 rounded mb-4">
                <p>Done publishing the translations for group '{{ $group }}'!</p>
            </div>

            <div x-show="successPublishAll" class="bg-green-500 text-white p-4 rounded mb-4">
                <p>Done publishing the translations for all group!</p>
            </div>

            @if(Session::has('successPublish'))
                <div class="bg-blue-500 text-white p-4 rounded mb-4">
                    {{ Session::get('successPublish') }}
                </div>
            @endif

            @if(!isset($group))
                <form class="mb-4" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postImport') }}">
                    @csrf
                    <div class="flex items-center mb-4">
                        <select name="replace" class="form-select block w-1/4">
                            <option value="0">Append new translations</option>
                            <option value="1">Replace existing translations</option>
                        </select>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded ml-4">Import groups</button>
                    </div>
                </form>

                <form class="mb-4" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postFind') }}">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Find translations in files</button>
                </form>
            @endif

            @if(isset($group))
                <form class="flex items-center mb-4" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postPublish', $group) }}">
                    @csrf
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mr-4">Publish translations</button>
                    <a href="{{ action('\Barryvdh\TranslationManager\Controller@getIndex') }}" class="bg-gray-300 text-black px-4 py-2 rounded">Back</a>
                </form>
            @endif

            <form method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postAddGroup') }}" class="mb-6">
                @csrf
                <div class="mb-4">
                    <p>Choose a group to display the group translations. If no groups are visible, make sure you have run the migrations and imported the translations.</p>
                    <select name="group" id="group" class="form-select block w-full mb-4">
                        @foreach($groups as $key => $value)
                            <option value="{{ $key }}" {{ $key == $group ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Enter a new group name and start editing translations in that group</label>
                    <input type="text" class="form-input block w-full" name="new-group" />
                </div>
                <div>
                    <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Add and edit keys</button>
                </div>
            </form>

            @if($group)
                <form method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postAdd', [$group]) }}" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2">Add new keys to this group</label>
                        <textarea class="form-textarea block w-full" rows="3" name="keys" placeholder="Add 1 key per line, without the group prefix"></textarea>
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add keys</button>
                </form>

                <div class="mb-4">
                    <button class="bg-gray-300 text-black px-4 py-2 rounded enable-auto-translate-group">Use Auto Translate</button>
                </div>

                <form x-show="showAutoTranslate" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postTranslateMissing') }}" class="bg-gray-100 p-4 rounded mb-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2">Base Locale for Auto Translations:</label>
                        <select name="base-locale" id="base-locale" class="form-select block w-full">
                            @foreach ($locales as $locale)
                                <option value="{{ $locale }}">{{ $locale }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2">Enter target locale key:</label>
                        <input type="text" name="new-locale" class="form-input block w-full" id="new-locale" placeholder="Enter target locale key" />
                    </div>
                    @if(!config('laravel_google_translate.google_translate_api_key'))
                        <p class="text-sm text-gray-500">
                            If you would like to use Google Translate API, install tanmuhittin/laravel-google-translate and enter your Google Translate API key in the config file laravel_google_translate.
                        </p>
                    @endif
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Auto translate missing translations</button>
                </form>
            @endif

            <hr class="my-6">

            @if($group)
                <h4 class="mb-4">Total: {{ $numTranslations }}, changed: {{ $numChanged }}</h4>
                <table class="w-full bg-white dark:bg-gray-800 border rounded-lg mb-4">
                    <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="py-2 px-4 text-left">Key</th>
                        @foreach ($locales as $locale)
                            <th class="py-2 px-4 text-left">{{ $locale }}</th>
                        @endforeach
                        @if ($deleteEnabled)
                            <th class="py-2 px-4 text-left">&nbsp;</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($translations as $key => $translation)
                        <tr id="{{ htmlentities($key, ENT_QUOTES, 'UTF-8', false) }}" class="border-t">
                            <td class="py-2 px-4">{{ htmlentities($key, ENT_QUOTES, 'UTF-8', false) }}</td>
                            @foreach ($locales as $locale)
                                    <?php $t = isset($translation[$locale]) ? $translation[$locale] : null ?>
                                <td class="py-2 px-4">
                                    <a href="#edit" class="editable status-{{ $t ? $t->status : 0 }} locale-{{ $locale }}" data-locale="{{ $locale }}" data-name="{{ $locale . "|" . htmlentities($key, ENT_QUOTES, 'UTF-8', false) }}" id="username" data-type="textarea" data-pk="{{ $t ? $t->id : 0 }}" data-url="{{ $editUrl }}" data-title="Enter translation">{{ $t ? htmlentities($t->value, ENT_QUOTES, 'UTF-8', false) : '' }}</a>
                                </td>
                            @endforeach
                            @if ($deleteEnabled)
                                <td class="py-2 px-4">
                                    <a href="{{ action('\Barryvdh\TranslationManager\Controller@postDelete', [$group, $key]) }}" class="text-red-600 hover:text-red-800" data-confirm="Are you sure you want to delete the translations for '{{ htmlentities($key, ENT_QUOTES, 'UTF-8', false) }}?">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <fieldset class="mb-6">
                    <legend class="text-xl font-bold">Supported locales</legend>
                    <p class="mb-4">Current supported locales:</p>
                    <form class="mb-4" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postRemoveLocale') }}">
                        @csrf
                        <ul class="list-locales space-y-2">
                            @foreach($locales as $locale)
                                <li class="flex items-center space-x-2">
                                    <button type="submit" name="remove-locale[{{ $locale }}]" class="text-red-600 hover:text-red-800">
                                        &times;
                                    </button>
                                    <span>{{ $locale }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </form>
                    <form method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postAddLocale') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2">Enter new locale key:</label>
                            <input type="text" name="new-locale" class="form-input block w-1/4" />
                        </div>
                        <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Add new locale</button>
                    </form>
                </fieldset>

                <fieldset>
                    <legend class="text-xl font-bold">Export all translations</legend>
                    <form method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postPublish', '*') }}">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Publish all</button>
                    </form>
                </fieldset>
            @endif
        </div>
    </div>

    <script>
        function setup() {
            return {
                isDark: false,
                sidebarOpen: false,
                successImport: false,
                successFind: false,
                successPublish: false,
                successPublishAll: false,
                showAutoTranslate: false,
                toggleTheme() {
                    this.isDark = !this.isDark;
                    document.documentElement.classList.toggle('dark', this.isDark);
                }
            }
        }
    </script>

@endsection
