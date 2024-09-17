@section('title', $page->title)
@section('description', Str::limit(strip_tags($page->content), 333))
<div class="container mx-auto px-4 py-8 ">
    <h1 class="text-4xl font-bold mb-4 text-gray-900 dark:text-gray-100 text-center">
        {{ $page->title }}
    </h1>
    <center>

    </center>
    <article class="mx-auto trix-content prose dark:prose-invert prose-h2:text-green-600 prose-h3:text-purple-500 prose-p:text-gray-700 dark:prose-p:text-gray-200 prose-a:text-blue-600 hover:prose-a:text-blue-400 prose-blockquote:bg-gray-100 dark:prose-blockquote:bg-gray-800 prose-blockquote:border-l-4 prose-blockquote:border-blue-600 prose-img:rounded-lg prose-img:shadow-lg prose-ul:list-disc prose-ul:pl-5 prose-ol:list-decimal prose-ol:pl-5 prose-li:text-gray-800 dark:prose-li:text-gray-300 prose-strong:text-red-600">
        {!! $page->content !!}    </article>
    <a href="{{route('app.pages')}}" wire:navigate class="m-8">adsf</a>
</div>
