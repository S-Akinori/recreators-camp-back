<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmailNotification
{
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('カスタム認証メールの件名')
            ->line('カスタムメッセージの内容をここに書きます。')
            ->action('メールアドレスを確認する', $verificationUrl)
            ->line('ありがとうございます！');
    }
}

