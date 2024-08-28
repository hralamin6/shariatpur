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

    public function updateOAuth()
    {
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
        $this->email = setup('email', 'laravel');
        $this->phone = setup('phone', 'laravel');
        $this->address = setup('address', 'laravel');
        $this->details = setup('details', 'laravel');
        $this->placeHolder = setup('placeHolder', 'laravel');

        $this->mailMailer = setup('mailMailer', 'default_Mailer');
        $this->mailHost = setup('mailHost', 'default_host');
        $this->mailPort = setup('mailPort', '1025');
        $this->mailUsername = setup('mailUsername', 'default_username');
        $this->mailPassword = setup('mailPassword', 'default_password');
        $this->mailEncryption = setup('mailEncryption', 'null');
        $this->mailFromAddress = setup('mailFromAddress', 'default@example.com');
        $this->mailFromName = setup('mailFromName', 'default_name');

        $this->logoImageUrl = Setting::where('key', 'logoImage')->first();
        $this->iconImageUrl = Setting::where('key', 'iconImage')->first();

        $this->githubClientId = setup('githubClientId', '');
        $this->githubClientSecret = setup('githubClientSecret', '');
        $this->googleClientId = setup('googleClientId', '');
        $this->googleClientSecret = setup('googleClientSecret', '');

    }

    protected function updateEnv($key, $value)
    {
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
//        $item = Setting::get();
        return view('livewire.app.setting-component');
    }
}
