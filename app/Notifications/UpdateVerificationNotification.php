<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UpdateVerificationNotification extends Notification
{
    private $token;
    private $type;

    public function __construct($token, $type)
    {
        $this->token = $token;
        $this->type = $type;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url("/verify-update/{$this->token}");
        $type = ucfirst($this->type);

        return (new MailMessage)
            ->subject("Verify {$type} Update")
            ->line("You are receiving this email because you requested a {$this->type} update.")
            ->action('Verify Update', $url)
            ->line('If you did not request this update, no further action is required.');
    }
}
