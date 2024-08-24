@props(['field' => 'id', 'OB'=>'', 'OD' => ''])
<th {{ $attributes }} @if($field!='id') wire:click.prevent="orderByDirection('{{$field}}')" @endif class="min-w-40 @if($field!='id') cursor-pointer @endif px-4 py-3 capitalize text-sm font-normal dark:text-gray-400">
    <div class="relative">
        {{$slot}}
{{--        <span class="text-xs text-purple-400">{{$OB==$field?'('.$OD.')':''}}</span>--}}
        @if($field!='id')
            <i class='bx bx-up-arrow-alt absolute right-0 top-1 text-md {{$OB==$field&$OD=='desc'?'opacity-100':'opacity-50'}}'></i>
            <i class='bx bx-down-arrow-alt absolute right-2  top-1 text-md {{$OB==$field&$OD=='asc'?'opacity-100':'opacity-50'}}'></i>
        @endif
    </div>

</th>
