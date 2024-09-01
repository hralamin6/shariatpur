<!-- resources/views/components/page.blade.php -->
<div class="container mx-auto px-4 py-8">
    <!-- Title Section -->
    <h1 class="text-4xl font-bold mb-4 text-gray-900 dark:text-gray-100">
        {{ $page->title }}
    </h1>
    <article class="trix-content prose dark:prose-invert prose-h1:text-red-500 dark:prose-h1:text-red-200 ">{!! $page->content !!}</article>

    <a href="{{route('app.pages')}}" wire:navigate class="m-8">adsf</a>
    {{$page->content}}
</div>
