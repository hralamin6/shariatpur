<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{receiver}', function ($user, $id) {
    return (int) $user->id === (int) $id || (int) $user->id === (int) request()->user()->id;
});
