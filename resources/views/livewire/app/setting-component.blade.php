<div  class="m-0 md:m-2 capitalize" x-data="setting()">
    <div class="flex justify-between gap-4 mb-2 capitalize">
        <p class="text-gray-700 dark:text-gray-200 text-xl font-semibold">@lang("all settings")</p>
        <div class="flex text-sm gap-1">
            <a href="{{route('app.dashboard')}}" wire:navigate class="text-blue-500 dark:text-blue-400">@lang("dashboard")</a>
            <span class="text-gray-500 dark:text-gray-200">/</span>
            <span class="text-gray-500 dark:text-gray-300">@lang("settings")</span>
        </div>
    </div>
    <main class="h-full capitalize">
        <div class="max-w-4xl mx-auto mb-6">
            <div class="flex justify-center space-x-4 bg-white dark:bg-darker shadow-lg rounded-lg overflow-hidden">
                <!-- General Settings Tab -->
                <button
                    @click="tab = 'general'"
                    :class="{'bg-primary text-white': tab === 'general', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': tab !== 'general'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('General Settings')
                </button>
                <!-- Mail Settings Tab -->
                <button
                    @click="tab = 'mail'"
                    :class="{'bg-primary text-white': tab === 'mail', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': tab !== 'mail'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('Mail Settings')
                </button>
                <!-- OAuth Settings Tab -->
                <button
                    @click="tab = 'oauth'"
                    :class="{'bg-primary text-white': tab === 'oauth', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': tab !== 'oauth'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('OAuth Settings')
                </button>
                <!-- Update Image Tab -->
                <button
                    @click="tab = 'image'"
                    :class="{'bg-primary text-white': tab === 'image', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': tab !== 'image'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('Update Image')
                </button>
            </div>
        </div>

        <div x-show="tab === 'general'" class="max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-darker shadow-lg rounded-lg p-4">
            <h3 class="text-xl text-center font-semibold mb-4 text-gray-800 dark:text-gray-100">@lang('Update general setting')</h3>
            <form wire:submit.prevent="updateGeneral" class="space-y-4">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="input">@lang('name')</label>
                    <x-text-input errorName="name" x-ref="inputName" id="input" wire:model="name" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="email">@lang('email')</label>
                    <x-text-input errorName="email" wire:model="email" type="email"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="address">@lang('address')</label>
                    <x-text-input errorName="address" wire:model="address" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="phone">@lang('phone')</label>
                    <x-text-input errorName="phone" wire:model="phone" type="tel"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="details">@lang('details')</label>
                    <x-text-input errorName="details" wire:model="details" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="placeHolder">@lang('place holder')</label>
                    <x-text-input errorName="placeHolder" wire:model="placeHolder" type="url"/>
                </div>
                <div class="text-center">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">@lang('Update setting')</button>
                </div>
            </form>
        </div>
        <div x-show="tab === 'mail'" class="max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-darker shadow-lg rounded-lg p-4">
            <h3 class="text-xl text-center font-semibold mb-4 text-gray-800 dark:text-gray-100">@lang('Update mail information')</h3>
            <form wire:submit.prevent="updateMail" class="space-y-4">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailMailer">@lang('Mail Mailer')</label>
                    <x-text-input errorName="mailMailer" wire:model="mailMailer" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailHost">@lang('Mail Host')</label>
                    <x-text-input errorName="mailHost" wire:model="mailHost" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailPort">@lang('Mail Port')</label>
                    <x-text-input errorName="mailPort" wire:model="mailPort" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailUsername">@lang('Mail Username')</label>
                    <x-text-input errorName="mailUsername" wire:model="mailUsername" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailPassword">@lang('Mail Password')</label>
                    <x-text-input errorName="mailPassword" wire:model="mailPassword" type="password"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailEncryption">@lang('Mail Encryption')</label>
                    <x-text-input errorName="mailEncryption" wire:model="mailEncryption" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailFromAddress">@lang('Mail From Address')</label>
                    <x-text-input errorName="mailFromAddress" wire:model="mailFromAddress" type="email"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="mailFromName">@lang('Mail From Name')</label>
                    <x-text-input errorName="mailFromName" wire:model="mailFromName" type="text"/>
                </div>

                <div class="text-center">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">@lang('Update mail')</button>
                </div>
            </form>
        </div>

        <div x-show="tab === 'oauth'" class="max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-darker shadow-lg rounded-lg p-4">
            <h3 class="text-xl text-center font-semibold mb-4 text-gray-800 dark:text-gray-100">@lang('Update OAuth Settings')</h3>
            <form wire:submit.prevent="updateOAuth" class="space-y-4">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="githubClientId">@lang('GitHub Client ID')</label>
                    <x-text-input errorName="githubClientId" wire:model="githubClientId" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="githubClientSecret">@lang('GitHub Client Secret')</label>
                    <x-text-input errorName="githubClientSecret" wire:model="githubClientSecret" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="googleClientId">@lang('Google Client ID')</label>
                    <x-text-input errorName="googleClientId" wire:model="googleClientId" type="text"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="googleClientSecret">@lang('Google Client Secret')</label>
                    <x-text-input errorName="googleClientSecret" wire:model="googleClientSecret" type="text"/>
                </div>
                <div class="text-center">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">@lang('Update OAuth Settings')</button>
                </div>
            </form>
        </div>


        <div x-show="tab === 'image'" class="max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-darker shadow-lg rounded-lg p-4">
            <h3 class="text-xl text-center font-semibold mb-4 text-gray-800 dark:text-gray-100 items-center">@lang('Update image')</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 justify-between items-center-center gap-2">

                <div class="text-center items-center">
                    <center>
                        @if($logoImage)
                            <img src="{{ $logoImage->temporaryUrl() }}" alt="Logo Preview" class="mt-2 w-32 h-32 object-cover"/>
                        @else

                            @if ($logoImageUrl)
                                <img src="{{ getSettingImage('logoImage', 'logo') }}" alt="Profile Avatar"  onerror="{{getErrorImage()}}"
                                     class="w-32 h-32 rounded-full border-4 border-white dark:border-darker shadow-lg mb-4">
                            @endif
                        @endif
                    </center>
                    <div>
                        <x-text-input placeholder="logo link" errorName="logo_url"  id="logo_url" wire:model="logo_url" type="url"/>
                    </div>
                    <form class="flex flex-col items-center"
                          x-data="{ isUploading: false, progress: 5 }"
                          x-on:livewire-upload-start="isUploading = true"
                          x-on:livewire-upload-finish="isUploading = false"
                          x-on:livewire-upload-error="isUploading = false"
                          x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <div x-show="isUploading" class="w-full mt-4">
                            <div class="relative pt-1">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-semibold text-blue-600 dark:text-blue-400" x-text="progress + '%'"></div>
                                </div>
                                <div class="flex w-full bg-gray-200 dark:bg-gray-700 rounded-full">
                                    <div class="bg-blue-600 dark:bg-blue-400 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                         x-bind:style="'width: ' + progress + '%'"
                                         x-text="progress + '%'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-text-input type="file" wire:model="logoImage" accept="image/*" class="mb-4"></x-text-input>
                    </form>
                </div>
                <div class="items-center text-center">
                    <center>
                        @if($iconImage)
                            <img src="{{ $iconImage->temporaryUrl() }}" alt="icon Preview" class="mt-2 w-32 h-32 object-cover"/>
                        @else

                            @if ($iconImageUrl)
                                <img src="{{ getSettingImage('iconImage') }}" alt="Profile Avatar"  onerror="{{getErrorImage()}}"
                                     class="w-32 h-32 rounded-full border-4 border-white dark:border-darker shadow-lg mb-4">
                            @endif
                        @endif
                    </center>
                    <div>
                        <x-text-input placeholder="icon link" errorName="icon_url"  id="icon_url" wire:model="icon_url" type="url"/>
                    </div>
                    <form class="flex flex-col items-center"
                          x-data="{ isUploading: false, progress: 5 }"
                          x-on:livewire-upload-start="isUploading = true"
                          x-on:livewire-upload-finish="isUploading = false"
                          x-on:livewire-upload-error="isUploading = false"
                          x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <div x-show="isUploading" class="w-full mt-4">
                            <div class="relative pt-1">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-semibold text-blue-600 dark:text-blue-400" x-text="progress + '%'"></div>
                                </div>
                                <div class="flex w-full bg-gray-200 dark:bg-gray-700 rounded-full">
                                    <div class="bg-blue-600 dark:bg-blue-400 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                                         x-bind:style="'width: ' + progress + '%'"
                                         x-text="progress + '%'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-text-input type="file" wire:model="iconImage" accept="image/*" class="mb-4"></x-text-input>
                    </form>
                </div>
            </div>
            <center>
                <button wire:loading.remove wire:target="photo" wire:click="updateImage"  type="button" class="px-4 text-center py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @lang('Update Image')</button>
            </center>
        </div>
    </main>

    @script
    <script>
        Alpine.data('setting', () => ({
            init() {
                $wire.on('dataAdded', (e) => {
                    this.isOpen = false
                    this.editMode = false
                    element = document.getElementById(e.dataId)
                    if (element) {
                        console.log(element)
                        element.scrollIntoView({ behavior: 'smooth' });
                        $nextTick(() => {
                            element.classList.add('animate-pulse');
                        });
                    }
                    setTimeout(() => {
                        element.classList.remove('animate-pulse');
                    }, 5000)
                })
            },
            isOpen: false,
            editMode: false,
            tab: $persist('general'),

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
