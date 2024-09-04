<?php

namespace App;

use App\Models\User;
use App\Notifications\DeleteNotification;
use App\Models\Role;
use App\Notifications\UserApproved;
use Illuminate\Support\Facades\Notification;

trait NotifiesAdminsOnDelete
{


    public static function bootNotifiesAdminsOnDelete()
    {
        static::created(function ($model) {
            // Get the authenticated user who created the record
            $user = auth()->user();

            $role = Role::where('name', 'admin')->first();

            // If the role exists, send the notification with afterCommit
            if ($role && $user) {
                $role->notify(new DeleteNotification($user, $model, 'created'));
            }
        });
        static::updating(function ($model) {
            // Get the authenticated user who is performing the action
            $user = auth()->user();

            // Capture the original model data before the update
            $role = Role::where('name', 'admin')->first();

            // If the role exists, send the notification with afterCommit
            if ($role && $user) {
                $role->notify(new DeleteNotification($user, $model, 'edited'));
//                $user->notify(new UserApproved($user->name, $body, $user));
                Notification::send($role->users, new UserApproved($user->name, $body='the quick brown fox', $user));
            }
        });

        static::deleting(function ($model) {
            // Get the authenticated user who is deleting the record
            $user = auth()->user();
            // Find the role that should be notified (e.g., Admin role)
            $role = Role::where('name', 'admin')->first();

            // If the role exists, send the notification
            if ($role && $user) {
//                $role->notify((new DeleteNotification($user, $model, 'deleted')));
                Notification::sendNow($role, new DeleteNotification($user, $model, 'deleted'));
            }
        });
    }
}
