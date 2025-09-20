<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;
    use NotifiesAdminsOnDelete;

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($blogCategory) {
            $blogCategory->slug = Str::slug($blogCategory->name);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}

