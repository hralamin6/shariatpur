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

// Places
Route::get('/places', \App\Livewire\Web\Place\PlaceComponent::class)->name('web.places');

Route::get('/hospitals', \App\Livewire\Web\Hospital\HospitalComponent::class)->name('web.hospitals');
Route::get('/fire-services', \App\Livewire\Web\FireService\FireServiceComponent::class)->name('web.fire_services');
Route::get('/doctor/categories', \App\Livewire\Web\Doctor\DoctorCategoryComponent::class)->name('web.doctor.categories');
Route::get('/doctor/categories/{cat_id?}', \App\Livewire\Web\Doctor\DoctorComponent::class)->name('web.doctor');

// Houses (rent)
Route::get('/house-types', \App\Livewire\Web\House\HouseTypeComponent::class)->name('web.house.types');
Route::get('/houses/{type_id?}', \App\Livewire\Web\House\HouseComponent::class)->name('web.houses');


Route::middleware(['auth', 'verified'])->group(function () {
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

});

require __DIR__ . '/auth.php';


Route::post('/subscribe', function (Request $request) {
    $user = \Illuminate\Support\Facades\Auth::user();
    $user->updatePushSubscription(
        $request->endpoint,
        $request->keys['p256dh'],
        $request->keys['auth']
    );
    return response()->json(['success' => true], 200);
});

Route::group(['as' => 'laravelpwa.'], function () {
    Route::get('/manifest.json', 'App\Http\Controllers\LaravelPWAController@manifestJson')
        ->name('manifest');
    Route::get('/offline/', 'LaravelPWAController@offline');
});

Route::get('cmd/{slug}', function ($slug = null) {
    Artisan::call($slug); // Replace 'your:command' with the actual command.
    $output = Artisan::output();
    return "<pre>" . htmlspecialchars($output) . "</pre>";
});


Route::get('{slug}', \App\Livewire\Web\PageComponent::class)->name('web.page');
