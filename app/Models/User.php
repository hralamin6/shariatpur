<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable;
    use Notifiable, InteractsWithMedia;
    use HasPushSubscriptions, NotifiesAdminsOnDelete;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')->singleFile()->registerMediaConversions(function (Media $media = null) {
            $this->addMediaConversion('thumb')->quality('50')->nonQueued();

        });
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

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'id')
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            });
    }
    public function messages()
    {
        return $this->hasMany(Message::class)
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            });
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
            'last_donate_date' => 'date',
            'is_blood_donor' => 'boolean',
        ];
    }

    // Blood donor scopes
    public function scopeBloodDonors($query)
    {
        return $query->where('is_blood_donor', true);
    }

    public function scopeAvailableDonors($query)
    {
        return $query->where('is_blood_donor', true)
                    ->where('donor_status', 'available');
    }

    public function scopeByBloodGroup($query, $bloodGroup)
    {
        return $query->where('blood_group', $bloodGroup);
    }

    // Helper methods for blood donation
    public function canDonate(): bool
    {
        if (!$this->is_blood_donor || $this->donor_status !== 'available') {
            return false;
        }

        if (!$this->last_donate_date) {
            return true;
        }

        // Must wait 3 months (90 days) between donations
        return $this->last_donate_date->addDays(90)->isPast();
    }

    public function getDaysUntilNextDonation(): ?int
    {
        if (!$this->last_donate_date) {
            return 0;
        }

        $nextDonationDate = $this->last_donate_date->addDays(90);
        return $nextDonationDate->isFuture() ? now()->diffInDays($nextDonationDate) : 0;
    }
}
