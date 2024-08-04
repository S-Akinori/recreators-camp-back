<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFollowNotification
{
    public function handle(UserFollowed $event)
    {
        Notification::create([
            'user_id' => $event->followed->id,
            'type' => 'follow',
            'data' => [
                'follower_id' => $event->follower->id,
                'follower_name' => $event->follower->name
            ],
        ]);
    }
}