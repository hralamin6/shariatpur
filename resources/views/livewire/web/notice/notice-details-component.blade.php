
<div class="mx-auto">
    <x-sponsor text="notice details"/>
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $notice->title ?? 'Untitled notice' }}
        </h1>

        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex flex-wrap items-center gap-2">
            <span class="font-medium">{{ data_get($notice, 'user.name', 'Unknown') }}</span>
            <span>•</span>
            <span>{{ optional(data_get($notice, 'created_at'))->toDayDateTimeString() }}</span>
        </div>

        <div class="prose dark:prose-invert max-w-none mt-6">
            {!! nl2br(e(data_get($notice, 'content', ''))) !!}
        </div>

        <div wire:loading class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Loading…
        </div>
    </div>

</div>
