<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 use Illuminate\Support\Facades\Storage;
 use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
 use Spatie\MediaLibrary\MediaCollections\Models\Media;

 class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory, Notifiable;
    use Notifiable, InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('10')->nonQueued();

        });;
    }
//     public function registerMediaConversions(Media $media = null): void
//     {
//
//                 $this->addMediaConversion('thumb')
//                     ->width(300)
//                     ->height(100)->quality('50')->nonQueued();
//
//     }

     public function role()
    {
        return $this->belongsTo(Role::class)->withDefault();
    }
    public function hasPermission($permission): bool
    {
        return $this->role->permissions()->where('slug', $permission)->first() ? true : false;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
