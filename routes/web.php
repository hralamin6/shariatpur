<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Web\HomeComponent::class)->name('web.home');
Route::get('/bus-routes', \App\Livewire\Web\Bus\BusRouteComponent::class)->name('web.bus.routes');
Route::get('/buses/{route_id?}', \App\Livewire\Web\Bus\BusComponent::class)->name('web.bus.buses');

// Train
Route::get('/train-routes', \App\Livewire\Web\Train\TrainRouteComponent::class)->name('web.train.routes');
Route::get('/trains/{route_id?}', \App\Livewire\Web\Train\TrainComponent::class)->name('web.train.trains');

// Launch
Route::get('/launch-routes', \App\Livewire\Web\Launch\LaunchRouteComponent::class)->name('web.launch.routes');
Route::get('/launches/{route_id?}', \App\Livewire\Web\Launch\LaunchComponent::class)->name('web.launch.launches');

// Places & Hotels
Route::get('/places', \App\Livewire\Web\Place\PlaceComponent::class)->name('web.places');
Route::get('/hotels', \App\Livewire\Web\Hotel\HotelComponent::class)->name('web.hotels');
Route::get('/restaurants', \App\Livewire\Web\Restaurant\RestaurantComponent::class)->name('web.restaurants');
Route::get('/beauty-parlors', \App\Livewire\Web\BeautyParlor\BeautyParlorComponent::class)->name('web.beauty_parlors');
Route::get('/hotlines', \App\Livewire\Web\Hotline\HotlineComponent::class)->name('web.hotlines');
Route::get('/works', \App\Livewire\Web\Work\WorkComponent::class)->name('web.works');
Route::get('/blood-donors', \App\Livewire\Web\BloodDonor\BloodDonorComponent::class)->name('web.blood_donors');
Route::get('/polices', \App\Livewire\Web\Police\PoliceComponent::class)->name('web.police');
Route::get('/institution-types', \App\Livewire\Web\Institution\InstitutionTypeComponent::class)->name('web.institution.types');
Route::get('/institutions/{type_id?}', \App\Livewire\Web\Institution\InstitutionComponent::class)->name('web.institutions');
Route::get('/notices', \App\Livewire\Web\Notice\NoticeComponent::class)->name('web.notices');
Route::get('/notice/{id?}', \App\Livewire\Web\Notice\NoticeDetailsComponent::class)->name('web.notice.details');

Route::get('/hospitals', \App\Livewire\Web\Hospital\HospitalComponent::class)->name('web.hospitals');
Route::get('/diagnostic-centers', \App\Livewire\Web\Diagnostic\DiagnosticCenterComponent::class)->name('web.diagnostic_centers');
Route::get('/fire-services', \App\Livewire\Web\FireService\FireServiceComponent::class)->name('web.fire_services');
Route::get('/electricity-offices', \App\Livewire\Web\ElectricityOffice\ElectricityOfficeComponent::class)->name('web.electricity_offices');
Route::get('/courier-services', \App\Livewire\Web\CourierService\CourierServiceComponent::class)->name('web.courier_services');
Route::get('/doctor/categories', \App\Livewire\Web\Doctor\DoctorCategoryComponent::class)->name('web.doctor.categories');
Route::get('/doctor/categories/{cat_id?}', \App\Livewire\Web\Doctor\DoctorComponent::class)->name('web.doctor');

// Houses (rent)
Route::get('/house-types', \App\Livewire\Web\House\HouseTypeComponent::class)->name('web.house.types');
Route::get('/houses/{type_id?}', \App\Livewire\Web\House\HouseComponent::class)->name('web.houses');

// Cars (rent)
Route::get('/car-types', \App\Livewire\Web\Car\CarTypeComponent::class)->name('web.car.types');
Route::get('/cars/{type_id?}', \App\Livewire\Web\Car\CarComponent::class)->name('web.cars');

// Servicemen (like House)
Route::get('/serviceman-types', \App\Livewire\Web\Serviceman\ServicemanTypeComponent::class)->name('web.serviceman.types');
Route::get('/servicemen/{type_id?}', \App\Livewire\Web\Serviceman\ServicemanComponent::class)->name('web.servicemen');

// Sell (classifieds)
Route::get('/sell-categories', \App\Livewire\Web\Sell\SellCategoryComponent::class)->name('web.sell.categories');
Route::get('/sells/{cat_id?}', \App\Livewire\Web\Sell\SellComponent::class)->name('web.sells');

