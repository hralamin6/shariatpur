<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/' , function (){
    return view('test');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('app', \App\Livewire\App\DashboardComponent::class)->name('app.dashboard');
    Route::get('app/roles', \App\Livewire\App\RoleComponent::class)->name('app.roles');
    Route::get('app/backups', \App\Livewire\App\BackupComponent::class)->name('app.backups');
    Route::get('app/users', \App\Livewire\App\UserComponent::class)->name('app.users');
    Route::get('app/profile', \App\Livewire\App\ProfileComponent::class)->name('app.profile');
    Route::get('app/setting', \App\Livewire\App\SettingComponent::class)->name('app.setting');
    Route::get('app/chat', \App\Livewire\App\ChatComponent::class)->name('app.chat');
    Route::get('app/notify', \App\Livewire\App\NotificationComponent::class)->name('app.notify');

});

require __DIR__.'/auth.php';


Route::post('/subscribe', function (Request $request) {
    $user = Auth::user();
    $user->updatePushSubscription(
        $request->endpoint,
        $request->keys['p256dh'],
        $request->keys['auth']
    );
    return response()->json(['success' => true], 200);
});
