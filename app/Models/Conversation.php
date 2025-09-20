<?php

namespace App\Models;

use App\NotifiesAdminsOnDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    use NotifiesAdminsOnDelete;

    protected $guarded='';

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id','id');
    }
}
