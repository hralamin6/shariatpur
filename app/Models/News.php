<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class News extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use NotifiesAdminsOnDelete;

    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($news) {
            $news->slug = Str::slug($news->title);
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('news')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->width(800)
            ->height(600)
            ->quality(75)
            ->nonQueued();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class);
    }

    public function likes()
    {
        return $this->hasMany(NewsLike::class);
    }

    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}

