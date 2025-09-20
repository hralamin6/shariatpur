<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Doctor extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use NotifiesAdminsOnDelete;

    protected $guarded = ['id'];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('doctor')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('avatar')->quality('50')->nonQueued();

        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(DoctorCategory::class, 'doctor_category_id');
    }


//    public function registerMediaConversions(Media $media = null): void
//    {
//        $this->addMediaConversion('thumb')
//            ->width(120)
//            ->height(120)
//            ->quality(70)
//            ->nonQueued();
//    }
}
