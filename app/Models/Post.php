<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $guarded = ['id'];

//    public function comments()
//    {
//        return $this->hasMany(Comment::class)->orderBy('id', 'desc');
//    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('postImages')->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('10')->nonQueued();

        });
        $this->addMediaCollection('post')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('10')->nonQueued();

        });
    }
    protected $casts = [
        'tags' => 'array',  // Cast tags field to array
        'published_at' => 'datetime',
    ];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with User (Author)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Mutator to ensure slug is URL-friendly
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Str::slug($value);
    }
}
