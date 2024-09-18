<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('components.layouts.web');

state('token')->locked();

state([
    'email' => fn () => request()->string('email')->value(),
    'password' => '',
    'password_confirmation' => ''
]);

rules([
    'token' => ['required'],
    'email' => ['required', 'string', 'email'],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$resetPassword = function () {
    $this->validate();

    // Here we will attempt to reset the user's password. If it is successful we
    // will update the password on an actual user model and persist it to the
    // database. Otherwise we will parse the error and return the response.
    $status = Password::reset(
        $this->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) {
            $user->forceFill([
                'password' => Hash::make($this->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    // If the password was successfully reset, we will redirect the user back to
    // the application's home authenticated view. If there is an error we can
    // redirect them back to where they came from with their error message.
    if ($status != Password::PASSWORD_RESET) {
        $this->addError('email', __($status));

        return;
    }

    Session::flash('status', __($status));

    $this->redirectRoute('login', navigate: true);
};

?>

<div class="bg-white dark:bg-darker shadow-lg rounded-lg p-6 max-w-md mx-auto space-y-6">
    <!-- Form Heading -->
    <h2 class="text-center text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ __('Reset Password') }}</h2>

    <form wire:submit.prevent="resetPassword" class="space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input wire:model="email"
                          errorName="email"
                          id="email"
                          class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg"
                          type="email"
                          name="email"
                          required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input wire:model="password"
                          errorName="password"
                          id="password"
                          class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input wire:model="password_confirmation"
                          errorName="password_confirmation"
                          id="password_confirmation"
                          class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg"
                          type="password"
                          name="password_confirmation"
                          required autocomplete="new-password" />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-4">
            <x-primary-button class="px-6 py-2 text-base font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg shadow-md transition-all duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:focus:ring-indigo-600">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</div>

