<?php

namespace App\Providers;

use Google\Service\Drive;
use Illuminate\Filesystem\Filesystem;
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

// Parse the URL to get its components
        $parsedUrl = parse_url($url);

// Check if the scheme is 'https'
        if (isset($parsedUrl['scheme']) && $parsedUrl['scheme'] === 'https') {
            // Force HTTPS if the scheme is 'https'
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
    }
}
