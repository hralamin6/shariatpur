<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('components.layouts.web');

state(['password' => '']);

rules(['password' => ['required', 'string']]);

$confirmPassword = function () {
    $this->validate();

    if (! Auth::guard('web')->validate([
        'email' => Auth::user()->email,
        'password' => $this->password,
    ])) {
        throw ValidationException::withMessages([
            'password' => __('auth.password'),
        ]);
    }

    session(['auth.password_confirmed_at' => time()]);

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div class="bg-white dark:bg-darker shadow-lg rounded-lg p-6 max-w-md mx-auto space-y-6">
    <!-- Instruction Text -->
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form wire:submit.prevent="confirmPassword" class="space-y-4">
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 dark:text-gray-300"/>

            <x-text-input wire:model="password"
                          errorName="password"
                          id="password"
                          class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-4">
            <x-primary-button class="px-6 py-2 text-base font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg shadow-md transition-all duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:focus:ring-indigo-600">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</div>

