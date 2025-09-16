<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }
}

