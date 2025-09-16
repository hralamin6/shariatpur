<div class="max-w-7xl mx-auto">
    <!-- Notice / Latest News Ticker -->
    <div x-data="{
items: @js(collect($headlines ?? [])->map(fn ($h) => ['title' => $h['title'], 'url' => route('web.notice.details', $h['id'])])->all()),
        i: 0,
        paused: false,
        autoId: null,
        touchStartX: 0,
        startAuto() {
            if (this.autoId || this.items.length < 2 || this.paused) return;
            this.autoId = setInterval(() => { this.next(); }, 3500);
        },
        stopAuto() {
            if (this.autoId) { clearInterval(this.autoId); this.autoId = null; }
        },
        next() { this.i = (this.i + 1) % this.items.length; },
        prev() { this.i = (this.i - 1 + this.items.length) % this.items.length; },
        handleSwipe(e) {
            const dx = e.changedTouches[0].clientX - this.touchStartX;
            if (Math.abs(dx) > 30) { dx < 0 ? this.next() : this.prev(); }
        },
        init() {
            this.startAuto();
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) { this.stopAuto(); } else { this.startAuto(); }
            });
        }
    }"
         @mouseenter="stopAuto()"
         @mouseleave="startAuto()"
         @focusin="stopAuto()"
         @focusout="startAuto()"
         @keydown.left.prevent="prev()"
         @keydown.right.prevent="next()"
         @touchstart.passive="touchStartX = $event.changedTouches[0].clientX"
         @touchend.passive="handleSwipe($event)"
         tabindex="0"
         role="region"
         aria-label="Latest updates ticker"
         class="mb-4">
        <div class="flex items-center px-3 py-2 sm:py-2.5 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
{{--            <span class="mr-2 sm:mr-3 inline-flex items-center px-2 py-0.5 text-[10px] sm:text-xs font-semibold rounded bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">--}}
{{--                <i class="bx bx-news mr-1"></i> Latest--}}
{{--            </span>--}}
            <div class="relative h-8 sm:h-9 md:h-10 overflow-hidden flex-1">
                <template x-for="(item, idx) in items" :key="idx">
                    <div x-show="i === idx"
                         x-transition:enter="transition transform ease-out duration-300"
                         x-transition:enter-start="translate-y-3 opacity-0"
                         x-transition:enter-end="translate-y-0 opacity-100"
                         x-transition:leave="transition transform ease-in duration-200"
                         x-transition:leave-start="translate-y-0 opacity-100"
                         x-transition:leave-end="-translate-y-3 opacity-0"
                         class="absolute inset-0 flex items-center">
                        <a wire:navigate :href="item.url" class="text-xs sm:text-sm md:text-base text-gray-700 dark:text-gray-300" x-text="item.title" aria-live="polite"></a>
                    </div>
                </template>
            </div>
            <div class="ml-2 flex items-center">
                <button type="button" aria-label="Previous"
                        class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                        @click="prev()">
                    <i class="bx bx-chevron-left text-lg"></i>
                </button>
                <button type="button" :aria-label="paused ? 'Play' : 'Pause'"
                        class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                        @click="paused = !paused; paused ? stopAuto() : startAuto()">
                    <i :class="paused ? 'bx bx-play text-lg' : 'bx bx-pause text-lg'"></i>
                </button>
                <button type="button" aria-label="Next"
                        class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                        @click="next()">
                    <i class="bx bx-chevron-right text-lg"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Image Banner/Slider -->

    <x-news-banner title="home" title="test"/>

    <!-- Services Grid -->
    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
        <a wire:navigate href="{{route('web.doctor.categories')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-blue-500">
                <i class="bx bxs-user-plus"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Doctors')</span>
        </a>
        <a wire:navigate href="{{route('web.notices')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-indigo-600">
                <i class="bx bx-bell"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Notices')</span>
        </a>
        <a wire:navigate href="{{route('web.hospitals')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-green-500">
                <i class="bx bx-building"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Hospitals')</span>
        </a>
        <a wire:navigate href="{{route('web.bus.routes')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-green-500">
                <i class="bx bx-bus"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Buses Schedule')</span>
        </a>
        <a wire:navigate href="{{route('web.train.routes')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-indigo-500">
                <i class="bx bx-train"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Trains Schedule')</span>
        </a>
        <a wire:navigate href="{{route('web.launch.routes')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-cyan-500">
                <i class="bx bxs-ship"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Launches Schedule')</span>
        </a>
        <a wire:navigate href="{{route('web.places')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-emerald-500">
                <i class="bx bx-map"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Travel Places')</span>
        </a>
        <a wire:navigate href="{{route('web.hotels')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-teal-500">
                <i class="bx bx-building-house"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Residential Hotels')</span>
        </a>
        <!-- Land (plots for sale) -->
        <a wire:navigate href="{{ route('web.lands') }}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-emerald-600">
                <i class="bx bx-map-alt"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Plot & Land')</span>
        </a>
        <a wire:navigate href="{{route('web.restaurants')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-orange-500">
                <i class="bx bx-restaurant"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Restaurants')</span>
        </a>
        <!-- New: Beauty Parlors -->
        <a wire:navigate href="{{route('web.beauty_parlors')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-pink-500">
                <i class="bx bx-female"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Beauty Parlors')</span>
        </a>
        <!-- New: Hotlines -->
        <a wire:navigate href="{{route('web.hotlines')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-rose-500">
                <i class="bx bxs-phone-call"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Hotlines & Links')</span>
        </a>
        <!-- New: Works -->
        <a wire:navigate href="{{route('web.works')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-emerald-500">
                <i class="bx bxs-briefcase-alt-2"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Find Jobs')</span>
        </a>
        <!-- New: Entrepreneurs -->
        <a wire:navigate href="{{route('web.entrepreneurs')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-sky-500">
                <i class="bx bx-user-pin"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Entrepreneurs')</span>
        </a>
        <a wire:navigate href="{{route('web.fire_services')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-red-500">
                <i class="bx bxs-hot"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Fire Services')</span>
        </a>
        <a wire:navigate href="{{route('web.electricity_offices')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-amber-500">
                <i class="bx bxs-bolt"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Electricity Offices')</span>
        </a>
        <a wire:navigate href="{{route('web.house.types')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-emerald-500">
                <i class="bx bxs-home-heart"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('House Rent')</span>
        </a>
        <!-- New: Servicemen -->
        <a wire:navigate href="{{route('web.serviceman.types')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-sky-500">
                <i class="bx bx-wrench"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Servicemen')</span>
        </a>
        <a wire:navigate href="{{route('web.car.types')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-amber-500">
                <i class="bx bxs-car"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Car Rent')</span>
        </a>
        <a wire:navigate href="{{route('web.sell.categories')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-amber-500">
                <i class="bx bxs-package"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Buy & Sell')</span>
        </a>
        <!-- New: Courier Services -->
        <a wire:navigate href="{{route('web.courier_services')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-amber-500">
                <i class="bx bxs-truck"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Courier Services')</span>
        </a>

        <!-- New: Blood Donors -->
        <a wire:navigate href="{{route('web.blood_donors')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-red-600">
                <i class="bx bxs-heart"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Blood Donors')</span>
        </a>

        <a wire:navigate href="{{route('web.diagnostic_centers')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-red-500">
                <i class="bx bxs-building-house"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Diagnostic Centers')</span>
        </a>
        <!-- New: Police -->
        <a wire:navigate href="{{route('web.police')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-indigo-500">
                <i class="bx bxs-shield-alt-2"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Police')</span>
        </a>
        <!-- New: Lawyers -->
        <a wire:navigate href="{{route('web.lawyers')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-indigo-500">
                <i class="bx bxs-briefcase"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Lawyers')</span>
        </a>
        <a wire:navigate href="{{route('web.institution.types')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-indigo-500">
                <i class="bx bx-library"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Institutions')</span>
        </a>
        <!-- New: Blogs -->
        <a wire:navigate href="{{route('web.blog.categories')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-blue-500">
                <i class="bx bxs-book"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Blogs')</span>
        </a>
        <!-- News -->
        <a wire:navigate href="{{route('web.news.categories')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-sky-500">
                <i class="bx bxs-news"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('News')</span>
        </a>
{{--        Tutor--}}
        <a wire:navigate href="{{route('web.tutors')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-yellow-500">
                <i class="bx bxs-graduation"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Tutors & Tution')</span>
        </a>
        <!-- Lost & Found -->
        <a wire:navigate href="{{route('web.lost_found')}}" class="flex flex-col items-center justify-center text-center p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-4xl mb-2 text-amber-500">
                <i class="bx bx-help-circle"></i>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Lost & Found')</span>
        </a>
    </div>

    <!-- Recent Blogs Section -->
    @if(($latestBlogs ?? null) && $latestBlogs->count())
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">@lang('Recent Blogs')</h3>
                <a wire:navigate href="{{ route('web.blogs') }}" class="text-sm text-primary hover:underline">@lang('View All')</a>
            </div>
            <div class="space-y-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($latestBlogs as $blog)
                    <div class="flex items-center bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <a wire:navigate href="{{ route('web.blog.details', $blog->slug) }}" class="block flex-shrink-0 w-24 h-24 bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center overflow-hidden">
                            @php $img = method_exists($blog,'getFirstMediaUrl') ? $blog->getFirstMediaUrl('blog','avatar') : null; @endphp
                            @if($img)
                                <img src="{{ $img }}" onerror="{{ getErrorImage() }}" alt="Blog image" class="h-full w-full object-cover">
                            @else
                                <i class='bx bxs-book text-4xl text-blue-500'></i>
                            @endif
                        </a>
                        <div class="flex-1 p-4">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white line-clamp-2">
                                <a wire:navigate href="{{ route('web.blog.details', $blog->slug) }}">{{ $blog->title }}</a>
                            </h4>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ optional($blog->blogCategory)->name }} • {{ optional($blog->created_at)->format('d M Y') }}
                            </p>

                            <div class="mt-2 flex items-center justify-between">
                                <a wire:navigate href="{{ route('web.blog.details', $blog->slug) }}" class="text-sm text-primary hover:underline">@lang('Read')</a>
                                @if($blog->user)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $blog->user->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
