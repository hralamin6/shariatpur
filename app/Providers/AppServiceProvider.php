<?php

namespace App\Providers;

use App\Models\User;
use Google\Service\Drive;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use League\Flysystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Masbug\Flysystem\GoogleDriveAdapter;
use Pusher\PushNotifications\PushNotifications;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $url = env('APP_URL', 'http://localhost:8000');
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['scheme']) && $parsedUrl['scheme'] === 'https') {
            URL::forceScheme('https');
        }
        $instanceId = config('services.pusher_beams.instance_id');
        $secretKey = config('services.pusher_beams.secret_key');

        if ($instanceId && $secretKey) {
            app()->singleton('PusherBeams', function () use ($instanceId, $secretKey) {
                return new PushNotifications([
                    'instanceId' => $instanceId,
                    'secretKey' => $secretKey,
                ]);
            });
        }
        \Blade::if('role', function ($role) {
            return \Auth::user()->role->slug === $role;
        });

        Gate::define('role', function (User $user, $role) {
            return $user->role->slug === $role;
        });
    }
}
