<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Bus extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('bus')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->width(300)
            ->height(200)
            ->quality(75)
            ->nonQueued();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function route()
    {
        return $this->belongsTo(BusRoute::class, 'bus_route_id');
    }
}
