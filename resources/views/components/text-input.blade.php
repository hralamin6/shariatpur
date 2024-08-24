@props(['disabled' => false, 'errorName'=>null])

@if($errors->has($errorName  ))
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => "text-red-400 border border-red-500 focus:border-red-500 block rounded-md focus:outline-none w-full mt-1 text-sm  dark:bg-darkBg dark:text-gray-300 form-input"]) !!}>
    @error($errorName)<p class="text-sm text-red-500 font-medium">{{ $message }}</p>@enderror

@else

    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => "dark:bg-dark dark:text-gray-300 focus:border-purple-400 block rounded-md focus:outline-none w-full mt-1 text-sm  dark:bg-darkBg form-input"]) !!}>
@endif
