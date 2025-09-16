@props([
    'wireClick' => null
])

@auth
    <button wire:click="{{$wireClick}}" class="fixed bottom-16 right-2 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Add Notice">
        <i class='bx bx-plus text-3xl'></i>
    </button>
@endauth
@guest
    <a href="{{ route('login') }}" class="fixed bottom-16 right-2 h-14 w-14 bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-indigo-600 transition z-30" aria-label="Login to add notice">
        <i class='bx bx-log-in text-3xl'></i>
    </a>
@endguest
