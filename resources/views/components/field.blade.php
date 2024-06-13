@props(['field' => 'id', 'OB'=>'', 'OD' => ''])
<th {{ $attributes }} wire:click.prevent="orderByDirection('{{$field}}')" class="cursor-pointer px-4 py-3 capitalize text-sm font-normal dark:text-gray-400"> {{$slot}}
    <span class="text-xs text-purple-400">{{$OB==$field?'('.$OD.')':''}}</span>
</th>
