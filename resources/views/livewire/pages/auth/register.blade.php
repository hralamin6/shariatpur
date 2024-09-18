<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('components.layouts.web');

state([
    'name' => '',
    'email' => '',
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('app.dashboard', absolute: false), navigate: true);
};

?>

<div class="bg-white dark:bg-darker shadow-lg rounded-lg p-6 max-w-md mx-auto space-y-6" x-data>
    <!-- Form Heading -->
    <h2 class="text-center text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ __('Register') }}</h2>

    <form wire:submit.prevent="register" class="space-y-4">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-gray-700 dark:text-gray-300"/>
            <x-text-input errorName="name" wire:model="name" id="name" class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg" type="text" name="name" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300"/>
            <x-text-input errorName="email" wire:model="email" id="email" class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg" type="email" name="email" required autocomplete="username"/>
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300"/>
            <x-text-input errorName="password" wire:model="password" id="password" class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg" type="password" name="password" required autocomplete="new-password"/>
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 dark:text-gray-300"/>
            <x-text-input errorName="password_confirmation" wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg" type="password" name="password_confirmation" required autocomplete="new-password"/>
        </div>

        <!-- Register Button -->
        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors duration-200" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="px-6 py-2 text-base font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg shadow-md transition-all duration-200 ms-4 dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:focus:ring-indigo-600">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Social Login Buttons -->
    <div class="flex flex-col space-y-3 mt-6">
        <a href="{{ route('socialite.auth', 'google') }}" class="flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
            <i class='bx bxl-google bx-tada text-2xl text-gray-500 dark:text-gray-300'></i>
            <span class="ml-2 text-gray-600 dark:text-gray-300">@lang('Login with Google')</span>
        </a>
        <a href="{{ route('socialite.auth', 'github') }}" class="flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
            <i class='bx bxl-github bx-tada text-2xl text-gray-500 dark:text-gray-300'></i>
            <span class="ml-2 text-gray-600 dark:text-gray-300">@lang('Login with GitHub')</span>
        </a>
    </div>
</div>

