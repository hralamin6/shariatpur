@props([
    'target',
])
<button {{ $attributes->merge([ 'wire:loading.attr' => 'disabled', 'wire:target' => $target ]) }}>
    {{ $slot }}
    <x-loader :target="$target" />
</button>

