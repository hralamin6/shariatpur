<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCategory extends Model
{
    use HasFactory;

    protected $guarded=['id'];
//    protected $fillable = ['user_id', 'name', 'bangla_name', 'icon', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
