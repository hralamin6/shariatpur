@props([
    'text' => 'Top News',
    'title' => 'News',
    'banners' => [],
    'autoplay' => true,
    'interval' => 5000,
    'limit' => 10,
    'pinnedOnly' => true,
    'categoryId' => null,
])

<div class="mb-6" wire:ignore>
    @php
        // Normalize explicit banners if provided
        $slides = collect($banners)->map(function ($banner, $i) use ($text) {
            if (is_string($banner)) {
                return ['src' => $banner, 'alt' => $text.' '.($i + 1)];
            }
            return [
                'src' => $banner['src'] ?? $banner['url'] ?? ($banner[0] ?? ''),
                'alt' => $banner['alt'] ?? ($text.' '.($i + 1)),
                'slug' => $banner['slug'] ?? null,
                'title' => $banner['title'] ?? ($banner['alt'] ?? null),
            ];
        })->filter(fn ($s) => ! empty($s['src']))->values()->all();

        // When no explicit banners provided, load from latest pinned news
        if (empty($slides)) {
            $query = \App\Models\News::query()->where('status', 'active');
            if ($pinnedOnly) { $query->where('is_pinned', true); }
            if (!empty($categoryId)) { $query->where('news_category_id', (int) $categoryId); }
            $newsItems = $query->latest()->take((int) $limit)->get();

            $slides = $newsItems->map(function ($n) use ($title) {
                $img = '';
                if (method_exists($n, 'getFirstMediaUrl')) {
                    $img = $n->getFirstMediaUrl('news', 'avatar') ?: $n->getFirstMediaUrl('news') ?: '';
                }
                return [
                    'src' => $img,
                    'alt' => $n->title ?: $title,
                    'slug' => $n->slug,
                    'title' => $n->title,
                ];
            })->filter(fn ($s) => ! empty($s['src']))->values()->all();
        }

        if (empty($slides)) {
            $slides = [
                ['src' => "https://placehold.co/1200x400/6366f1/ffffff?text={$text}+1", 'alt' => 'Banner 1', 'title' => $text.' 1'],
                ['src' => "https://placehold.co/1200x400/10b981/ffffff?text={$text}+2", 'alt' => 'Banner 2', 'title' => $text.' 2'],
                ['src' => "https://placehold.co/1200x400/f59e0b/ffffff?text={$text}+3", 'alt' => 'Banner 3', 'title' => $text.' 3'],
            ];
        }
    @endphp

    <div class="relative max-w-fit mx-auto">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide">
                        @php $caption = $slide['title'] ?? $slide['alt'] ?? 'News'; @endphp
                        @if(!empty($slide['slug']))
                            <a wire:navigate href="{{ route('web.news.details', $slide['slug']) }}" aria-label="{{ $caption }}" class="block">
                                <div class="relative rounded-lg overflow-hidden shadow-lg">
                                    <img src="{{ $slide['src'] }}" alt="{{ $caption }}" class="w-full lg:h-96 md:h-72 h-40 object-cover">
                                    <div class="absolute inset-0 flex items-center justify-center p-3 sm:p-4 md:p-5">
                                        <div class="bg-black/50 dark:bg-black/60 px-3 sm:px-4 py-2 sm:py-3 rounded-md shadow-lg backdrop-blur-sm">
                                            <h3 class="text-white font-semibold text-center drop-shadow-md text-sm sm:text-base md:text-lg leading-snug line-clamp-3">{{ $caption }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="relative rounded-lg overflow-hidden shadow-lg">
                                <img src="{{ $slide['src'] }}" alt="{{ $caption }}" class="w-full lg:h-96 md:h-72 h-40 object-cover">
                                <div class="absolute inset-0 flex items-center justify-center p-3 sm:p-4 md:p-5">
                                    <div class="bg-black/50 dark:bg-black/60 px-3 sm:px-4 py-2 sm:py-3 rounded-md shadow-lg backdrop-blur-sm">
                                        <h3 class="text-white font-semibold text-center drop-shadow-md text-sm sm:text-base md:text-lg leading-snug line-clamp-3">{{ $caption }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Navigation buttons -->
            <div class="swiper-button-prev hidden"></div>
            <div class="swiper-button-next hidden"></div>
        </div>
    </div>
</div>

@push('head')
    {{--    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">--}}
@endpush

@push('head')
    {{--    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>--}}
    <script>
        function initializeSwiper() {
            // destroy old instance if exists
            if (window.swiperInstance) {
                window.swiperInstance.destroy(true, true);
            }

            window.swiperInstance = new Swiper('.mySwiper', {
                loop: true,
                autoplay: {!! $autoplay ? '{ delay: '.(int) $interval.', disableOnInteraction: false }' : 'false' !!},
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                slidesPerView: 1,
                spaceBetween: 20,
                grabCursor: true,
            });
        }

        document.addEventListener("DOMContentLoaded", initializeSwiper);

        // when Livewire updates DOM
        document.addEventListener("livewire:navigated", initializeSwiper);
        Livewire.hook('message.processed', () => {
            initializeSwiper();
        });
    </script>
@endpush
