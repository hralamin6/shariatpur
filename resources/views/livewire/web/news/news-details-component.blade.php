@section('title', $news->title)
@section('description', str(strip_tags($news->content))->limit(333))
@section('image', $news->getFirstMediaUrl('news', 'avatar'))
<div class="max-w-5xl mx-auto">
    <article class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        <header class="relative">
            @if(!empty($photoUrl))
                <img src="{{ $photoUrl }}" onerror="{{ getErrorImage() }}" alt="{{ $news->title }}" class="w-full h-64 sm:h-80 md:h-96 object-cover">
            @else
                <div class="w-full h-48 sm:h-64 md:h-80 bg-gradient-to-r from-sky-100 to-indigo-100 dark:from-sky-900/30 dark:to-indigo-900/30 flex items-center justify-center">
                    <i class='bx bxs-news text-6xl text-primary'></i>
                </div>
            @endif
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                <div class="text-white text-sm font-medium flex items-center justify-between">
                    <span>{{ $news->category?->name }} • {{ optional($news->created_at)->format('d M Y') }}</span>
                    <div class="flex items-center gap-3">
                        <div class="inline-flex items-center gap-1 text-white/90">
                            <i class='bx bx-show'></i>
                            <span class="text-xs">{{ $viewsCount ?? 0 }}</span>
                        </div>
                        <button type="button" wire:click="toggleLike" class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs">
                            <i class='{{ $liked ? 'bx bxs-heart' : 'bx bx-heart' }}'></i>
                            <span>{{ $likesCount ?? 0 }}</span>
                        </button>
                        <div x-data="{open:false}" class="relative">
                            <button @click="open=!open" @keydown.escape.window="open=false" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-white/30 text-xs hover:bg-white/10" aria-haspopup="true" :aria-expanded="open ? 'true' : 'false'">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 8a3 3 0 1 0-6 0 3 3 0 0 0 6 0zM18 20a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM6 20a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                                <span>Share</span>
                            </button>
                            <div x-cloak x-show="open" x-transition.opacity @click.outside="open=false" class="absolute right-0 mt-2 w-60 p-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg z-20">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="px-2 py-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-600 dark:text-blue-400 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Facebook
                                    </a>
                                    <a href="https://x.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener" class="px-2 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> X
                                    </a>
                                    <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="px-2 py-2 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> WhatsApp
                                    </a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="px-2 py-2 rounded-lg hover:bg-sky-50 dark:hover:bg-sky-900/20 text-sky-600 dark:text-sky-400 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> LinkedIn
                                    </a>
                                    <a href="https://www.reddit.com/submit?url={{ $shareUrl }}&title={{ $shareText }}" target="_blank" rel="noopener" class="px-2 py-2 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/20 text-orange-600 dark:text-orange-400 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Reddit
                                    </a>
                                    <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener" class="px-2 py-2 rounded-lg hover:bg-cyan-50 dark:hover:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> Telegram
                                    </a>
                                    <a href="mailto:?subject={{ $shareText }}&body={{ $shareText }}%0A%0A{{ $shareUrl }}" class="px-2 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Email
                                    </a>
                                    <a href="https://www.pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareText }}" target="_blank" rel="noopener" class="px-2 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 inline-flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Pinterest
                                    </a>
                                    <button type="button"
                                            @click="
                                                const title = decodeURIComponent('{{ $shareText }}');
                                                const url = decodeURIComponent('{{ $shareUrl }}');
                                                if (navigator.share) {
                                                    navigator.share({ title, text: title, url }).catch(()=>{});
                                                } else {
                                                    alert('Sharing not supported on this browser.');
                                                }
                                            "
                                            class="px-2 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 inline-flex items-center gap-2 col-span-2 sm:col-span-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Device share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h1 class="mt-1 text-2xl sm:text-3xl md:text-4xl font-extrabold text-white">{{ $news->title }}</h1>
            </div>
        </header>

        <div class="p-6 sm:p-8">
            <div class="prose dark:prose-invert max-w-none">
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $news->content }}</p>
            </div>

            <div class="mt-6 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                <div>
                    @if($news->user)
                        <span>@lang('Author'):</span>
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $news->user->name }}</span>
                    @endif
                    @if($news->upazila)
                        <span class="ml-3">@lang('Upazila'):</span>
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $news->upazila->name }}</span>
                    @endif
                </div>
                <a wire:navigate href="{{ route('web.news', $news->news_category_id) }}" class="text-primary hover:underline">@lang('Back to') {{ $news->category?->name }}</a>
            </div>

            <section class="mt-10">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">@lang('Comments') ({{ $comments->count() }})</h2>
                <div class="mt-4">
                    @auth
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                            <label for="commentText" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Add a comment')</label>
                            <textarea id="commentText" rows="3" wire:model.defer="commentText" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-primary focus:ring-primary" placeholder="@lang('Write your comment here...')"></textarea>
                            @error('commentText')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            <div class="mt-3 flex items-center justify-end">
                                <button type="button" wire:click="addComment" wire:loading.attr="disabled" class="px-4 py-2 rounded-md bg-primary text-white hover:bg-primary/90 shadow">
                                    <span wire:loading.remove>@lang('Post Comment')</span>
                                    <span wire:loading>@lang('Posting...')</span>
                                </button>
                            </div>
                        </div>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-primary hover:underline text-sm">
                            <i class='bx bx-log-in'></i>
                            @lang('Log in to comment')
                        </a>
                    @endguest
                </div>

                <div class="mt-6 space-y-4">
                    @forelse($comments as $c)
                        <div wire:key="ncomment-{{ $c->id }}" class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-semibold">
                                        {{ strtoupper(mb_substr($c->user?->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $c->user?->name ?? 'User' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ optional($c->created_at)->diffForHumans() }}</div>
                                    </div>
                                </div>
                                @php
                                    $isAdmin = optional(auth()->user()?->role)->slug === 'admin';
                                    $isNewsOwner = auth()->check() && $news->user_id === auth()->id();
                                    $isCommentOwner = auth()->check() && $c->user_id === auth()->id();
                                @endphp
                                @if($isAdmin || $isNewsOwner || $isCommentOwner)
                                    <button type="button" class="px-2 py-1 text-xs rounded bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-300" wire:click="deleteComment({{ $c->id }})">
                                        @lang('Delete')
                                    </button>
                                @endif
                            </div>
                            <p class="mt-3 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $c->body }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">@lang('No comments yet. Be the first to comment!')</p>
                    @endforelse
                </div>
            </section>
        </div>
    </article>

    @if(($related ?? null) && $related->count())
        <section class="mt-8">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">@lang('Related News')</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($related as $item)
                    <a wire:navigate href="{{ route('web.news.details', $item->slug) }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition">
                        <div class="h-36 w-full bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center overflow-hidden">
                            @php $img = method_exists($item,'getFirstMediaUrl') ? $item->getFirstMediaUrl('news','avatar') : null; @endphp
                            @if($img)
                                <img src="{{ $img }}" onerror="{{ getErrorImage() }}" alt="{{ $item->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <i class='bx bxs-news text-4xl text-sky-500'></i>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $item->category?->name }}</div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200 line-clamp-2">{{ $item->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

