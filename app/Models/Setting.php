<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = ['id'];

    use NotifiesAdminsOnDelete;
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('10')->nonQueued();
        });
        $this->addMediaCollection('icon')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('10')->nonQueued();
        });

        $this->addMediaCollection('icon')
            ->singleFile()
            ->registerMediaConversions(function (Media $media = null) {
                $this->addMediaConversion('72x72')
                    ->fit(Fit::Crop, 72, 72)
                    ->nonQueued();

                $this->addMediaConversion('96x96')
                    ->fit(Fit::Crop, 96, 96)
                    ->nonQueued();

                $this->addMediaConversion('128x128')
                    ->fit(Fit::Crop, 128, 128)
                    ->nonQueued();

                $this->addMediaConversion('144x144')
                    ->fit(Fit::Crop, 144, 144)
                    ->nonQueued();

                $this->addMediaConversion('152x152')
                    ->fit(Fit::Crop, 152, 152)
                    ->nonQueued();

                $this->addMediaConversion('192x192')
                    ->fit(Fit::Crop, 192, 192)
                    ->nonQueued();

                $this->addMediaConversion('384x384')
                    ->fit(Fit::Crop, 384, 384)
                    ->nonQueued();

                $this->addMediaConversion('512x512')
                    ->fit(Fit::Crop, 512, 512)
                    ->nonQueued();
            });
        $this->addMediaCollection('icon')
            ->singleFile()
            ->registerMediaConversions(function (Media $media = null) {
                collect([
                    '640x1136', '750x1334', '828x1792', '1125x2436',
                    '1242x2208', '1242x2688', '1536x2048', '1668x2224',
                    '1668x2388', '2048x2732'
                ])->each(function ($size) {
                    [$w, $h] = explode('x', $size);
                    $this->addMediaConversion($size)
                        ->fit(Fit::Crop, (int)$w, (int)$h)
                        ->nonQueued();
                });
            });
    }
//    public static function getByKey($key, $default = null)
//    {
//        $setting = self::where('key', $key)->first();
//        if (isset($setting)) {
//            return $setting->value;
//        }else{
//            return $default;
//        }
//    }

    // In your Setting model
    public static function getByKey($key, $default = null)
    {
        // Define a static variable to store settings within the request
        static $settings = [];

        // Check if the setting is already retrieved in this request
        if (!array_key_exists($key, $settings)) {
            $setting = self::where('key', $key)->first();
            $settings[$key] = $setting->value ?? $default; // Store the retrieved setting in memory
        }

        // Return the stored setting value
        return $settings[$key];
    }

}
