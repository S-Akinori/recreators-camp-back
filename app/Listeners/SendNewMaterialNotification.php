<?php

namespace App\Listeners;

use App\Events\MaterialCreated;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewMaterialNotification
{
    public function handle(MaterialCreated $event)
    {
        $followers = $event->material->user->followers;

        foreach ($followers as $follower) {
            Notification::create([
                'user_id' => $follower->id,
                'type' => 'new_material',
                'data' => [
                    'material_id' => $event->material->id,
                    'material_name' => $event->material->name,
                    'author_id' => $event->material->user->id,
                    'author_name' => $event->material->user->name,
                ],
            ]);
        }
    }
}