<div  class="m-0 md:m-2 capitalize" x-data="profile()">
{{--    <div class="flex justify-between gap-4 mb-2 capitalize">--}}
{{--        <p class="text-gray-700 dark:text-gray-200 text-xl font-semibold">@lang("user profile")</p>--}}
{{--        <div class="flex text-sm gap-1">--}}
{{--            <a href="{{route('app.dashboard')}}" wire:navigate class="text-blue-500 dark:text-blue-400">@lang("dashboard")</a>--}}
{{--            <span class="text-gray-500 dark:text-gray-200">/</span>--}}
{{--            <span class="text-gray-500 dark:text-gray-300">@lang("users")</span>--}}
{{--        </div>--}}
{{--    </div>--}}
    <main class="h-full capitalize">
        <div class="max-w-4xl mx-auto mb-6">
            <!-- Tabs Container -->
            <div class="flex justify-center space-x-4 bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden overflow-x-auto">
                <!-- Profile Tab -->
                <button
                    @click="activeTab = 'profile'"
                    :class="{'bg-primary text-white': activeTab === 'profile', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': activeTab !== 'profile'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('Profile')
                </button>
                <!-- Update Profile Tab -->
                <button
                    @click="activeTab = 'updateProfile'"
                    :class="{'bg-primary text-white': activeTab === 'updateProfile', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': activeTab !== 'updateProfile'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('Update Profile')
                </button>
                <!-- Change Password Tab -->
                <button
                    @click="activeTab = 'changePassword'"
                    :class="{'bg-primary text-white': activeTab === 'changePassword', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': activeTab !== 'changePassword'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('Change Password')
                </button>
                <!-- Update Photo Tab -->
                <button
                    @click="activeTab = 'updatePhoto'"
                    :class="{'bg-primary text-white': activeTab === 'updatePhoto', 'text-gray-500 hover:text-blue-500 dark:text-gray-300': activeTab !== 'updatePhoto'}"
                    class="px-4 py-2 rounded-lg transition-colors duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary">
                    @lang('Update Photo')
                </button>
            </div>
        </div>

        <div x-show="activeTab === 'profile'" class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-lg border border-slate-300 dark:border-slate-600 rounded-xl overflow-hidden">
            <!-- Profile Header -->
            <div class="flex flex-col items-center p-6 bg-white dark:bg-gray-800">
                <!-- Profile Avatar -->
                <img src="{{ getUserProfileImage($item) }}" alt="Profile Avatar"  onerror="{{getErrorProfile($item)}}"
                     class="w-32 h-32 rounded-full border-4 border-white dark:border-darker shadow-lg mb-4">
                <!-- Profile Information -->
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $item->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">@lang("role"): {{ $item->role->name }}</p>
                    @if($item->email_verified_at!=null)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-200 rounded-full dark:bg-green-700 dark:text-green-100">@lang("verified")</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-200 rounded-full dark:bg-red-700 dark:text-red-100">@lang("pending")</span>
                    @endif
                </div>
            </div>

            <!-- Profile Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Details -->
                    <div class="bg-white border border-slate-300 dark:border-slate-600 dark:bg-dark shadow-lg rounded-lg p-4">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">@lang("Contact Information")</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <p class="w-1/4 text-sm font-semibold text-gray-700 dark:text-gray-300">@lang("Email"):</p>
                                <p class="w-3/4 text-sm text-gray-800 dark:text-gray-200">{{$item->email}}</p>
                            </div>
                            <div class="flex items-center">
                                <p class="w-1/4 text-sm font-semibold text-gray-700 dark:text-gray-300">@lang("phone"):</p>
                                <p class="w-3/4 text-sm text-gray-800 dark:text-gray-200">{{ $item->phone }}</p>
                            </div>
                            <div class="flex items-center">
                                <p class="w-1/4 text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('address'):</p>
                                <p class="w-3/4 text-sm text-gray-800 dark:text-gray-200">{{ $item->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bio and Status -->
                    <div class="bg-white border border-slate-300 dark:border-slate-600 dark:bg-dark shadow-lg rounded-lg p-4">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">@lang('Additional Information')</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <p class="w-1/4 text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('bio'):</p>
                                <p class="w-3/4 text-sm text-gray-800 dark:text-gray-200">{{ $item->bio }}</p>
                            </div>
                            <div class="flex items-center">
                                <p class="w-1/4 text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('status'):</p>
                                <p class="w-3/4 text-sm text-gray-800 dark:text-gray-200">{{ $item->status }}</p>
                            </div>
                            <div class="flex items-center">
                                <p class="w-1/4 text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('Last Seen'):</p>
                                <p class="w-3/4 text-sm text-gray-800 dark:text-gray-200">{{\Carbon\Carbon::parse($item->last_seen)->diffForHumans()}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div x-show="activeTab === 'updateProfile'" class="max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-gray-800 shadow-lg rounded-lg p-4">
            <h3 class="text-xl text-center font-semibold mb-4 text-gray-800 dark:text-gray-100">@lang('Update Profile Information')</h3>
            <form wire:submit.prevent="updateProfile" class="space-y-4">
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
                    <label class="text-gray-700 dark:text-gray-200" for="bio">@lang('bio')</label>
                    <x-text-input errorName="bio" wire:model="bio" type="text"/>
                </div>
                <div class="text-center">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">@lang('Update Profile')</button>
                </div>
            </form>
        </div>

        <div x-show="activeTab === 'changePassword'" class="max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-gray-800 shadow-lg rounded-lg p-4">
            <h3 class="text-xl  text-center text-center font-semibold mb-4 text-gray-800 dark:text-gray-100">@lang('Change Password')</h3>
            <form wire:submit.prevent="updatePassword" class="space-y-4 ">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="input">@lang('Current Password')</label>
                    <x-text-input errorName="currentPassword"  id="currentPassword" wire:model="currentPassword" type="password"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="input">@lang('New Password')</label>
                    <x-text-input errorName="newPassword"  id="newPassword" wire:model="newPassword" type="password"/>
                </div>
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="input">@lang('Confirm New Password')</label>
                    <x-text-input errorName="newPasswordConfirmation"  id="newPasswordConfirmation" wire:model="newPasswordConfirmation" type="password"/>
                </div>
                <div class="text-center">
                    <button type="submit" class="px-4  py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">@lang('Update Password')</button>
                </div>
            </form>
            @if (session()->has('message'))
                <div class="mt-4 text-green-600">{{ session('message') }}</div>
            @endif
        </div>


        <div x-show="activeTab === 'updatePhoto'" class="max-w-4xl mx-auto my-2 bg-white border border-slate-300 dark:border-slate-600 dark:bg-gray-800 shadow-lg rounded-lg p-4">
            <h3 class="text-xl text-center font-semibold mb-4 text-gray-800 dark:text-gray-100 items-center">@lang('Update Profile Photo')</h3>

            {{--            @foreach(auth()->user()->getMedia('profile') as $k => $media)--}}
            {{--                <div><img style="height: 55px; width: 66px;" src="{{$media->getAvailableUrl(['thumb'])}}" onerror="this.onerror=null;this.src='https://picsum.photos/id/10/600/300';"></div>--}}
            {{--            @endforeach--}}
            <center>
                @if($photo)
                    <img src="{{ $photo->temporaryUrl() }}" alt="icon Preview" class="mt-2 w-32 h-32 object-cover"/>
                @else
                    <img src="{{ getUserProfileImage($item) }}" alt="Profile Avatar"  onerror="{{getErrorProfile($item)}}"
                         class="w-32 h-32 rounded-full border-4 border-white dark:border-darker shadow-lg mb-4">
                @endif
            </center>
            <div>
                <x-text-input placeholder="image link" errorName="image_url"  id="image_url" wire:model="image_url" type="url"/>
            </div>
            <form wire:submit.prevent="updatePhoto" class="flex flex-col items-center"
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
                <x-text-input type="file" wire:model="photo" accept="image/*" class="mb-4"></x-text-input>

                <button wire:loading.remove wire:target="photo" type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">@lang('Upload Photo')</button>

            </form>
        </div>



    </main>

    @script
    <script>
        Alpine.data('profile', () => ({
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
            activeTab: $persist('profile'),
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