// Blogs
Route::get('/blog-categories', \App\Livewire\Web\Blog\BlogCategoryComponent::class)->name('web.blog.categories');
Route::get('/blogs/{cat_id?}', \App\Livewire\Web\Blog\BlogComponent::class)->name('web.blogs');
Route::get('/blog/{slug}', \App\Livewire\Web\Blog\BlogDetailsComponent::class)->name('web.blog.details');

// Entrepreneurs (like Hotels)
Route::get('/entrepreneurs', \App\Livewire\Web\Entrepreneur\EntrepreneurComponent::class)->name('web.entrepreneurs');

// News
Route::get('/news-categories', \App\Livewire\Web\News\NewsCategoryComponent::class)->name('web.news.categories');
Route::get('/news/{cat_id?}', \App\Livewire\Web\News\NewsComponent::class)->name('web.news');
Route::get('/news-item/{slug}', \App\Livewire\Web\News\NewsDetailsComponent::class)->name('web.news.details');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('profile', \App\Livewire\Web\ProfileComponent::class)->name('web.profile');

    Route::get('app', \App\Livewire\App\DashboardComponent::class)->name('app.dashboard');
    Route::get('app/roles', \App\Livewire\App\RoleComponent::class)->name('app.roles');
    Route::get('app/backups', \App\Livewire\App\BackupComponent::class)->name('app.backups');
    Route::get('app/users', \App\Livewire\App\UserComponent::class)->name('app.users');
    Route::get('app/profile', \App\Livewire\App\ProfileComponent::class)->name('app.profile');
    Route::get('app/user/{user}', \App\Livewire\App\UserDetailComponent::class)->name('app.user.detail');
    Route::get('app/setting', \App\Livewire\App\SettingComponent::class)->name('app.setting');
    Route::get('app/chat', \App\Livewire\App\ChatComponent::class)->name('app.chat');
    Route::get('app/pages', \App\Livewire\App\PageComponent::class)->name('app.pages');
    Route::get('app/categories', \App\Livewire\App\CategoryComponent::class)->name('app.categories');
    Route::get('app/posts', \App\Livewire\App\PostComponent::class)->name('app.posts');
    Route::get('app/notifications', \App\Livewire\App\NotificationComponent::class)->name('app.notifications');
    Route::get('app/translate', \App\Livewire\App\TranslateComponent::class)->name('app.translate');
    // Sponsors (backend)
    Route::get('app/sponsors', \App\Livewire\App\SponsorComponent::class)->name('app.sponsors');
});

require __DIR__.'/auth.php';

// Secure Web Push subscription endpoint
Route::post('/subscribe', function (Request $request) {
    $validated = $request->validate([
        'endpoint' => 'required|string',
        'keys.p256dh' => 'required|string',
        'keys.auth' => 'required|string',
    ]);

    $user = $request->user();
    if (! $user) {
        abort(401);
    }

    $user->updatePushSubscription(
        $validated['endpoint'],
        $validated['keys']['p256dh'],
        $validated['keys']['auth']
    );

    return response()->json(['success' => true], 200);
})->middleware('auth')->name('webpush.subscribe');

// PWA manifest and offline routes
Route::group(['as' => 'laravelpwa.'], function () {
    Route::get('/manifest.json', [\App\Http\Controllers\LaravelPWAController::class, 'manifestJson'])
        ->name('manifest');
    // Support both with and without trailing slash
    Route::get('/offline', [\App\Http\Controllers\LaravelPWAController::class, 'offline'])->name('offline');
});

// Restrict artisan command execution to local environment and authenticated users, allowlist only
if (app()->environment('local')) {
    Route::get('cmd/{slug}', function ($slug = null) {
        $allowed = [
            'cache:clear', 'config:cache', 'config:clear', 'route:clear', 'route:cache', 'view:clear',
            'queue:restart', 'migrate', 'migrate:fresh', 'migrate:refresh', 'optimize', 'optimize:clear',
        ];
        if (! $slug || ! in_array($slug, $allowed, true)) {
            abort(403, 'Command not allowed.');
        }
        Artisan::call($slug);
        $output = Artisan::output();

        return '<pre>'.htmlspecialchars($output).'</pre>';
    })->where('slug', '[A-Za-z0-9:\\-]+')->middleware('auth');
}

Route::get('{slug}', \App\Livewire\Web\PageComponent::class)->name('web.page');
