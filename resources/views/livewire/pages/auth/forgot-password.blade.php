<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('components.layouts.web');

state(['email' => '']);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {
    $this->validate();

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.
    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    Session::flash('status', __($status));
};

?>

<div class="bg-white dark:bg-darker shadow-lg rounded-lg p-6 max-w-md mx-auto space-y-6">
    <!-- Instructions -->
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit.prevent="sendPasswordResetLink" class="space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input errorName="email" wire:model="email" id="email" class="block mt-1 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm px-4 py-2 bg-gray-100 dark:bg-darkBg focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-darkBg" type="email" name="email" required autofocus />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="px-6 py-2 text-base font-semibold bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg shadow-md transition-all duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:focus:ring-indigo-600">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</div>
