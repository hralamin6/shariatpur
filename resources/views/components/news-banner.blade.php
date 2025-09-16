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
            {{--
                Redesigned Swiper slide with a "news site" style caption.
                - Title is moved to the bottom for better readability and aesthetics.
                - A gradient overlay ensures text is always legible.
                - Uses dark classes as per user preference.
            --}}
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide pointer-events-auto">
                        @php $caption = $slide['title'] ?? $slide['alt'] ?? 'News'; @endphp

                        {{-- Clickable link, allow drag on slide --}}
                        @if(!empty($slide['slug']))
                            <a wire:navigate href="{{ route('web.news.details', $slide['slug']) }}" aria-label="{{ $caption }}" class="block group pointer-events-auto cursor-pointer select-none">
                                <div class="relative rounded-lg overflow-hidden shadow-lg">
                                    <img src="{{ $slide['src'] }}" alt="{{ $caption }}" class="w-full lg:h-96 md:h-72 h-40 object-cover transition-transform duration-300 group-hover:scale-105">

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/40 to-transparent pointer-events-none"></div>

                                    <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-5 md:p-6 pointer-events-none">
                                        <h3 class="text-white font-bold md:text-lg lg:text-2xl leading-tight drop-shadow-lg line-clamp-3">
                                            {{ $caption }}
                                        </h3>
                                    </div>
                                </div>
                            </a>
                        @else
                            {{-- Non-clickable version --}}
                            <div class="relative rounded-lg overflow-hidden shadow-lg">
                                <img src="{{ $slide['src'] }}" alt="{{ $caption }}" class="w-full lg:h-96 md:h-72 h-40 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/40 to-transparent pointer-events-none"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-5 md:p-6 pointer-events-none">
                                    <h3 class="text-white font-bold md:text-lg lg:text-2xl leading-tight drop-shadow-lg line-clamp-3">
                                        {{ $caption }}
                                    </h3>
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
                preventClicks: false,
                preventClicksPropagation: false,
                // Fine-tune drag vs click sensitivity
                threshold: 5,
                touchStartPreventDefault: false,
                touchMoveStopPropagation: true,
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
