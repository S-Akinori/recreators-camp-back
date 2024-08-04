<?php

namespace App\Listeners;

use App\Events\MaterialFavorited;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFavoriteNotification
{
    protected $milestones = [5, 10, 20, 50, 100]; // 通知を送る特定の数

    public function handle(MaterialFavorited $event)
    {
        $favoritesCount = $event->material->favorites_count;

        if (in_array($favoritesCount, $this->milestones)) {
            Notification::create([
                'user_id' => $event->material->user_id,
                'type' => 'favorite',
                'data' => [
                    'material_id' => $event->material->id,
                    'material_name' => $event->material->name,
                    'favorites_count' => $favoritesCount
                ],
            ]);
        }
    }
}
