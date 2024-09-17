<?php

namespace App\Livewire\App;

use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public $photo;
    public $image_url;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $details;
    public $placeHolder;


    public $mailMailer;
    public $mailHost;
    public $mailPort;
    public $mailUsername;
    public $mailPassword;
    public $mailEncryption;
    public $mailFromAddress;
    public $mailFromName;



    public $logo_url;
    public $logoImage;
    public $logoImageUrl;
    public $icon_url;
    public $iconImage;
    public $iconImageUrl;


    public $githubClientId;
    public $githubClientSecret;
    public $googleClientId;
    public $googleClientSecret;



    public $appName;
    public $appEnv;
    public $appDebug;
    public $appUrl;
    public $appLocale;
    public $queueConnection;
    public $appTimezone;

    public $pusherAppId;
    public $pusherAppKey;
    public $pusherAppSecret;
    public $pusherAppCluster;
    public $pusherHost;
    public $pusherPort;
    public $pusherScheme;
    public $vapidPublicKey;
    public $vapidPrivateKey;

    public function updatePusherAndVapidSettings()
    {
        $this->authorize('app.settings.edit');

        $this->validate([
            'pusherAppId' => 'required|string|max:255',
            'pusherAppKey' => 'required|string|max:255',
            'pusherAppSecret' => 'required|string|max:255',
            'pusherAppCluster' => 'required|string|max:255',
            'pusherHost' => 'nullable|string|max:255',
            'pusherPort' => 'nullable|numeric',
            'pusherScheme' => 'nullable|string|max:10',
            'vapidPublicKey' => 'required|string|max:255',
            'vapidPrivateKey' => 'required|string|max:255',
        ]);

        // Update Pusher settings in the database or environment file
        Setting::updateOrCreate(['key' => 'pusherAppId'], ['value' => str_replace(' ', '_', $this->pusherAppId)]);
        Setting::updateOrCreate(['key' => 'pusherAppKey'], ['value' => str_replace(' ', '_', $this->pusherAppKey)]);
        Setting::updateOrCreate(['key' => 'pusherAppSecret'], ['value' => str_replace(' ', '_', $this->pusherAppSecret)]);
        Setting::updateOrCreate(['key' => 'pusherAppCluster'], ['value' => str_replace(' ', '_', $this->pusherAppCluster)]);
        Setting::updateOrCreate(['key' => 'pusherHost'], ['value' => str_replace(' ', '_', $this->pusherHost)]);
        Setting::updateOrCreate(['key' => 'pusherPort'], ['value' => str_replace(' ', '_', $this->pusherPort)]);
        Setting::updateOrCreate(['key' => 'pusherScheme'], ['value' => str_replace(' ', '_', $this->pusherScheme)]);
        Setting::updateOrCreate(['key' => 'vapidPublicKey'], ['value' => str_replace(' ', '_', $this->vapidPublicKey)]);
        Setting::updateOrCreate(['key' => 'vapidPrivateKey'], ['value' => str_replace(' ', '_', $this->vapidPrivateKey)]);

        // Update environment variables
        $this->updateEnv('PUSHER_APP_ID', str_replace(' ', '_', $this->pusherAppId));
        $this->updateEnv('PUSHER_APP_KEY', str_replace(' ', '_', $this->pusherAppKey));
        $this->updateEnv('PUSHER_APP_SECRET', str_replace(' ', '_', $this->pusherAppSecret));
        $this->updateEnv('PUSHER_APP_CLUSTER', str_replace(' ', '_', $this->pusherAppCluster));
        $this->updateEnv('PUSHER_HOST', str_replace(' ', '_', $this->pusherHost));
        $this->updateEnv('PUSHER_PORT', str_replace(' ', '_', $this->pusherPort));
        $this->updateEnv('PUSHER_SCHEME', str_replace(' ', '_', $this->pusherScheme));
        $this->updateEnv('VAPID_PUBLIC_KEY', str_replace(' ', '_', $this->vapidPublicKey));
        $this->updateEnv('VAPID_PRIVATE_KEY', str_replace(' ', '_', $this->vapidPrivateKey));

        $this->alert('success', __('Pusher and VAPID settings updated successfully!'));
    }
    public function updateAppSettings()
    {
        $this->authorize('app.settings.edit');

        $this->validate([
            'appName' => 'required|string|max:255',
            'appTimezone' => 'required|string|max:255',
            'appEnv' => 'required|string|max:255',
            'appDebug' => 'required',
            'appUrl' => 'required|url|max:255',
            'appLocale' => 'required|string|max:5',
            'queueConnection' => 'required|string|max:255',
        ]);

//        dd($this->appDebug);
        Setting::updateOrCreate(['key' => 'appName'], ['value' => str_replace(' ', '_', $this->appName)]);
        Setting::updateOrCreate(['key' => 'appEnv'], ['value' => str_replace(' ', '_', $this->appEnv)]);
        Setting::updateOrCreate(['key' => 'appDebug'], ['value' => str_replace(' ', '_', $this->appDebug)]);
        Setting::updateOrCreate(['key' => 'appTimezone'], ['value' => str_replace(' ', '_', $this->appTimezone)]);
        Setting::updateOrCreate(['key' => 'appUrl'], ['value' => str_replace(' ', '_', $this->appUrl)]);
        Setting::updateOrCreate(['key' => 'appLocale'], ['value' => str_replace(' ', '_', $this->appLocale)]);
        Setting::updateOrCreate(['key' => 'queueConnection'], ['value' => str_replace(' ', '_', $this->queueConnection)]);

        // Update environment variables
        $this->updateEnv('APP_NAME', str_replace(' ', '_', $this->appName));
        $this->updateEnv('APP_ENV', str_replace(' ', '_', $this->appEnv));
        $this->updateEnv('APP_DEBUG', str_replace(' ', '_', $this->appDebug));
        $this->updateEnv('APP_TIMEZONE', str_replace(' ', '_', $this->appTimezone));
        $this->updateEnv('APP_URL', str_replace(' ', '_', $this->appUrl));
        $this->updateEnv('APP_LOCALE', str_replace(' ', '_', $this->appLocale));
        $this->updateEnv('QUEUE_CONNECTION', str_replace(' ', '_', $this->queueConnection));

        $this->alert('success', __('App settings updated successfully!'));
    }
    public function updateOAuth()
    {
        $this->authorize('app.settings.edit');

        $this->validate([
            'githubClientId' => 'required|string|max:255',
            'githubClientSecret' => 'required|string|max:255',
            'googleClientId' => 'required|string|max:255',
            'googleClientSecret' => 'required|string|max:255',
        ]);

        // Update settings
        Setting::updateOrCreate(['key' => 'githubClientId'], ['value' => $this->githubClientId]);
        Setting::updateOrCreate(['key' => 'githubClientSecret'], ['value' => $this->githubClientSecret]);
        Setting::updateOrCreate(['key' => 'googleClientId'], ['value' => $this->googleClientId]);
        Setting::updateOrCreate(['key' => 'googleClientSecret'], ['value' => $this->googleClientSecret]);

        // Update .env file
        $this->updateEnv('GITHUB_CLIENT_ID', $this->githubClientId);
        $this->updateEnv('GITHUB_CLIENT_SECRET', $this->githubClientSecret);
        $this->updateEnv('GOOGLE_CLIENT_ID', $this->googleClientId);
        $this->updateEnv('GOOGLE_CLIENT_SECRET', $this->googleClientSecret);

        $this->alert('success', __('Data saved successfully!'));
    }
    public function updateImage()
    {
        $this->authorize('app.settings.edit');

        $this->validate([
            'logoImage' => 'nullable|image|max:2048',
            'iconImage' => 'nullable|image|max:2048',
            'logo_url' => 'nullable|url', // 2MB Max
            'icon_url' => 'nullable|url', // 2MB Max

        ]);
        $setting = Setting::first(); // Retrieve the appropriate model instance
       $logo= Setting::updateOrCreate(['key' => 'logoImage'], ['value' => 'logoImage']);
       $icon= Setting::updateOrCreate(['key' => 'iconImage'], ['value' => 'iconImage']);
        if ($this->logo_url) {
            $extension = pathinfo(parse_url($this->logo_url, PHP_URL_PATH), PATHINFO_EXTENSION);
//            $logo->clearMediaCollection('logo');
            $media =  $logo->addMediaFromUrl($this->logo_url)->usingFileName($logo->key. '.' . $extension)->toMediaCollection('logo');
            $path = storage_path("app/public/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
            $this->alert('success', __('Profile photo updated successfully.'));
        }elseif ($this->logoImage) {
//            $logo->clearMediaCollection('logo');
            $media = $logo->addMedia($this->logoImage->getRealPath())->usingFileName($logo->key. '.' . $this->logoImage->getClientOriginalExtension())
                ->toMediaCollection('logo');
            $path = storage_path("app/public/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
            $this->reset('logoImage');
        }

        if ($this->icon_url) {
            $extension = pathinfo(parse_url($this->icon_url, PHP_URL_PATH), PATHINFO_EXTENSION);
            $media =  $icon->addMediaFromUrl($this->icon_url)->usingFileName($icon->key. '.' . $extension)->toMediaCollection('icon');
            $path = storage_path("app/public/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
            $this->alert('success', __('Profile photo updated successfully.'));
        }elseif ($this->iconImage) {
            $media = $icon->addMedia($this->iconImage->getRealPath())->usingFileName($icon->key. '.' . $this->iconImage->getClientOriginalExtension())
                ->toMediaCollection('icon');
            $path = storage_path("app/public/".$media->id.'/'. $media->file_name);
            if (file_exists($path)) {
                unlink($path);
            }
            $this->reset('iconImage');
        }
        $this->alert('success', __('Data saved successfully!'));
        $this->reset('logoImage', 'logo_url', 'iconImage', 'icon_url');

    }
    public function updateGeneral()
    {
        $this->authorize('app.settings.edit');

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'details' => 'nullable|string|max:500',
            'placeHolder' => 'nullable|url|max:500',
        ]);
        Setting::updateOrCreate(['key' => 'name'], ['value' => $this->name]);
        Setting::updateOrCreate(['key' => 'email'], ['value' => $this->email]);
        Setting::updateOrCreate(['key' => 'phone'], ['value' => $this->phone]);
        Setting::updateOrCreate(['key' => 'address'], ['value' => $this->address]);
        Setting::updateOrCreate(['key' => 'details'], ['value' => $this->details]);
        Setting::updateOrCreate(['key' => 'placeHolder'], ['value' => $this->placeHolder]);
        $this->alert('success', __('Data saved successfully!'));

    }
    public function updateMail()
    {
        $this->authorize('app.settings.edit');

        $this->validate([
            'mailMailer' => 'required|string|max:255',
            'mailHost' => 'required|string|max:255',
            'mailPort' => 'required|numeric',
            'mailUsername' => 'nullable|string|max:255',
            'mailPassword' => 'nullable|string|max:255',
            'mailEncryption' => 'nullable|string|max:10',
            'mailFromAddress' => 'required|email|max:255',
            'mailFromName' => 'required|string|max:255',
        ]);
        Setting::updateOrCreate(['key' => 'mailMailer'], ['value' => str_replace(' ', '_',$this->mailMailer)]);
        Setting::updateOrCreate(['key' => 'mailHost'], ['value' => str_replace(' ', '_',$this->mailHost)]);
        Setting::updateOrCreate(['key' => 'mailPort'], ['value' => str_replace(' ', '_',$this->mailPort)]);
        Setting::updateOrCreate(['key' => 'mailUsername'], ['value' => str_replace(' ', '_',$this->mailUsername)]);
        Setting::updateOrCreate(['key' => 'mailPassword'], ['value' => str_replace(' ', '_',$this->mailPassword)]);
        Setting::updateOrCreate(['key' => 'mailEncryption'], ['value' => str_replace(' ', '_',$this->mailEncryption)]);
        Setting::updateOrCreate(['key' => 'mailFromAddress'], ['value' => str_replace(' ', '_',$this->mailFromAddress)]);
        Setting::updateOrCreate(['key' => 'mailFromName'], ['value' => str_replace(' ', '_',$this->mailFromName)]);

        // Update mail settings
        $this->updateEnv('MAIL_MAILER', str_replace(' ', '_',$this->mailMailer));
        $this->updateEnv('MAIL_HOST', str_replace(' ', '_',$this->mailHost));
        $this->updateEnv('MAIL_PORT', str_replace(' ', '_',$this->mailPort));
        $this->updateEnv('MAIL_USERNAME', str_replace(' ', '_',$this->mailUsername));
        $this->updateEnv('MAIL_PASSWORD', str_replace(' ', '_',$this->mailPassword));
        $this->updateEnv('MAIL_ENCRYPTION', str_replace(' ', '_',$this->mailEncryption));
        $this->updateEnv('MAIL_FROM_ADDRESS', str_replace(' ', '_',$this->mailFromAddress));
        $this->updateEnv('MAIL_FROM_NAME', str_replace(' ', '_',$this->mailFromName));

        $this->alert('success', __('Data saved successfully!'));
    }

    public function mount()
    {
        $this->name = setup('name', 'laravel');
        $this->email = setup('email', 'example@mail.com');
        $this->phone = setup('phone', '12345678903');
        $this->address = setup('address', '');
        $this->details = setup('details', '');
        $this->placeHolder = setup('placeHolder', '');

        $this->logoImageUrl = Setting::where('key', 'logoImage')->first();
        $this->iconImageUrl = Setting::where('key', 'iconImage')->first();


        $this->mailMailer = setup('mailMailer', env('MAIL_MAILER', 'default_Mailer'));
        $this->mailHost = setup('mailHost', env('MAIL_HOST', 'default_host'));
        $this->mailPort = setup('mailPort', env('MAIL_PORT', '1025'));
        $this->mailUsername = setup('mailUsername', env('MAIL_USERNAME', 'default_username'));
        $this->mailPassword = setup('mailPassword', env('MAIL_PASSWORD', 'default_password'));
        $this->mailEncryption = setup('mailEncryption', env('MAIL_ENCRYPTION', 'null'));
        $this->mailFromAddress = setup('mailFromAddress', env('MAIL_FROM_ADDRESS', 'default@example.com'));
        $this->mailFromName = setup('mailFromName', env('MAIL_FROM_NAME', 'default_name'));

        $this->githubClientId = setup('githubClientId', env('GITHUB_CLIENT_ID', ''));
        $this->githubClientSecret = setup('githubClientSecret', env('GITHUB_CLIENT_SECRET', ''));
        $this->googleClientId = setup('googleClientId', env('GOOGLE_CLIENT_ID', ''));
        $this->googleClientSecret = setup('googleClientSecret', env('GOOGLE_CLIENT_SECRET', ''));

        $this->appName = setup('appName', env('APP_NAME', 'Laravel'));
        $this->appEnv = setup('appEnv', env('APP_ENV', 'local'));
        $this->appDebug = (bool) setup('appDebug', env('APP_DEBUG', true));
        $this->appTimezone = setup('appTimezone', env('APP_TIMEZONE', 'UTC'));
        $this->appUrl = setup('appUrl', env('APP_URL', 'http://localhost'));
        $this->appLocale = setup('appLocale', env('APP_LOCALE', 'en'));
        $this->queueConnection = setup('queueConnection', env('QUEUE_CONNECTION', 'database'));

        $this->pusherAppId = setup('pusherAppId', env('PUSHER_APP_ID', 'default_pusher_app_id'));
        $this->pusherAppKey = setup('pusherAppKey', env('PUSHER_APP_KEY', 'default_pusher_app_key'));
        $this->pusherAppSecret = setup('pusherAppSecret', env('PUSHER_APP_SECRET', 'default_pusher_app_secret'));
        $this->pusherAppCluster = setup('pusherAppCluster', env('PUSHER_APP_CLUSTER', 'mt1'));
        $this->pusherHost = setup('pusherHost', env('PUSHER_HOST', null));
        $this->pusherPort = setup('pusherPort', env('PUSHER_PORT', null));
        $this->pusherScheme = setup('pusherScheme', env('PUSHER_SCHEME', 'https'));
        $this->vapidPublicKey = setup('vapidPublicKey', env('VAPID_PUBLIC_KEY', 'default_vapid_public_key'));
        $this->vapidPrivateKey = setup('vapidPrivateKey', env('VAPID_PRIVATE_KEY', 'default_vapid_private_key'));

    }

    protected function updateEnv($key, $value)
    {
        $this->authorize('app.settings.edit');

        $path = base_path('.env');

        if (!File::exists($path)) {
            $this->alert('error', __('this file does not exist:' . $path));
            return;
        }

        $envFile = File::get($path);
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $envFile)) {
            $envFile = preg_replace($pattern, $replacement, $envFile);
        } else {
            $envFile .= "\n{$replacement}";
        }

        File::put($path, $envFile);

        // Clear the configuration cache
//        \Artisan::call('config:cache');
    }
    public function render()
    {
        $this->authorize('app.settings.index');
        return view('livewire.app.setting-component');
    }
}
