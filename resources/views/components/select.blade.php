@props(['disabled' => false, 'errorName' => 'asdf'])

@php
    $errorClass = $errors->has($errorName) ? 'border-red-500 dark:border-red-400' : '';
@endphp

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $errorClass. 'px-2 text-center block placeholder-white w-full py-2 text-gray-700 bg-white border  rounded-md dark:bg-dark dark:text-gray-300 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring']) !!}>
    {{ $slot }}</select>
@error($errorName)<p class="text-sm text-red-500 font-medium">{{ $message }}</p>@enderror

