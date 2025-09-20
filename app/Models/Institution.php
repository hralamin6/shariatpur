<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Institution extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    use NotifiesAdminsOnDelete;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'established_at' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class)->withDefault();
    }
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InstitutionType::class, 'institution_type_id')->withDefault();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('institution')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->width(300)
            ->height(200)
            ->quality(75)
            ->nonQueued();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
