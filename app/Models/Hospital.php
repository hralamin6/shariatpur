<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Hospital extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use NotifiesAdminsOnDelete;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hospital')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->width(300)
            ->height(200)
            ->quality(75)
            ->nonQueued();
    }
    public function getAvatarUrlAttribute()
    {
        $media = $this->getFirstMedia('hospital');
        if ($media) {
            return asset($media->getUrl());
        }
        return null;
    }


}
