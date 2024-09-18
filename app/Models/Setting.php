<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
