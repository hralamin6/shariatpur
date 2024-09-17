<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    protected $guarded = ['id'];

    use InteractsWithMedia, HasFactory, NotifiesAdminsOnDelete;

    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Recursive relationship for subcategories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = $value === '' ? null : $value;
    }

}
