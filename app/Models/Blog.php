<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('blog')->singleFile();
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

    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(BlogLike::class);
    }

    public function views()
    {
        return $this->hasMany(BlogView::class);
    }

    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
