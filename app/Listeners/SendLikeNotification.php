<?php

namespace App\Listeners;

use App\Events\MaterialLiked;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLikeNotification
{
    protected $milestones = [5, 10, 20, 50, 100]; // 通知を送る特定の数

    public function handle(MaterialLiked $event)
    {
        $likesCount = $event->material->like_count;



        if (in_array($likesCount, $this->milestones)) {
            Notification::create([
                'user_id' => $event->material->user_id,
                'type' => 'like',
                'data' => [
                    'material_id' => $event->material->id,
                    'material_name' => $event->material->name,
                    'like_count' => $likesCount
                ],
            ]);
        }
    }
}