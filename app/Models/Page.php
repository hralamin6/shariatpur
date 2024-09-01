<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use  InteractsWithMedia;
    protected $guarded = ['id'];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('10')->nonQueued();
        });
        $this->addMediaCollection('icon')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('10')->nonQueued();
        });
    }
    public static function getByKey($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (isset($setting)) {
            return $setting->value;
        }else{
            return $default;
        }
    }
}
