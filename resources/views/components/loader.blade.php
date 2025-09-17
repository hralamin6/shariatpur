@props([
    'target' => null,
])
<span wire:loading wire:target="{{$target}}" class="w-3 h-3  border border-t-transparent rounded-full animate-spin"></span>
