@props([
    'text' => 'Sponsored by',
    'title' => 'home',
    'banners' => [],
    'autoplay' => true,
    'interval' => 5000,
    'useSponsors' => true,
    'max' => 20,
])

<div class="mb-6" wire:ignore>
    @php
        $slides = collect($banners)->map(function ($banner, $i) use ($text) {
            if (is_string($banner)) {
                return ['src' => $banner, 'alt' => $text.' '.($i + 1)];
            }

            return [
                'src' => $banner['src'] ?? $banner['url'] ?? ($banner[0] ?? ''),
                'alt' => $banner['alt'] ?? ($text.' '.($i + 1)),
            ];
        })->filter(fn ($s) => ! empty($s['src']))->values()->all();

        // If no explicit banners passed, try loading from Sponsors (active and not expired)
        if (empty($slides) && $useSponsors) {
            $sponsors = \App\Models\Sponsor::query()
                ->where('status', 'active')
                ->where('title', $title)
                ->where(function ($q) {
                    $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
                })
                ->latest()
                ->take((int) $max)
                ->get();

            $slides = $sponsors->flatMap(function ($sponsor) use ($text, $title) {
                return collect($sponsor->getMedia('sponsorImages'))->map(function ($media, $k) use ($sponsor, $text, $title) {
                    // Prefer thumb conversion if available
                    $src = method_exists($media, 'getAvailableUrl')
                        ? $media->getAvailableUrl(['thumb'])
                        : ($media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getUrl());

                    return [
                        'src' => $src,
                        'alt' => trim(($sponsor->name ?? $text).' - '.($sponsor->title ?? $title)),
                    ];
                });
            })->filter(fn ($s) => ! empty($s['src']))->values()->all();
        }

//        if (empty($slides)) {
//            $slides = [
//                ['src' => "https://placehold.co/1200x400/6366f1/ffffff?text={$text}+1", 'alt' => 'Banner 1'],
//                ['src' => "https://placehold.co/1200x400/10b981/ffffff?text={$text}+2", 'alt' => 'Banner 2'],
//                ['src' => "https://placehold.co/1200x400/f59e0b/ffffff?text={$text}+3", 'alt' => 'Banner 3'],
//            ];
//        }
    @endphp

    <div class="relative max-w-fit mx-auto">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide">
                        <img src="{{ $slide['src'] }}" alt="{{ $slide['alt'] }}"
                             class="w-full lg:h-96 md:h-72 h-40 object-cover rounded-lg shadow-lg">
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
