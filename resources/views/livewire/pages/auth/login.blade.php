<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);
$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('app.dashboard', absolute: false), navigate: true);
};

?>

<div x-data>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" >
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <div class="flex justify-between space-x-2">
        <x-secondary-button
            @click="$wire.form.email='admin@mail.com', $wire.form.password='000000', $wire.login()"
            class="capitalize mx-auto my-2 text-base">
            @lang('admin')
        </x-secondary-button>
        <x-secondary-button
            @click="$wire.form.email='user@mail.com', $wire.form.password='000000', $wire.login()"
            class="capitalize mx-auto my-2 text-base">
            @lang('user')
        </x-secondary-button>

    </div>
    <a href="{{ route('socialite.auth', 'google') }}" class="flex items-center justify-center px-4 py-2 space-x-2 text-white transition-all duration-200 bg-black rounded-md hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-1 dark:focus:ring-offset-darker"
    >
        <i class='bx bxl-google bx-tada text-2xl text-white' ></i>
        <span> Login with google </span>
    </a>
    <a href="{{ route('socialite.auth', 'github') }}" class="flex items-center justify-center px-4 py-2 space-x-2 text-white transition-all duration-200 bg-black rounded-md hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-1 dark:focus:ring-offset-darker"
    >
        <svg
            aria-hidden="true"
            class="w-6 h-6 text-white"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="currentColor"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M12.026,2c-5.509,0-9.974,4.465-9.974,9.974c0,4.406,2.857,8.145,6.821,9.465 c0.499,0.09,0.679-0.217,0.679-0.481c0-0.237-0.008-0.865-0.011-1.696c-2.775,0.602-3.361-1.338-3.361-1.338 c-0.452-1.152-1.107-1.459-1.107-1.459c-0.905-0.619,0.069-0.605,0.069-0.605c1.002,0.07,1.527,1.028,1.527,1.028 c0.89,1.524,2.336,1.084,2.902,0.829c0.091-0.645,0.351-1.085,0.635-1.334c-2.214-0.251-4.542-1.107-4.542-4.93 c0-1.087,0.389-1.979,1.024-2.675c-0.101-0.253-0.446-1.268,0.099-2.64c0,0,0.837-0.269,2.742,1.021 c0.798-0.221,1.649-0.332,2.496-0.336c0.849,0.004,1.701,0.115,2.496,0.336c1.906-1.291,2.742-1.021,2.742-1.021 c0.545,1.372,0.203,2.387,0.099,2.64c0.64,0.696,1.024,1.587,1.024,2.675c0,3.833-2.33,4.675-4.552,4.922 c0.355,0.308,0.675,0.916,0.675,1.846c0,1.334-0.012,2.41-0.012,2.737c0,0.267,0.178,0.577,0.687,0.479 C19.146,20.115,22,16.379,22,11.974C22,6.465,17.535,2,12.026,2z"
            ></path>
        </svg>
        <span> Login with github </span>
    </a>

</div>
