<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendFirstLoginNotification
{
    public function handle(Login $event)
    {
        // last_login_at が null の場合、つまり初回ログインの場合に通知を送信
        if (is_null($event->user->last_login_at)) {

            Notification::create([
                'user_id' => $event->user->id,
                'type' => 'first_login',
                'data' => [
                    'login_at' => Carbon::now(),
                ],
            ]);

        }
    }
}