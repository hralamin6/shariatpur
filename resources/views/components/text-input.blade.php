@props(['disabled' => false, 'error'])

@if($errors->has($error))
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => "text-red-400 border border-red-500 focus:border-red-500 block  focus:outline-none w-full mt-1 text-sm  dark:bg-gray-700 form-input"]) !!}>
@else

    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => "dark:text-gray-300 dark:border-gray-600 focus:border-purple-400 block  focus:outline-none w-full mt-1 text-sm  dark:bg-gray-700 form-input"]) !!}>
@endif
